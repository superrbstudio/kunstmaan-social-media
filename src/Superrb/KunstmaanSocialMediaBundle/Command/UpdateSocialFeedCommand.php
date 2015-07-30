<?php

namespace Superrb\KunstmaanSocialMediaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Superrb\KunstmaanSocialMediaBundle\Entity\Social;
use Superrb\KunstmaanSocialMediaBundle\Entity\Setting;
use GuzzleHttp\Client;

class UpdateSocialFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kuma:socialMedia:update')
            ->setDescription('Updates the social media feed items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting Social Media Feed Update</info>');

        // update instagram if required
        $instagramSetting = $this->getContainer()->get('doctrine')->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->instagram();

        if($instagramSetting->getIsActive())
        {
            //$this->updateInstagram($input, $output, $instagramSetting);
        }

        // update twitter if required
        $twitterSetting = $this->getContainer()->get('doctrine')->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->twitter();

        if($twitterSetting->getIsActive())
        {
            $this->updateTwitter($input, $output, $twitterSetting);
        }
    }

    protected function updateInstagram(InputInterface $input, OutputInterface $output, Setting $settings)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $output->writeln('Updating Instagram');

        try
        {
            $instagram = new Client(array('base_uri' => "https://api.instagram.com/v1/"));
            $query = array('access_token' => $settings->getSetting('access_token'), 'count' => 50);

            if(!$settings->getSetting('hashtag'))
            {
                $response = $instagram->get('users/' . $settings->getSetting('user_id') . '/media/recent', array('query' => $query));
            }
            else
            {
                $response = $instagram->get('tags/' . htmlentities($settings->getSetting('hashtag')) . '/media/recent', array('query' => $query));
            }

            if($response->getStatusCode() == 200)
            {
                $posts = json_decode($response->getBody()->getContents());

                $added = 0;
                $updated = 0;

                if($posts)
                {
                    foreach($posts->data as $post)
                    {
                        $social = $doctrine->getRepository('SuperrbKunstmaanSocialMediaBundle:Social')->findOneBy(array('socialId' => $post->id, 'type' => 'instagram'));

                        if(!$social)
                        {
                            $social = new Social();
                            $social->setSocialId($post->id);
                            $social->setType('instagram');
                            $added++;
                        }
                        else
                        {
                            $updated++;
                        }

                        $social->setUsername($post->user->username);
                        $social->setDatePosted(new \DateTime(date('Y-m-d H:i:s', $post->created_time)));
                        $social->setLink($post->link);
                        if(isset($post->caption->text)) { $social->setInstagramCaption($post->caption->text); }
                        $social->setInstagramImageUrl($post->images->standard_resolution->url);

                        if(isset($post->location->latitude) and isset($post->location->longitude))
                        {
                            $social->setLatitude($post->location->latitude);
                            $social->setLongitude($post->location->longitude);
                        }

                        if($post->type == 'video')
                        {
                            $social->setInstagramVideoUrl($post->videos->standard_resolution->url);
                        }

                        $doctrine->getManager()->persist($social);
                    }
                }


                $output->writeln('Instagram Updated: ' . $added . ' Added, ' . $updated . ' Updated');
                $settings->setSetting('last_updated', date('Y-m-d H:i:s'));
                $doctrine->getManager()->persist($settings);
                $doctrine->getManager()->flush();
            }
            else
            {
                $output->writeln('<error>Unable to update Instagram: ' . $response->getStatusCode() . ' response code given</error>');
            }
        }
        catch (\Exception $e)
        {
            $output->writeln('<error>Unable to update Instagram: ' . $e->getMessage() . '</error>');
        }
    }

    protected function updateTwitter(InputInterface $input, OutputInterface $output, Setting $settings)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $output->writeln('Updating Twitter');

        try
        {
            $twitter = new Client(array('base_uri' => 'https://api.twitter.com'));
            $response = null;

            if($settings->getSetting('user_or_hashtag') == 'Username' and $settings->getSetting('username'))
            {
                $response = $twitter->get('/1.1/statuses/user_timeline.json', array(
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $settings->getSetting('access_token'),
                    ),
                    'query' => array(
                        'count' => 50,
                        'screen_name' => $settings->getSetting('username'),
                    )
                ));
            }

            if($settings->getSetting('user_or_hashtag') == 'Hashtag' and $settings->getSetting('hashtag'))
            {
                $response = $twitter->get('/1.1/search/tweets.json', array(
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $settings->getSetting('access_token'),
                    ),
                    'query' => array(
                        'count' => 50,
                        'q' => '#' . $settings->getSetting('hashtag'),
                    )
                ));
            }

            if($response and $response->getStatusCode() == 200)
            {
                $posts = json_decode($response->getBody()->getContents());

                $added = 0;
                $updated = 0;

                if($posts)
                {
                    if(isset($posts->statuses))
                    {
                        $posts = $posts->statuses;
                    }

                    foreach ($posts as $post)
                    {
                        $social = $doctrine->getRepository('SuperrbKunstmaanSocialMediaBundle:Social')->findOneBy(array('socialId' => $post->id, 'type' => 'twitter'));

                        if(!$social)
                        {
                            $social = new Social();
                            $social->setSocialId($post->id);
                            $social->setType('twitter');
                            $added++;
                        }
                        else
                        {
                            $updated++;
                        }

                        $social->setUsername($post->user->screen_name);
                        $social->setDatePosted(new \DateTime($post->created_at));
                        $social->setLink('https://twitter.com/' . $post->user->screen_name . '/status/' . $post->id);
                        if(isset($post->text)) { $social->setTwitterContent($post->text); }

                        if(isset($post->entities->media[0]) and $post->entities->media[0]->type == 'photo')
                        {
                            $social->setTwitterImageUrl($post->entities->media[0]->media_url_https);
                        }

                        $doctrine->getManager()->persist($social);
                    }
                }

                $output->writeln('Twitter Updated: ' . $added . ' Added, ' . $updated . ' Updated');
                $settings->setSetting('last_updated', date('Y-m-d H:i:s'));
                $doctrine->getManager()->persist($settings);
                $doctrine->getManager()->flush();
            }
            else
            {
                $output->writeln('<error>Unable to update Twitter: ' . $response->getStatusCode() . ' response code given</error>');
            }
        }
        catch (\Exception $e)
        {
            $output->writeln('<error>Unable to update Twitter: ' . $e->getMessage() . '</error>');
        }
    }
}
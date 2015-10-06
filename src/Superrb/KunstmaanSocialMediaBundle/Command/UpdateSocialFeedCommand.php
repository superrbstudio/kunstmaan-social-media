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
            $this->updateInstagram($input, $output, $instagramSetting);
        }

        // update tumblr if required
        $tumblrSetting = $this->getContainer()->get('doctrine')->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->tumblr();

        if($tumblrSetting->getIsActive())
        {
            $this->updateTumblr($input, $output, $tumblrSetting);
        }

        // update twitter if required
        $twitterSetting = $this->getContainer()->get('doctrine')->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->twitter();

        if($twitterSetting->getIsActive())
        {
            $this->updateTwitter($input, $output, $twitterSetting);
        }

        // update vimeo if required
        $vimeoSetting = $this->getContainer()->get('doctrine')->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->vimeo();

        if($vimeoSetting->getIsActive())
        {
            $this->updateVimeo($input, $output, $vimeoSetting);
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

                        $dateTime = new \DateTime(null, new \DateTimeZone('UTC'));
                        $dateTime->setTimestamp($post->created_time);
                        $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
                        $social->setDatePosted($dateTime);

                        $social->setLink($post->link);
                        if(isset($post->caption->text)) { $social->setInstagramCaption(mysql_real_escape_string(utf8_encode($post->caption->text))); }
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

    protected function updateTumblr(InputInterface $input, OutputInterface $output, Setting $settings)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $output->writeln('Updating Tumblr');
        try
        {
            if($settings->getSetting('user_or_hashtag') == 'Username' && $settings->getSetting('tumblr_url'))
            {
                $url = 'blog/' . $settings->getSetting('tumblr_url') . '/posts';
                $tag = '';
            }
            elseif($settings->getSetting('user_or_hashtag') == 'Hashtag' && $settings->getSetting('hashtag'))
            {
                $url = 'tagged';
                $tag = $settings->getSetting('hashtag');
            }
            else
            {
                $url = '';
                $tag = '';
            }

            $client = new Client(array('base_uri' => 'https://api.tumblr.com'));

            if($url)
            {
                $response =  $client->get('/v2/' . $url, [
                    'query' => [
                        'api_key' => $settings->getSetting('consumer_key'),
                        'tag' => $tag,
                        'notes_info' => true]
                ]);
                if($response->getStatusCode() == 200)
                {
                    $data = json_decode($response->getBody()->getContents());
                    switch ($settings->getSetting('user_or_hashtag'))
                    {
                        case 'Username':
                            $posts = $data->response->posts;
                            break;
                        case 'Hashtag':
                            $posts = $data->response;
                            break;
                    }
                    $added = 0;
                    $updated = 0;
                    if($posts)
                    {
                        $type = 'tumblr';
                        //will need to save additional fields for other post types
                        $postTypes = array('text', 'photo', 'video');
                        foreach ($posts as $post)
                        {
                            if(in_array($post->type, $postTypes))
                            {
                                $social = $doctrine->getRepository('SuperrbKunstmaanSocialMediaBundle:Social')->findOneBy(array('socialId' => $post->id, 'type' => $type));

                                if(!$social)
                                {
                                    $social = new Social();
                                    $social->setSocialId($post->id);
                                    $social->setType($type);
                                    $added++;
                                }
                                else
                                {
                                    $updated++;
                                }

                                $social->setUsername($post->blog_name);

                                $dateTime = new \DateTime(null, new \DateTimeZone('UTC'));
                                $dateTime->setTimestamp($post->timestamp);
                                $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
                                $social->setDatePosted($dateTime);

                                $social->setLink($post->post_url);
                                $social->setTumblrMediaType($post->type);

                                if($post->type == 'text')
                                {
                                    $social->setTumblrTitle(mysql_real_escape_string(utf8_encode($post->title)));
                                    $social->setTumblrBodyText(mysql_real_escape_string(utf8_encode($post->body)));
                                }
                                if($post->type == 'photo' || $post->type == 'video')
                                {
                                    $social->setTumblrCaption(mysql_real_escape_string(utf8_encode($post->caption)));
                                }
                                if($post->type == 'photo')
                                {
                                    $image = array_pop($post->photos);
                                    $social->setTumblrImageUrl($image->original_size->url);
                                }
                                if($post->type == 'video')
                                {
                                    $social->setTumblrVideoType($post->video_type);
                                    if($post->video_type == 'tumblr')
                                    {
                                        $social->setTumblrVideoUrl($post->video_url);

                                    } else
                                    {
                                        $largestEmbed = array_pop($post->player);
                                        $social->setTumblrVideoEmbedCode($largestEmbed->embed_code);
                                    }
                                    $social->setTumblrVideoThumbnailImageUrl($post->thumbnail_url);
                                }

                                $doctrine->getManager()->persist($social);
                            }
                        }
                    }

                    $output->writeln('Tumblr Updated: ' . $added . ' Added, ' . $updated . ' Updated');
                    $settings->setSetting('last_updated', date('Y-m-d H:i:s'));
                    $doctrine->getManager()->persist($settings);
                    $doctrine->getManager()->flush();
                }
                else
                {
                    $output->writeln('<error>Unable to update Twitter: ' . $response->getStatusCode() . ' response code given</error>');
                }
            }
        }
        catch (\Exception $e)
        {
            $output->writeln('<error>Unable to update Tumblr: ' . $e->getMessage() . '</error>');
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

                        $dateTime = new \DateTime($post->created_at, new \DateTimeZone('UTC'));
                        $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
                        $social->setDatePosted($dateTime);

                        $social->setLink('https://twitter.com/' . $post->user->screen_name . '/status/' . $post->id);
                        if(isset($post->text)) { $social->setTwitterContent(mysql_real_escape_string(utf8_encode($post->text))); }

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
    protected function updateVimeo(InputInterface $input, OutputInterface $output, Setting $settings)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $output->writeln('Updating Vimeo');

        try
        {
            $client = new Client(array('base_uri' => 'https://api.vimeo.com'));

            if($settings->getSetting('user_or_hashtag') == 'Username' and $settings->getSetting('user_id'))
            {
                $response = $client->get('/users/' . $settings->getSetting('user_id') . '/videos', array(
                    'headers' => array(
                        'Authorization' => 'bearer ' . $settings->getSetting('access_token'),
                    ),
                    'query' => array(
                        'sort' => 'date',
                        'direction' => 'desc',
                        'filter' => 'playable',
                    )
                ));
            }

            if($settings->getSetting('user_or_hashtag') == 'Hashtag' and $settings->getSetting('hashtag'))
            {
                $response = $client->get('/tags/' . $settings->getSetting('hashtag') . '/videos', array(
                    'headers' => array(
                        'Authorization' => 'bearer ' . $settings->getSetting('access_token'),
                    ),
                    'query' => array(
                        'sort' => 'created_time',
                        'direction' => 'desc',
                    )
                ));
            }

            if($response->getStatusCode() == 200)
            {
                $type = 'vimeo';
                $posts = json_decode($response->getBody()->getContents());

                $added = 0;
                $updated = 0;

                foreach($posts->data as $post)
                {
                    $social = $doctrine->getRepository('SuperrbKunstmaanSocialMediaBundle:Social')->findOneBy(array('socialId' => $post->uri, 'type' => $type));

                    if(!$social)
                    {
                        $social = new Social();
                        $social->setSocialId($post->uri);
                        $social->setType($type);
                        $added++;
                    }
                    else
                    {
                        $updated++;
                    }

                    $thumbnail = array_pop($post->pictures->sizes);

                    $social->setUsername($post->user->name);
                    $dateTime = new \DateTime($post->created_time, new \DateTimeZone('UTC'));
                    $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
                    $social->setDatePosted($dateTime);

                    $social->setLink($post->link);
                    $social->setVimeoTitle(mysql_real_escape_string(utf8_encode($post->name)));
                    $social->setVimeoDescription($post->description);
                    $social->setVimeoThumbnailImageUrl($thumbnail->link);

                    $doctrine->getManager()->persist($social);
                }
            }
            else
            {
                $output->writeln('<error>Unable to update Vimeo: ' . $response->getStatusCode() . ' response code given</error>');
            }

            $output->writeln('Vimeo Updated: ' . $added . ' Added, ' . $updated . ' Updated');
            $settings->setSetting('last_updated', date('Y-m-d H:i:s'));
            $doctrine->getManager()->persist($settings);
            $doctrine->getManager()->flush();
        }
        catch (\Exception $e)
        {
            $output->writeln('<error>Unable to update Vimeo: ' . $e->getMessage() . '</error>');
        }
    }
}
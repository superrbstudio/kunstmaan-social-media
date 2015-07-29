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
use MetzWeb\Instagram\Instagram;
use GuzzleHttp\Client;

class UpdateSocialFeedCommand extends ContainerAwareCommand
{
    protected $instagramAccessToken = null;
    protected $instagramUserId = null;
    protected $instagramHashtag = null;

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
        if($this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_access_token', null))
        {
            $this->instagramAccessToken = $this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_access_token');
            $this->instagramUserId      = $this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_user_id');
            $this->instagramHashtag     = $this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_hashtag');
            $this->updateInstagram($input, $output);
        }
    }

    protected function updateInstagram(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $output->writeln('Updating Instagram');

        try
        {
            $instagram = new Client(array('base_uri' => "https://api.instagram.com/v1/"));
            $query = array('access_token' => $this->instagramAccessToken, 'count' => 50);

            if(!$this->instagramHashtag)
            {
                $response = $instagram->get('users/' . $this->instagramUserId . '/media/recent', array('query' => $query));
            }
            else
            {
                $response = $instagram->get('tags/' . htmlentities($this->instagramHashtag) . '/media/recent', array('query' => $query));
            }

            if($response->getStatusCode() == 200)
            {
                $posts = json_decode($response->getBody()->getContents());

                if($posts)
                {
                    $added = 0;
                    $updated = 0;

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


                $doctrine->getManager()->flush();
                $output->writeln('Instagram Updated: ' . $added . ' Added, ' . $updated . ' Updated');
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
}
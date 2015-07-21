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
        if($this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_access_token', null))
        {
            $this->updateInstagram($input, $output);
        }
    }

    protected function updateInstagram(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $output->writeln('Updating Instagram');

        $instagram = new Instagram(array(
            'apiKey'      => $this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_client_id'),
            'apiSecret'   => $this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_client_secret'),
            'apiCallback' => $this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_callback')
        ));

        $instagram->setAccessToken($this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_access_token'));

        try
        {
            if ($this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_hashtag'))
            {
                $posts = $instagram->getTagMedia($this->getContainer()->getParameter('superrb_kunstmaan_social_media.instagram_hashtag'), 50);
            }
            else
            {
                $posts = $instagram->getUserMedia();
            }

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
                    $social->setInstagramCaption($post->caption->text);
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
        catch (\Exception $e)
        {
            $output->writeln('<error>Unable to update Instagram: ' . $e->getMessage() . '</error>');
        }
    }
}
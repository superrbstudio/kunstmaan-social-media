<?php

namespace Superrb\SocialMediaFeedBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Instagram\Instagram;
use Superrb\SocialMediaFeedBundle\Entity\Social;

class UpdateSocialFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('socialMediaFeed:update')
            ->setDescription('Updates the social media feed items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting Social Media Feed Update</info>');
        $doctrine = $this->getContainer()->get('doctrine');

        $this->updateInstagram($input, $output, $doctrine);
    }

    protected function updateInstagram(InputInterface $input, OutputInterface $output, Registry $doctrine)
    {
        $output->writeln('Updating Instagram');
        $instagram = new Instagram($this->getContainer()->getParameter('ssmf_instagram_access_token'));

        try
        {
            if ($this->getContainer()->getParameter('ssmf_instagram_hashtag'))
            {
                $posts = $instagram->searchTags($this->getContainer()->getParameter('ssmf_instagram_hashtag'));
            }
            else
            {
                $user = $instagram->getUser($this->getContainer()->getParameter('ssmf_instagram_user_id'));
                $posts = $user->getMedia();
            }

            if($posts)
            {
                $added = 0;
                $updated = 0;

                foreach($posts as $post)
                {
                    $social = $doctrine->getRepository('SuperrbSocialMediaFeedBundle:Social')->findOneBy(array('socialId' => $post->id, 'type' => 'instagram'));

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
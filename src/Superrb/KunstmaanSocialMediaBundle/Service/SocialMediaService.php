<?php

namespace Superrb\KunstmaanSocialMediaBundle\Service;

use Doctrine\ORM\EntityManager;

class SocialMediaService
{
    protected $em;
    protected $useInstagram = false;
    protected $useTwitter = false;
    protected $useTumblr = false;
    protected $useVimeo = false;

    /**
     * SocialMediaService constructor.
     * @param EntityManager $em
     * @param bool $useInstagram
     * @param bool $useTwitter
     * @param bool $useTumblr
     * @param bool $useVimeo
     */
    public function __construct(EntityManager $em, $useInstagram = false, $useTwitter = false, $useTumblr = false, $useVimeo = false)
    {
        $this->em = $em;
        $this->useInstagram = $useInstagram;
        $this->useTwitter = $useTwitter;
        $this->useTumblr = $useTumblr;
        $this->useVimeo = $useVimeo;
    }

    /**
     * Can we use Instagram
     *
     * @return bool
     */
    public function getUseInstagram() {
        return $this->useInstagram;
    }

    /**
     * Can we use Twitter
     *
     * @return bool
     */
    public function getUseTwitter() {
        return $this->useTwitter;
    }

    /**
     * Can we use Tumblr
     *
     * @return bool
     */
    public function getUseTumblr() {
        return $this->useTumblr;
    }

    /**
     * Can we use Vimeo
     * 
     * @return bool
     */
    public function getUseVimeo() {
        return $this->useVimeo;
    }

    public function getAvailableTypes() {
        $types = array();

        if($this->getUseInstagram()) {
            $types['instagram'] = 'instagram';
        }

        if($this->getUseTwitter()) {
            $types['twitter'] = 'twitter';
        }

//        if($this->getUseTumblr()) {
//            $types['tumblr'] = 'tumblr';
//        }
//
//        if($this->getUseVimeo()) {
//            $types['vimeo'] = 'vimeo';
//        }

        return $types;
    }
}
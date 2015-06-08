<?php

namespace Superrb\SocialMediaFeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Social
 *
 * @ORM\Table(name="ssmf_social")
 * @ORM\Entity(repositoryClass="Superrb\SocialMediaFeedBundle\Repository\SocialRepository")
 */
class Social extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="social_id", type="string", length=255)
     */
    private $socialId;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="date_posted", type="string", length=255)
     */
    private $datePosted;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal")
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal")
     */
    private $longitude;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean")
     */
    private $approved;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_title", type="string", length=255)
     */
    private $blogTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_content", type="string", length=999)
     */
    private $blogContent;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_image_url", type="string", length=255)
     */
    private $blogImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_image_url", type="string", length=255)
     */
    private $instagramImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_caption", type="string", length=999)
     */
    private $instagramCaption;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_content", type="string", length=255)
     */
    private $twitterContent;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_location", type="string", length=255)
     */
    private $twitterLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="vimeo_title", type="string", length=255)
     */
    private $vimeoTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="vimeo_description", type="string", length=999)
     */
    private $vimeoDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="vimeo_thumbnail_image_url", type="string", length=255)
     */
    private $vimeoThumbnailImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_media_type", type="string", length=255)
     */
    private $tumblrMediaType;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_title", type="string", length=255)
     */
    private $tumblrTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_body_text", type="string", length=999)
     */
    private $tumblrBodyText;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_image_url", type="string", length=255)
     */
    private $tumblrImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_caption", type="string", length=999)
     */
    private $tumblrCaption;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_video_url", type="string", length=255)
     */
    private $tumblrVideoUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_video_thumbnail_image_url", type="string", length=255)
     */
    private $tumblrVideoThumbnailImageUrl;


    /**
     * Set socialId
     *
     * @param string $socialId
     *
     * @return Social
     */
    public function setSocialId($socialId)
    {
        $this->socialId = $socialId;

        return $this;
    }

    /**
     * Get socialId
     *
     * @return string
     */
    public function getSocialId()
    {
        return $this->socialId;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Social
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Social
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Social
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set datePosted
     *
     * @param string $datePosted
     *
     * @return Social
     */
    public function setDatePosted($datePosted)
    {
        $this->datePosted = $datePosted;

        return $this;
    }

    /**
     * Get datePosted
     *
     * @return string
     */
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Social
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Social
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Social
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     *
     * @return Social
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set blogTitle
     *
     * @param string $blogTitle
     *
     * @return Social
     */
    public function setBlogTitle($blogTitle)
    {
        $this->blogTitle = $blogTitle;

        return $this;
    }

    /**
     * Get blogTitle
     *
     * @return string
     */
    public function getBlogTitle()
    {
        return $this->blogTitle;
    }

    /**
     * Set blogContent
     *
     * @param string $blogContent
     *
     * @return Social
     */
    public function setBlogContent($blogContent)
    {
        $this->blogContent = $blogContent;

        return $this;
    }

    /**
     * Get blogContent
     *
     * @return string
     */
    public function getBlogContent()
    {
        return $this->blogContent;
    }

    /**
     * Set blogImageUrl
     *
     * @param string $blogImageUrl
     *
     * @return Social
     */
    public function setBlogImageUrl($blogImageUrl)
    {
        $this->blogImageUrl = $blogImageUrl;

        return $this;
    }

    /**
     * Get blogImageUrl
     *
     * @return string
     */
    public function getBlogImageUrl()
    {
        return $this->blogImageUrl;
    }

    /**
     * Set instagramImageUrl
     *
     * @param string $instagramImageUrl
     *
     * @return Social
     */
    public function setInstagramImageUrl($instagramImageUrl)
    {
        $this->instagramImageUrl = $instagramImageUrl;

        return $this;
    }

    /**
     * Get instagramImageUrl
     *
     * @return string
     */
    public function getInstagramImageUrl()
    {
        return $this->instagramImageUrl;
    }

    /**
     * Set instagramCaption
     *
     * @param string $instagramCaption
     *
     * @return Social
     */
    public function setInstagramCaption($instagramCaption)
    {
        $this->instagramCaption = $instagramCaption;

        return $this;
    }

    /**
     * Get instagramCaption
     *
     * @return string
     */
    public function getInstagramCaption()
    {
        return $this->instagramCaption;
    }

    /**
     * Set twitterContent
     *
     * @param string $twitterContent
     *
     * @return Social
     */
    public function setTwitterContent($twitterContent)
    {
        $this->twitterContent = $twitterContent;

        return $this;
    }

    /**
     * Get twitterContent
     *
     * @return string
     */
    public function getTwitterContent()
    {
        return $this->twitterContent;
    }

    /**
     * Set twitterLocation
     *
     * @param string $twitterLocation
     *
     * @return Social
     */
    public function setTwitterLocation($twitterLocation)
    {
        $this->twitterLocation = $twitterLocation;

        return $this;
    }

    /**
     * Get twitterLocation
     *
     * @return string
     */
    public function getTwitterLocation()
    {
        return $this->twitterLocation;
    }

    /**
     * Set vimeoTitle
     *
     * @param string $vimeoTitle
     *
     * @return Social
     */
    public function setVimeoTitle($vimeoTitle)
    {
        $this->vimeoTitle = $vimeoTitle;

        return $this;
    }

    /**
     * Get vimeoTitle
     *
     * @return string
     */
    public function getVimeoTitle()
    {
        return $this->vimeoTitle;
    }

    /**
     * Set vimeoDescription
     *
     * @param string $vimeoDescription
     *
     * @return Social
     */
    public function setVimeoDescription($vimeoDescription)
    {
        $this->vimeoDescription = $vimeoDescription;

        return $this;
    }

    /**
     * Get vimeoDescription
     *
     * @return string
     */
    public function getVimeoDescription()
    {
        return $this->vimeoDescription;
    }

    /**
     * Set vimeoThumbnailImageUrl
     *
     * @param string $vimeoThumbnailImageUrl
     *
     * @return Social
     */
    public function setVimeoThumbnailImageUrl($vimeoThumbnailImageUrl)
    {
        $this->vimeoThumbnailImageUrl = $vimeoThumbnailImageUrl;

        return $this;
    }

    /**
     * Get vimeoThumbnailImageUrl
     *
     * @return string
     */
    public function getVimeoThumbnailImageUrl()
    {
        return $this->vimeoThumbnailImageUrl;
    }

    /**
     * Set tumblrMediaType
     *
     * @param string $tumblrMediaType
     *
     * @return Social
     */
    public function setTumblrMediaType($tumblrMediaType)
    {
        $this->tumblrMediaType = $tumblrMediaType;

        return $this;
    }

    /**
     * Get tumblrMediaType
     *
     * @return string
     */
    public function getTumblrMediaType()
    {
        return $this->tumblrMediaType;
    }

    /**
     * Set tumblrTitle
     *
     * @param string $tumblrTitle
     *
     * @return Social
     */
    public function setTumblrTitle($tumblrTitle)
    {
        $this->tumblrTitle = $tumblrTitle;

        return $this;
    }

    /**
     * Get tumblrTitle
     *
     * @return string
     */
    public function getTumblrTitle()
    {
        return $this->tumblrTitle;
    }

    /**
     * Set tumblrBodyText
     *
     * @param string $tumblrBodyText
     *
     * @return Social
     */
    public function setTumblrBodyText($tumblrBodyText)
    {
        $this->tumblrBodyText = $tumblrBodyText;

        return $this;
    }

    /**
     * Get tumblrBodyText
     *
     * @return string
     */
    public function getTumblrBodyText()
    {
        return $this->tumblrBodyText;
    }

    /**
     * Set tumblrImageUrl
     *
     * @param string $tumblrImageUrl
     *
     * @return Social
     */
    public function setTumblrImageUrl($tumblrImageUrl)
    {
        $this->tumblrImageUrl = $tumblrImageUrl;

        return $this;
    }

    /**
     * Get tumblrImageUrl
     *
     * @return string
     */
    public function getTumblrImageUrl()
    {
        return $this->tumblrImageUrl;
    }

    /**
     * Set tumblrCaption
     *
     * @param string $tumblrCaption
     *
     * @return Social
     */
    public function setTumblrCaption($tumblrCaption)
    {
        $this->tumblrCaption = $tumblrCaption;

        return $this;
    }

    /**
     * Get tumblrCaption
     *
     * @return string
     */
    public function getTumblrCaption()
    {
        return $this->tumblrCaption;
    }

    /**
     * Set tumblrVideoUrl
     *
     * @param string $tumblrVideoUrl
     *
     * @return Social
     */
    public function setTumblrVideoUrl($tumblrVideoUrl)
    {
        $this->tumblrVideoUrl = $tumblrVideoUrl;

        return $this;
    }

    /**
     * Get tumblrVideoUrl
     *
     * @return string
     */
    public function getTumblrVideoUrl()
    {
        return $this->tumblrVideoUrl;
    }

    /**
     * Set tumblrVideoThumbnailImageUrl
     *
     * @param string $tumblrVideoThumbnailImageUrl
     *
     * @return Social
     */
    public function setTumblrVideoThumbnailImageUrl($tumblrVideoThumbnailImageUrl)
    {
        $this->tumblrVideoThumbnailImageUrl = $tumblrVideoThumbnailImageUrl;

        return $this;
    }

    /**
     * Get tumblrVideoThumbnailImageUrl
     *
     * @return string
     */
    public function getTumblrVideoThumbnailImageUrl()
    {
        return $this->tumblrVideoThumbnailImageUrl;
    }
}


<?php

namespace Superrb\KunstmaanSocialMediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Kunstmaan\MediaBundle\Entity\Media;
use Kunstmaan\AdminBundle\Entity\AbstractEntity;
use Symfony\Component\Form\FormInterface;

/**
 * Social
 *
 * @ORM\Table(name="sksmb_social", indexes={@ORM\Index(name="social_id_type_idx", columns={"social_id", "type"}), @ORM\Index(name="type_idx", columns={"type"}), @ORM\Index(name="approved_idx", columns={"approved"}), @ORM\Index(name="approved_type_idx", columns={"approved", "type"})})
 * @ORM\Entity(repositoryClass="Superrb\KunstmaanSocialMediaBundle\Repository\SocialRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Social extends AbstractEntity
{
    static $availableTypes = array(
        'instagram' => 'instagram',
        //'tumblr' => 'tumblr',
        'twitter' => 'twitter',
        //'vimeo' => 'vimeo',
    );

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="social_id", type="string", length=255, nullable=true)
     */
    private $socialId = 'custom';

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date_posted", type="datetime", length=255)
     */
    private $datePosted;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="float", nullable=true, precision=15, scale=15)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="float", nullable=true, precision=15, scale=15)
     */
    private $longitude;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean")
     */
    private $approved = false;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_title", type="string", length=255, nullable=true)
     */
    private $blogTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_content", type="string", length=999, nullable=true)
     */
    private $blogContent;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_image_url", type="string", length=255, nullable=true)
     */
    private $blogImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_image_url", type="string", length=255, nullable=true)
     */
    private $instagramImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_video_url", type="string", length=255, nullable=true)
     */
    private $instagramVideoUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_caption", type="text", nullable=true)
     */
    private $instagramCaption;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_content", type="text", nullable=true)
     */
    private $twitterContent;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_image_url", type="string", length=255, nullable=true)
     */
    private $twitterImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_video_url", type="string", length=255, nullable=true)
     */
    private $twitterVideoUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_location", type="string", length=255, nullable=true)
     */
    private $twitterLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="vimeo_title", type="string", length=255, nullable=true)
     */
    private $vimeoTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="vimeo_description", type="string", length=999, nullable=true)
     */
    private $vimeoDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="vimeo_thumbnail_image_url", type="string", length=255, nullable=true)
     */
    private $vimeoThumbnailImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_media_type", type="string", length=255, nullable=true)
     */
    private $tumblrMediaType;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_title", type="string", length=255, nullable=true)
     */
    private $tumblrTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_body_text", type="text", nullable=true)
     */
    private $tumblrBodyText;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_image_url", type="string", length=255, nullable=true)
     */
    private $tumblrImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_caption", type="text", nullable=true)
     */
    private $tumblrCaption;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_video_type", type="string", length=255, nullable=true)
     */
    private $tumblrVideoType;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_video_embed_code", type="text", nullable=true)
     */
    private $tumblrVideoEmbedCode;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_video_url", type="string", length=255, nullable=true)
     */
    private $tumblrVideoUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="tumblr_video_thumbnail_image_url", type="string", length=255, nullable=true)
     */
    private $tumblrVideoThumbnailImageUrl;

    /**
     * @var \Kunstmaan\MediaBundle\Entity\Media
     *
     * @ORM\ManyToOne(targetEntity="Kunstmaan\MediaBundle\Entity\Media")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="custom_image_id", referencedColumnName="id")
     * })
     */
    private $customImage;

    /**
     * Determine validation groups for custom posts
     *
     * @param FormInterface $form
     * @return array
     */
    static function determineCustomPostValidationGroups(FormInterface $form) {
        $data = $form->getData();
        $groups = array('default');

        switch($data->getType()) {
            case 'instagram':
                $groups[] = 'instagram';
                break;

            case 'tumblr':
                $groups[] = 'tumblr';
                break;

            case 'twitter':
                $groups[] = 'twitter';
                break;

            case 'vimeo':
                $groups[] = 'vimeo';
                break;
        }

        return $groups;
    }


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
     * Get the image for this social feed
     *
     * @return null|string
     */
    public function getImage()
    {
        // Return the Custom Image if there is one
        if($this->getCustomImage()) {
            return $this->getCustomImage()->getUrl();
        }

        switch($this->getType())
        {
            case 'instagram':
                return $this->getInstagramImageUrl();

            case 'tumblr':
                switch($this->getTumblrMediaType())
                {
                    case 'photo':
                        return $this->getTumblrImageUrl();
                    case 'video':
                        return $this->getTumblrVideoThumbnailImageUrl();
                }

            case 'twitter':
                return $this->getTwitterImageUrl();

            case 'vimeo':
                return $this->getVimeoThumbnailImageUrl();
        }

        return null;
    }

    /**
     * Get the text for this social feed
     *
     * @return null|string
     */
    public function getText()
    {
        switch($this->getType())
        {
            case 'instagram':
                return $this->getInstagramCaption();

            case 'tumblr':
                switch($this->getTumblrMediaType())
                {
                    case 'text':
                        return $this->getTumblrTitle();
                    case 'photo':
                        return $this->getTumblrCaption();
                    case 'video':
                        return $this->getTumblrCaption();
                }

            case 'twitter':
                return $this->getTwitterContent();

            case 'vimeo':
                return $this->getVimeoTitle();
        }

        return null;
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
     * Set instagramVideoUrl
     *
     * @param string $instagramVideoUrl
     *
     * @return Social
     */
    public function setInstagramVideoUrl($instagramVideoUrl)
    {
        $this->instagramVideoUrl = $instagramVideoUrl;

        return $this;
    }

    /**
     * Get instagramVideoUrl
     *
     * @return string
     */
    public function getInstagramVideoUrl()
    {
        return $this->instagramVideoUrl;
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
     * Set tumblrVideoType
     *
     * @param string $tumblrVideoType
     *
     * @return Social
     */
    public function setTumblrVideoType($tumblrVideoType)
    {
        $this->tumblrVideoType = $tumblrVideoType;

        return $this;
    }

    /**
     * Get tumblrVideoType
     *
     * @return string
     */
    public function getTumblrVideoType()
    {
        return $this->tumblrVideoType;
    }

    /**
     * Set tumblrVideoEmbedCode
     *
     * @param string $tumblrVideoEmbedCode
     *
     * @return Social
     */
    public function setTumblrVideoEmbedCode($tumblrVideoEmbedCode)
    {
        $this->tumblrVideoEmbedCode = $tumblrVideoEmbedCode;

        return $this;
    }

    /**
     * Get tumblrVideoEmbedCode
     *
     * @return string
     */
    public function getTumblrVideoEmbedCode()
    {
        return $this->tumblrVideoEmbedCode;
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

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Social
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Social
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set datePosted
     *
     * @param \DateTime $datePosted
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
     * @return \DateTime
     */
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * Set twitterImageUrl
     *
     * @param string $twitterImageUrl
     *
     * @return Social
     */
    public function setTwitterImageUrl($twitterImageUrl)
    {
        $this->twitterImageUrl = $twitterImageUrl;

        return $this;
    }

    /**
     * Get twitterImageUrl
     *
     * @return string
     */
    public function getTwitterImageUrl()
    {
        return $this->twitterImageUrl;
    }

    /**
     * Set twitterVideoUrl
     *
     * @param string $twitterVideoUrl
     *
     * @return Social
     */
    public function setTwitterVideoUrl($twitterVideoUrl)
    {
        $this->twitterVideoUrl = $twitterVideoUrl;

        return $this;
    }

    /**
     * Get twitterVideoUrl
     *
     * @return string
     */
    public function getTwitterVideoUrl()
    {
        return $this->twitterVideoUrl;
    }

    /**
     * Set customImage
     *
     * @param Media|null $customImage
     * @return $this
     */
    public function setCustomImage(Media $customImage = null)
    {
        $this->customImage = $customImage;

        return $this;
    }

    /**
     * Get customImage
     *
     * @return Media
     */
    public function getCustomImage()
    {
        return $this->customImage;
    }
}

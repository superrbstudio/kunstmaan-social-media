<?php

namespace Superrb\SocialMediaFeedBundle\AdminList;

use Doctrine\ORM\EntityManager;

use Superrb\SocialMediaFeedBundle\Form\SocialAdminType;
use Kunstmaan\AdminListBundle\AdminList\FilterType\ORM;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;

/**
 * The admin list configurator for Social
 */
class SocialAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator
{
    /**
     * @param EntityManager $em        The entity manager
     * @param AclHelper     $aclHelper The acl helper
     */
    public function __construct(EntityManager $em, AclHelper $aclHelper = null)
    {
        parent::__construct($em, $aclHelper);
        $this->setAdminType(new SocialAdminType());
    }

    /**
     * Configure the visible columns
     */
    public function buildFields()
    {
        $this->addField('socialId', 'Social id', true);
        $this->addField('username', 'Username', true);
        $this->addField('slug', 'Slug', true);
        $this->addField('type', 'Type', true);
        $this->addField('datePosted', 'Date posted', true);
        $this->addField('link', 'Link', true);
        //$this->addField('latitude', 'Latitude', true);
        //$this->addField('longitude', 'Longitude', true);
        $this->addField('approved', 'Approved', true);
        /*$this->addField('blogTitle', 'Blog title', true);
        $this->addField('blogContent', 'Blog content', true);
        $this->addField('blogImageUrl', 'Blog image url', true);
        $this->addField('instagramImageUrl', 'Instagram image url', true);
        $this->addField('instagramCaption', 'Instagram caption', true);
        $this->addField('twitterContent', 'Twitter content', true);
        $this->addField('twitterLocation', 'Twitter location', true);
        $this->addField('vimeoTitle', 'Vimeo title', true);
        $this->addField('vimeoDescription', 'Vimeo description', true);
        $this->addField('vimeoThumbnailImageUrl', 'Vimeo thumbnail image url', true);
        $this->addField('tumblrMediaType', 'Tumblr media type', true);
        $this->addField('tumblrTitle', 'Tumblr title', true);
        $this->addField('tumblrBodyText', 'Tumblr body text', true);
        $this->addField('tumblrImageUrl', 'Tumblr image url', true);
        $this->addField('tumblrCaption', 'Tumblr caption', true);
        $this->addField('tumblrVideoUrl', 'Tumblr video url', true);
        $this->addField('tumblrVideoThumbnailImageUrl', 'Tumblr video thumbnail image url', true);*/
    }

    /**
     * Build filters for admin list
     */
    public function buildFilters()
    {
        $this->addFilter('socialId', new ORM\StringFilterType('socialId'), 'Social id');
        $this->addFilter('username', new ORM\StringFilterType('username'), 'Username');
        $this->addFilter('slug', new ORM\StringFilterType('slug'), 'Slug');
        $this->addFilter('type', new ORM\StringFilterType('type'), 'Type');
        $this->addFilter('datePosted', new ORM\StringFilterType('datePosted'), 'Date posted');
        $this->addFilter('link', new ORM\StringFilterType('link'), 'Link');
        $this->addFilter('latitude', new ORM\NumberFilterType('latitude'), 'Latitude');
        $this->addFilter('longitude', new ORM\NumberFilterType('longitude'), 'Longitude');
        $this->addFilter('approved', new ORM\BooleanFilterType('approved'), 'Approved');
        $this->addFilter('blogTitle', new ORM\StringFilterType('blogTitle'), 'Blog title');
        $this->addFilter('blogContent', new ORM\StringFilterType('blogContent'), 'Blog content');
        $this->addFilter('blogImageUrl', new ORM\StringFilterType('blogImageUrl'), 'Blog image url');
        $this->addFilter('instagramImageUrl', new ORM\StringFilterType('instagramImageUrl'), 'Instagram image url');
        $this->addFilter('instagramCaption', new ORM\StringFilterType('instagramCaption'), 'Instagram caption');
        $this->addFilter('twitterContent', new ORM\StringFilterType('twitterContent'), 'Twitter content');
        $this->addFilter('twitterLocation', new ORM\StringFilterType('twitterLocation'), 'Twitter location');
        $this->addFilter('vimeoTitle', new ORM\StringFilterType('vimeoTitle'), 'Vimeo title');
        $this->addFilter('vimeoDescription', new ORM\StringFilterType('vimeoDescription'), 'Vimeo description');
        $this->addFilter('vimeoThumbnailImageUrl', new ORM\StringFilterType('vimeoThumbnailImageUrl'), 'Vimeo thumbnail image url');
        $this->addFilter('tumblrMediaType', new ORM\StringFilterType('tumblrMediaType'), 'Tumblr media type');
        $this->addFilter('tumblrTitle', new ORM\StringFilterType('tumblrTitle'), 'Tumblr title');
        $this->addFilter('tumblrBodyText', new ORM\StringFilterType('tumblrBodyText'), 'Tumblr body text');
        $this->addFilter('tumblrImageUrl', new ORM\StringFilterType('tumblrImageUrl'), 'Tumblr image url');
        $this->addFilter('tumblrCaption', new ORM\StringFilterType('tumblrCaption'), 'Tumblr caption');
        $this->addFilter('tumblrVideoUrl', new ORM\StringFilterType('tumblrVideoUrl'), 'Tumblr video url');
        $this->addFilter('tumblrVideoThumbnailImageUrl', new ORM\StringFilterType('tumblrVideoThumbnailImageUrl'), 'Tumblr video thumbnail image url');
    }

    /**
     * Get bundle name
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'SuperrbSocialMediaFeedBundle';
    }

    /**
     * Get entity name
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'Social';
    }
}

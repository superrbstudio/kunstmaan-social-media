<?php

namespace Superrb\KunstmaanSocialMediaBundle\AdminList;

use Doctrine\ORM\EntityManager;

use Superrb\KunstmaanSocialMediaBundle\Form\SocialAdminType;
use Kunstmaan\AdminListBundle\AdminList\FilterType\ORM;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;

/**
 * The admin list configurator for Social
 */
class SocialAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator
{
    /**
     * @var string
     */
    private $listTemplate = 'SuperrbKunstmaanSocialMediaBundle:Default:list.html.twig';

    /**
     * @var string
     */
    private $editTemplate = 'SuperrbKunstmaanSocialMediaBundle:Default:edit.html.twig';

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
        $this->addField('username', 'Username', true);
        $this->addField('type', 'Type', true);
        $this->addField('datePosted', 'Date posted', true, 'SuperrbKunstmaanSocialMediaBundle:AdminList:datePosted.html.twig');
        $this->addField('link', 'Link', false, 'SuperrbKunstmaanSocialMediaBundle:AdminList:link.html.twig');
        $this->addField('approved', 'Approved', true);
        $this->addField('image', 'Image', false, 'SuperrbKunstmaanSocialMediaBundle:AdminList:image.html.twig');
        $this->addField('text', 'Text', false);
    }

    /**
     * Build filters for admin list
     */
    public function buildFilters()
    {
        $this->addFilter('username', new ORM\StringFilterType('username'), 'Username');
        $this->addFilter('type', new ORM\StringFilterType('type'), 'Type');
        $this->addFilter('datePosted', new ORM\StringFilterType('datePosted'), 'Date posted');
        $this->addFilter('link', new ORM\StringFilterType('link'), 'Link');
        $this->addFilter('approved', new ORM\BooleanFilterType('approved'), 'Approved');
    }

    /**
     * Get bundle name
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'SuperrbKunstmaanSocialMediaBundle';
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

    /**
     * @return int
     */
    public function getLimit()
    {
        return 50;
    }

    /**
     * Configure if it's possible to add new items
     *
     * @return bool
     */
    public function canAdd()
    {
        return false;
    }

    /**
     * Configure if it's possible to delete the given $item
     *
     * @param object|array $item
     *
     * @return bool
     */
    public function canDelete($item)
    {
        return false;
    }

    /**
     * @return string
     */
    public function getListTemplate()
    {
        return $this->listTemplate;
    }

    /**
     * @return string
     */
    public function getEditTemplate()
    {
        return $this->editTemplate;
    }
}

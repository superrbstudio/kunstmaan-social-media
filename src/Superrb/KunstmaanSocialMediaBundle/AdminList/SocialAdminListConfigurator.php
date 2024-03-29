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
     * @var Query
     */
    private $query = null;

    /**
     * @var PermissionDefinition
     */
    private $permissionDef = null;

    /**
     * @var AclHelper
     */
    protected $aclHelper = null;

    /**
     * @var string
     */
    private $listTemplate = '@SuperrbKunstmaanSocialMedia/Default/list.html.twig';

    /**
     * @var string
     */
    private $addTemplate = '@SuperrbKunstmaanSocialMedia/Default/addCustomPost.html.twig';

    /**
     * @param EntityManager $em        The entity manager
     * @param AclHelper     $aclHelper The acl helper
     */
    public function __construct(EntityManager $em, AclHelper $aclHelper = null)
    {
        parent::__construct($em, $aclHelper);
    }

    /**
     * Configure the visible columns
     */
    public function buildFields()
    {
        $this->addField('username', 'Username', true);
        $this->addField('type', 'Type', true);
        $this->addField('datePosted', 'Date', true, '@SuperrbKunstmaanSocialMedia/AdminList/datePosted.html.twig');
        $this->addField('link', 'Link', false, '@SuperrbKunstmaanSocialMedia/AdminList/link.html.twig');
        $this->addField('image', 'Image', false, '@SuperrbKunstmaanSocialMedia/AdminList/image.html.twig');
        $this->addField('text', 'Text', false, '@SuperrbKunstmaanSocialMedia/AdminList/text.html.twig');
        $this->addField('approved', 'Approved', true, '@SuperrbKunstmaanSocialMedia/AdminList/approved.html.twig');
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
        return true;
    }

    /**
     * @param object|array $item
     *
     * @return bool
     */
    public function canEdit($item)
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
    public function getAddTemplate()
    {
        return $this->addTemplate;
    }

    /**
     * @return Query|null
     */
    public function getQuery()
    {
        if (is_null($this->query)) {
            $queryBuilder = $this->getQueryBuilder();
            $this->adaptQueryBuilder($queryBuilder);

            // Apply filters
            $filters = $this->getFilterBuilder()->getCurrentFilters();
            /* @var Filter $filter */
            foreach ($filters as $filter) {
                /* @var AbstractORMFilterType $type */
                $type = $filter->getType();
                $type->setQueryBuilder($queryBuilder);
                $filter->apply();
            }

            // Apply sorting
            if (!empty($this->orderBy)) {
                $orderBy = $this->orderBy;
                if (!strpos($orderBy, '.')) {
                    $orderBy = 'b.' . $orderBy;
                }
                $queryBuilder->orderBy($orderBy, ($this->orderDirection == 'DESC' ? 'DESC' : 'ASC'));
            }
            else
            {
                $queryBuilder->orderBy('b.datePosted', ($this->orderDirection == 'ASC' ? 'ASC' : 'DESC'));
            }

            // Apply ACL restrictions (if applicable)
            if (!is_null($this->permissionDef) && !is_null($this->aclHelper)) {
                $this->query = $this->aclHelper->apply($queryBuilder, $this->permissionDef);
            } else {
                $this->query = $queryBuilder->getQuery();
            }
        }

        return $this->query;
    }
}

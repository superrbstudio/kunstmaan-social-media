<?php
namespace Superrb\KunstmaanSocialMediaBundle\Helper\Menu;

use Kunstmaan\AdminBundle\Helper\Menu\MenuAdaptorInterface;
use Kunstmaan\AdminBundle\Helper\Menu\MenuBuilder;
use Kunstmaan\AdminBundle\Helper\Menu\MenuItem;
use Kunstmaan\AdminBundle\Helper\Menu\TopMenuItem;
use Symfony\Component\HttpFoundation\Request;

class ModulesMenuAdaptor implements MenuAdaptorInterface
{

    /**
     * {@inheritDoc}
     */
    public function adaptChildren(MenuBuilder $menu, array &$children, MenuItem $parent = null, Request $request = null)
    {
        if (is_null($parent)) {
            $menuItem = new TopMenuItem($menu);
            $menuItem
                ->setRoute('superrbkunstmaansocialmediabundle_admin_social_false')
                ->setLabel('Social Media')
                ->setUniqueId('Social Media')
                ->setFolder(true)
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
            }
            $children[] = $menuItem;
        }

        if (!is_null($parent) && 'superrbkunstmaansocialmediabundle_admin_social_false' == $parent->getRoute()) {
            // Posts
            $menuItem = new TopMenuItem($menu);
            $menuItem
                ->setRoute('superrbkunstmaansocialmediabundle_admin_social')
                ->setLabel('Social Media Posts')
                ->setUniqueId('Social Media Posts')
                ->setParent($parent);
            if ($request->attributes->get('_route') == 'superrbkunstmaansocialmediabundle_admin_social_false') {
                $menuItem->setActive(true);
                $parent->setActive(true);
            }
            $children[] = $menuItem;

            // Instagram Settings
            $menuItem = new TopMenuItem($menu);
            $menuItem
                ->setRoute('superrbkunstmaansocialmediabundle_admin_social_authenticate_instagram')
                ->setLabel('Instagram Settings')
                ->setUniqueId('Instagram Settings')
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
                $parent->setActive(true);
            }
            $children[] = $menuItem;

            // Tumblr Settings
            $menuItem = new TopMenuItem($menu);
            $menuItem
                ->setRoute('superrbkunstmaansocialmediabundle_admin_social_authenticate_tumblr')
                ->setLabel('Tumblr Settings')
                ->setUniqueId('Tumblr Settings')
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
                $parent->setActive(true);
            }
            $children[] = $menuItem;

            // Twitter Settings
            $menuItem = new TopMenuItem($menu);
            $menuItem
                ->setRoute('superrbkunstmaansocialmediabundle_admin_social_authenticate_twitter')
                ->setLabel('Twitter Settings')
                ->setUniqueId('Twitter Settings')
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
                $parent->setActive(true);
            }
            $children[] = $menuItem;

            // Vimeo Settings
            $menuItem = new TopMenuItem($menu);
            $menuItem
                ->setRoute('superrbkunstmaansocialmediabundle_admin_social_authenticate_vimeo')
                ->setLabel('Vimeo Settings')
                ->setUniqueId('Vimeo Settings')
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
                $parent->setActive(true);
            }
            $children[] = $menuItem;

            // Vimeo Settings
            $menuItem = new TopMenuItem($menu);
            $menuItem
                ->setRoute('superrbkunstmaansocialmediabundle_admin_social_update')
                ->setLabel('Update Feed')
                ->setUniqueId('Update Feed')
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
                $parent->setActive(true);
            }
            $children[] = $menuItem;
        }
    }
}

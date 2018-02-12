<?php
namespace Superrb\KunstmaanSocialMediaBundle\Helper\Menu;

use Kunstmaan\AdminBundle\Helper\Menu\MenuAdaptorInterface;
use Kunstmaan\AdminBundle\Helper\Menu\MenuBuilder;
use Kunstmaan\AdminBundle\Helper\Menu\MenuItem;
use Kunstmaan\AdminBundle\Helper\Menu\TopMenuItem;
use Superrb\KunstmaanSocialMediaBundle\Service\SocialMediaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ModulesMenuAdaptor implements MenuAdaptorInterface
{
    protected $socialMediaService;
    protected $authorizationChecker;

    public function __construct(SocialMediaService $socialMediaService, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->socialMediaService = $socialMediaService;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritDoc}
     */
    public function adaptChildren(MenuBuilder $menu, array &$children, MenuItem $parent = null, Request $request = null)
    {
        if($this->authorizationChecker->isGranted('ROLE_SOCIAL_USER') or $this->authorizationChecker->isGranted('ROLE_SOCIAL_ADMIN')) {

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
                // Social Media Posts
                $menuItem = new TopMenuItem($menu);
                $menuItem
                    ->setRoute('superrbkunstmaansocialmediabundle_admin_social')
                    ->setLabel('Social Media Posts')
                    ->setUniqueId('Social Media Posts')
                    ->setParent($parent);
                if ($request->attributes->get('_route') == 'superrbkunstmaansocialmediabundle_admin_social') {
                    $menuItem->setActive(true);
                    $parent->setActive(true);
                }
                $children[] = $menuItem;

                if($this->authorizationChecker->isGranted('ROLE_SOCIAL_ADMIN')) {
                    if ($this->socialMediaService->getUseInstagram()) {
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
                    }

                    if ($this->socialMediaService->getUseTwitter()) {
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
                    }

                    if ($this->socialMediaService->getUseTumblr()) {
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
                    }

                    if ($this->socialMediaService->getUseVimeo()) {
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
                    }

                    // Update Feed
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
    }
}

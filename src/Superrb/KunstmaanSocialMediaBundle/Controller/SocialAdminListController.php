<?php

namespace Superrb\KunstmaanSocialMediaBundle\Controller;

use Superrb\KunstmaanSocialMediaBundle\AdminList\SocialAdminListConfigurator;
use Kunstmaan\AdminListBundle\Controller\AdminListController;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AdminListConfiguratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Superrb\KunstmaanSocialMediaBundle\Form\InstagramAuthenticationType;
use Superrb\KunstmaanSocialMediaBundle\Form\TwitterAuthenticationType;
use Superrb\KunstmaanSocialMediaBundle\Form\TumblrAuthenticationType;
use Superrb\KunstmaanSocialMediaBundle\Form\VimeoAuthenticationType;
use Superrb\KunstmaanSocialMediaBundle\Entity\Setting;
use GuzzleHttp\Client;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * The admin list controller for Social
 */
class SocialAdminListController extends AdminListController
{
    /**
     * @var AdminListConfiguratorInterface
     */
    private $configurator;

    /**
     * @return AdminListConfiguratorInterface
     */
    public function getAdminListConfigurator()
    {
        if (!isset($this->configurator)) {
            $this->configurator = new SocialAdminListConfigurator($this->getEntityManager());
        }

        return $this->configurator;
    }

    /**
     * The index action
     *
     * @Route("/", name="superrbkunstmaansocialmediabundle_admin_social")
     */
    public function indexAction(Request $request)
    {
        return parent::doIndexAction($this->getAdminListConfigurator(), $request);
    }

    /**
     * The add action
     *
     * @Route("/add", name="superrbkunstmaansocialmediabundle_admin_social_add")
     * @Method({"GET", "POST"})
     * @return array
     */
    public function addAction(Request $request)
    {
        return parent::doAddAction($this->getAdminListConfigurator(), null, $request);
    }

    /**
     * The edit action
     *
     * @param int $id
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="superrbkunstmaansocialmediabundle_admin_social_edit")
     * @Method({"GET", "POST"})
     *
     * @return array
     */
    public function editAction(Request $request, $id)
    {
        return parent::doEditAction($this->getAdminListConfigurator(), $id, $request);
    }

    /**
     * The delete action
     *
     * @param int $id
     *
     * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="superrbkunstmaansocialmediabundle_admin_social_delete")
     * @Method({"GET", "POST"})
     *
     * @return array
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::doDeleteAction($this->getAdminListConfigurator(), $id, $request);
    }

    /**
     * The export action
     *
     * @param string $_format
     *
     * @Route("/export.{_format}", requirements={"_format" = "csv|xlsx"}, name="superrbkunstmaansocialmediabundle_admin_social_export")
     * @Method({"GET", "POST"})
     * @return array
     */
    public function exportAction(Request $request, $_format)
    {
        return parent::doExportAction($this->getAdminListConfigurator(), $_format, $request);
    }

    /**
     * The authenticate Instagram action
     *
     * @Route("/authenticate-instagram", name="superrbkunstmaansocialmediabundle_admin_social_authenticate_instagram")
     */
    public function authenticateInstagramAction(Request $request)
    {
        $settings = $this->getDoctrine()->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->instagram();
        $redirectUrl = $this->generateUrl('superrbkunstmaansocialmediabundle_admin_social_authenticate_instagram', array(), true);

        $formData = array(
            'active' => $settings->getSetting('active')
        );

        if($settings->getSetting('client_id') and $settings->getSetting('client_secret'))
        {
            $formData['client_id'] = $settings->getSetting('client_id');
            $formData['client_secret'] = $settings->getSetting('client_secret');

            if($settings->getSetting('hashtag'))
            {
                $formData['hashtag'] = $settings->getSetting('hashtag');
            }

            $form = $this->createForm(new InstagramAuthenticationType(), $formData);
        }
        else
        {
            $form = $this->createForm(new InstagramAuthenticationType());
        }

        // we have returned from instagram and have a code.
        if($request->query->get('code', null) and $settings instanceof Setting)
        {
            $code = $request->query->get('code');
            $instagramClient = new Client(array('base_uri' => "https://api.instagram.com"));
            $data = "client_id=" . $settings->getSetting('client_id')
                . "&client_secret=" . $settings->getSetting('client_secret')
                . "&grant_type=authorization_code"
                . "&redirect_uri=" . $settings->getSetting('redirect_url')
                . "&code=" . $code;

            try
            {
                $response = $instagramClient->post('/oauth/access_token', array('body' => $data));

                if($response->getStatusCode() == 200)
                {
                    $details = json_decode($response->getBody()->getContents());

                    $settings->setSetting('access_token', $details->access_token);
                    $settings->setSetting('user_id', $details->user->id);
                    $settings->setSetting('user_name', $details->user->username);
                    $settings->setSetting('user_fullname', $details->user->full_name);

                    $this->getDoctrine()->getManager()->persist($settings);
                    $this->getDoctrine()->getManager()->flush();
                }

            }
            catch (\Exception $e)
            {
                var_dump('<error>Unable to update Instagram: ' . $e->getMessage() . '</error>');
            }
        }

        // we have returned from instagram with an error

        // form has been submitted validate and redirect to instagram
        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $settings->setSetting('active', $form['active']->getData());
                $settings->setSetting('client_id', $form['client_id']->getData());
                $settings->setSetting('client_secret', $form['client_secret']->getData());
                $settings->setSetting('hashtag', $form['hashtag']->getData());
                $settings->setSetting('redirect_url', $redirectUrl);

                $this->getDoctrine()->getManager()->persist($settings);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect('https://api.instagram.com/oauth/authorize/?client_id=' . $settings->getSetting('client_id') . '&redirect_uri=' . $redirectUrl . '&response_type=code');
            }
        }

        return $this->render('SuperrbKunstmaanSocialMediaBundle:Default:authenticateInstagram.html.twig', array(
            'form' => $form->createView(),
            'redirectUrl' => $redirectUrl,
            'settings' => $settings,
            'isAuthenticated' => $settings->getIsAuthenticated(),
            'isActive' => $settings->getIsActive(),
        ));
    }

    /**
     * The authenticate Tumblr action
     *
     * @Route("/authenticate-tumblr", name="superrbkunstmaansocialmediabundle_admin_social_authenticate_tumblr")
     */
    public function authenticateTumblrAction(Request $request)
    {
        $settings = $this->getDoctrine()->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->tumblr();
        $redirectUrl = $this->generateUrl('superrbkunstmaansocialmediabundle_admin_social_authenticate_tumblr', array(), true);

        $formData = array(
            'active' => $settings->getSetting('active')
        );

        if($settings->getSetting('consumer_key'))
        {
            $formData['consumer_key'] = $settings->getSetting('consumer_key');

            if($settings->getSetting('user_or_hashtag'))
            {
                $formData['user_or_hashtag'] = $settings->getSetting('user_or_hashtag');
            }
            if($settings->getSetting('tumblr_url'))
            {
                $formData['tumblr_url'] = $settings->getSetting('tumblr_url');
            }
            if($settings->getSetting('hashtag'))
            {
                $formData['hashtag'] = $settings->getSetting('hashtag');
            }
            $form = $this->createForm(new TumblrAuthenticationType(), $formData);

        } else
        {
            $form = $this->createForm(new TumblrAuthenticationType());
        }

        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $settings->setSetting('active', $form['active']->getData());
                $settings->setSetting('consumer_key', $form['consumer_key']->getData());
                $settings->setSetting('user_or_hashtag', $form['user_or_hashtag']->getData());
                $settings->setSetting('tumblr_url', $form['tumblr_url']->getData());
                $settings->setSetting('hashtag', $form['hashtag']->getData());

                // save everything
                $this->getDoctrine()->getManager()->persist($settings);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        return $this->render('SuperrbKunstmaanSocialMediaBundle:Default:authenticateTumblr.html.twig', array(
            'form' => $form->createView(),
            'redirectUrl' => $redirectUrl,
            'settings' => $settings,
            'isAuthenticated' => $settings->getIsAuthenticated(),
        ));

    }

    /**
     * The authenticate Twitter action
     *
     * @Route("/authenticate-twitter", name="superrbkunstmaansocialmediabundle_admin_social_authenticate_twitter")
     */
    public function authenticateTwitterAction(Request $request)
    {
        $settings = $this->getDoctrine()->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->twitter();
        $redirectUrl = $this->generateUrl('superrbkunstmaansocialmediabundle_admin_social_authenticate_instagram', array(), true);

        $formData = array(
            'active' => $settings->getSetting('active')
        );

        if($settings->getSetting('consumer_key') and $settings->getSetting('consumer_secret'))
        {
            $formData['consumer_key'] = $settings->getSetting('consumer_key');
            $formData['consumer_secret'] = $settings->getSetting('consumer_secret');

            if($settings->getSetting('user_or_hashtag'))
            {
                $formData['user_or_hashtag'] = $settings->getSetting('user_or_hashtag');
            }

            if($settings->getSetting('username'))
            {
                $formData['username'] = $settings->getSetting('username');
            }

            if($settings->getSetting('hashtag'))
            {
                $formData['hashtag'] = $settings->getSetting('hashtag');
            }

            $form = $this->createForm(new TwitterAuthenticationType(), $formData);
        }
        else
        {
            $form = $this->createForm(new TwitterAuthenticationType());
        }

        // form has been submitted validate and redirect to twitter
        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $settings->setSetting('active', $form['active']->getData());
                $settings->setSetting('consumer_key', $form['consumer_key']->getData());
                $settings->setSetting('consumer_secret', $form['consumer_secret']->getData());
                $settings->setSetting('user_or_hashtag', $form['user_or_hashtag']->getData());
                $settings->setSetting('username', $form['username']->getData());
                $settings->setSetting('hashtag', $form['hashtag']->getData());
                $settings->setSetting('redirect_url', $redirectUrl);

                // create the bearer token credentials
                $consumerKey = urlencode($settings->getSetting('consumer_key'));
                $consumerSecret = urlencode($settings->getSetting('consumer_secret'));
                $bearerTokenCredentials = base64_encode($consumerKey . ":" . $consumerSecret);
                $settings->setSetting('bearer_token_credentials', $bearerTokenCredentials);

                // attempt to get bearer token
                try
                {
                    $client = new Client(array('base_uri' => 'https://api.twitter.com'));
                    $response = $client->post(
                        '/oauth2/token',
                        array(
                            'headers' => array(
                                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                                'Authorization' => 'Basic ' . $settings->getSetting('bearer_token_credentials')
                            ),
                            'body' => 'grant_type=client_credentials',
                        )
                    );

                    if($response->getStatusCode() == 200)
                    {
                        $data = json_decode($response->getBody()->getContents());
                        $settings->setSetting('token_type', $data->token_type);
                        $settings->setSetting('access_token', $data->access_token);
                    }
                }
                catch (\Exception $e)
                {
                    var_dump('<error>Unable to update Twitter: ' . $e->getMessage() . '</error>');
                }

                // save everything
                $this->getDoctrine()->getManager()->persist($settings);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return $this->render('SuperrbKunstmaanSocialMediaBundle:Default:authenticateTwitter.html.twig', array(
            'form' => $form->createView(),
            'redirectUrl' => $redirectUrl,
            'settings' => $settings,
            'isAuthenticated' => $settings->getIsAuthenticated(),
        ));
    }
    /**
     * The authenticate Vimeo action
     *
     * @Route("/authenticate-vimeo", name="superrbkunstmaansocialmediabundle_admin_social_authenticate_vimeo")
     */
    public function authenticateVimeoAction(Request $request)
    {
        $settings = $this->getDoctrine()->getRepository('SuperrbKunstmaanSocialMediaBundle:Setting')->vimeo();
        $redirectUrl = $this->generateUrl('superrbkunstmaansocialmediabundle_admin_social_authenticate_vimeo', array(), true);

        $formData = array(
            'active' => $settings->getSetting('active')
        );

        if($settings->getSetting('consumer_key') and $settings->getSetting('consumer_secret'))
        {
            $formData['consumer_key'] = $settings->getSetting('consumer_key');
            $formData['consumer_secret'] = $settings->getSetting('consumer_secret');

            if($settings->getSetting('user_or_hashtag'))
            {
                $formData['user_or_hashtag'] = $settings->getSetting('user_or_hashtag');
            }

            if($settings->getSetting('user_id'))
            {
                $formData['user_id'] = $settings->getSetting('user_id');
            }

            if($settings->getSetting('hashtag'))
            {
                $formData['hashtag'] = $settings->getSetting('hashtag');
            }

            $form = $this->createForm(new VimeoAuthenticationType(), $formData);
        }
        else
        {
            $form = $this->createForm(new VimeoAuthenticationType());
        }

        if($request->query->get('code', null) and $request->query->get('state') == $settings->getSetting('consumer_secret'))
        {
            // create the bearer token credentials
            $bearerTokenCredentials = base64_encode($settings->getSetting('consumer_key') . ":" . $settings->getSetting('consumer_secret'));
            $settings->setSetting('bearer_token_credentials', $bearerTokenCredentials);
            $settings->setSetting('code', $request->query->get('code'));

            try
            {
                $client = new Client(array('base_uri' => 'https://api.vimeo.com'));
                $response = $client->post(
                    '/oauth/access_token',
                    array(
                        'headers' => array(
                            'Authorization' => 'basic ' . $settings->getSetting('bearer_token_credentials')
                        ),
                        'form_params' => [
                            'grant_type' => 'authorization_code',
                            'code' => $settings->getSetting('code'),
                            'redirect_uri' => $settings->getSetting('redirect_url')
                        ]
                    )
                );
                if($response->getStatusCode() == 200)
                {
                    $details = json_decode($response->getBody()->getContents());

                    $settings->setSetting('access_token', $details->access_token);
                    $settings->setSetting('token_type', $details->token_type);
                    $settings->setSetting('scope', $details->scope);
                    $settings->setSetting('user_name', $details->user->name);
                    $settings->setSetting('user_link', $details->user->link);
                }
            }
            catch (\Exception $e)
            {
                var_dump('<error>Unable to update Vimeo: ' . $e->getMessage() . '</error>');
            }

        }
        // form has been submitted validate and redirect to vimeo
        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $settings->setSetting('active', $form['active']->getData());
                $settings->setSetting('consumer_key', $form['consumer_key']->getData());
                $settings->setSetting('consumer_secret', $form['consumer_secret']->getData());
                $settings->setSetting('user_or_hashtag', $form['user_or_hashtag']->getData());
                $settings->setSetting('user_id', $form['user_id']->getData());
                $settings->setSetting('hashtag', $form['hashtag']->getData());
                $settings->setSetting('redirect_url', $redirectUrl);

                $this->getDoctrine()->getManager()->persist($settings);
                $this->getDoctrine()->getManager()->flush();

                $queryParams = array(
                    'response_type' => 'code',
                    'client_id' => $settings->getSetting('consumer_key'),
                    'redirect_uri' => $settings->getSetting('redirect_url'),
                    'state' => $settings->getSetting('consumer_secret')
                );

                $queryParams = http_build_query($queryParams);

                return $this->redirect('https://api.vimeo.com/oauth/authorize/?' . $queryParams);
            }
        }

        // save everything
        $this->getDoctrine()->getManager()->persist($settings);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('SuperrbKunstmaanSocialMediaBundle:Default:authenticateVimeo.html.twig', array(
            'form' => $form->createView(),
            'redirectUrl' => $redirectUrl,
            'settings' => $settings,
            'isAuthenticated' => $settings->getIsAuthenticated(),
        ));
    }

    /**
     * Update the social feed
     *
     * @Route("/update-social-feed", name="superrbkunstmaansocialmediabundle_admin_social_update")
     */
    public function updateSocialFeedAction(Request $request)
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'kuma:socialMedia:update',
        ));

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        return $this->redirect($this->generateUrl('superrbkunstmaansocialmediabundle_admin_social'));
    }
}

<?php

namespace Superrb\KunstmaanSocialMediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MetzWeb\Instagram\Instagram;

class InstagramAuthenticationController extends Controller
{
    public function indexAction(Request $request)
    {
        $instagram = new Instagram(array(
            'apiKey'      => $this->container->getParameter('superrb_kunstmaan_social_mediainstagram_client_id'),
            'apiSecret'   => $this->container->getParameter('superrb_kunstmaan_social_mediainstagram_client_secret'),
            'apiCallback' => $this->container->getParameter('superrb_kunstmaan_social_mediainstagram_callback')
        ));

        if($request->query->get('code'))
        {
            $code = $request->query->get('code');
            $data = $instagram->getOAuthToken($code);

            if(isset($data->access_token))
            {
                $accessToken = $data->access_token;
            }
            else
            {
                $accessToken = 'SOMETHING WENT WRONG, PLEASE TRY AGAIN';
            }
        }
        else
        {
            $accessToken = null;
        }

        return $this->render('SuperrbKunstmaanSocialMediaBundle:Instagram:index.html.twig', array(
            'instagram' => $instagram,
            'accessToken' => $accessToken,
        ));
    }
}
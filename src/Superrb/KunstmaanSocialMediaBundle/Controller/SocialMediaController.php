<?php namespace Superrb\KunstmaanSocialMediaBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SocialMediaController extends Controller
{
    //pass the request to the action
    public function feedAction(Request $request, $limit = 10, $template = 'SuperrbKunstmaanSocialMediaBundle:SocialMedia:feed.html.twig')
    {

        // get the table
        $repository = $this->getDoctrine()
            ->getRepository('SuperrbKunstmaanSocialMediaBundle:Social');

        // get approved posts - newest first
        // limit passed or defaults to 10
        $posts = $repository->createQueryBuilder('p')
            ->where('p.approved = 1')
            ->orderBy('p.datePosted', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();

        //render the view
        return $this->render($template, array(
            'posts' => $posts,
        ));

    }
}
<?php namespace Superrb\SocialMediaFeedBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SocialMediaController extends Controller
{
    //pass the request to the action
    public function feedAction(Request $request, $limit = 10)
    {

        // get the table
        $repository = $this->getDoctrine()
            ->getRepository('SuperrbSocialMediaFeedBundle:Social');

        // get approved posts - newest first
        // limit passed or defaults to 10
        $posts = $repository->createQueryBuilder('p')
            ->where('p.approved = 1')
            ->orderBy('p.datePosted', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();

        //render the view
        return $this->render('SuperrbPowerfulWaterBundle:SocialMedia:feed.html.twig', array(
            'posts' => $posts,
        ));

    }
}
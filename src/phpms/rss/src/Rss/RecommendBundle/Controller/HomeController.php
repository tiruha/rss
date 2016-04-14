<?php

namespace Rss\RecommendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Rss\RecommendBundle\Entity\RssData;
use Rss\RecommendBundle\Form\Type\RssDataType;

class HomeController extends Controller
{
    public function homeAction()
    {
        if (!$this->container->get('session')->has('rssData')) {
            $this->container->get('session')->set('rssData', new RssData());
        }
        $form = $this->createForm(
            new RssDataType(), 
            $this->container->get('session')->get('rssData')
        );
        
        return $this->render('RssRecommendBundle:Home:home.html.twig', 
            array(
                'form' => $form->createView(),
                'loginUser' => $this->get('security.context')->getToken()->getUser()
            )
        );
    }
    
    public function homePostAction()
    {
        $form = $this->createForm(
            new RssDataType(), 
            $this->container->get('session')->get('rssData')
        );
        $form->bind($this->getRequest());
        return $this->redirect($this->generateUrl('rss_recommend_home_post'));
    }
}

<?php

namespace Rss\RecommendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Rss\RecommendBundle\Entity\LoginUser;
use Rss\RecommendBundle\Form\Type\LoginUserType;

class LoginController extends Controller
{
    public function loginAction()
    {
        if (!$this->container->get('session')->has('loginUser')) {
            $this->container->get('session')->set('loginUser', new LoginUser());
        }
        $form = $this->createForm(
            new LoginUserType(), 
            $this->container->get('session')->get('loginUser')
        );
        return $this->render('RssRecommendBundle:Login:login.html.twig', array('form' => $form->createView()));
    }
    
    public function loginPostAction()
    {
        $form = $this->createForm(
            new LoginUserType(), 
            $this->container->get('session')->get('loginUser')
        );
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            return $this->redirect($this->generateUrl('rss_recommend_home'));
        } else{
            return $this->render('RssRecommendBundle:Login:login.html.twig', array('form' => $form->createView()));
        }
    }
}

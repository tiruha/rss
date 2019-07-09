<?php

namespace Rss\RecommendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Rss\RecommendBundle\Form\Bean\HomeUrlFormBean;
use Rss\RecommendBundle\Form\Type\UrlType;
use Rss\RecommendBundle\Entity\Synonym;

/**
 * 類似度判定表示画面
 */
class ResultController extends Controller
{
    public function resultAction()
    {
        $logger = $this->get('logger');
        $logger->info("結果画面");
        //結果画面表示
        return $this->render('RssRecommendBundle:Result:result.html.twig', array('relevanceScore' => null));
    }
    
    public function resultPostAction()
    {
        if (!$this->container->get('session')->has('home_url')) {
            // default data
            $url_data = new HomeUrlFormBean();
            // 入力用に空のデータを追加
            $synonym = new Synonym();
            $synonym->setSynonym(NULL);
            $synonym->setSynonymSeverity("main");
            $url_data->setSynonym($synonym);
            // ユーザ情報を設定
            $url_data->setUser($this->get('security.context')->getToken()->getUser());
            $this->container->get('session')->set('home_url', $url_data);
        }

        $form = $this->createForm(
            new UrlType(),
            $this->container->get('session')->get('home_url')
        );
        
        return $this->render('RssRecommendBundle:Home:home.html.twig', 
            array(
                'form' => $form->createView(),
                'loginUser' => $this->get('security.context')->getToken()->getUser()
            )
        );
    }

}

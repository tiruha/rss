<?php

namespace Rss\RecommendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Rss\RecommendBundle\Form\Bean\HomeUrlFormBean;
use Rss\RecommendBundle\Form\Type\UrlType;
use Rss\RecommendBundle\Entity\Synonym;

class HomeController extends Controller
{
    public function homeAction()
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
    
    public function homePostAction()
    {
        $logger = $this->get('logger');
        $logger->info("home post action");
        
        $form = $this->createForm(
            new UrlType(), 
            $this->container->get('session')->get('home_url')
        );
        $form->bind($this->getRequest());
        $form_data = $form->getData();
        // 類義語がない場合、空データを設定
        foreach ($form_data->getSynonym() as $synonym_data) {
            if($synonym_data->getSynonymSeverity() === "sub"){
                // 類似語の追加
                $synonym = new Synonym();
                // 空データを追加
                $synonym->setSynonym(null);
                $synonym->setSynonymSeverity("sub");
                $form_data->setSynonym($synonym);
            }
        }
        // service.ymlに定義したクラスをDIコンテナから取得
        $api = $this->container->get('machine.learning.api');
        $aryy = $api->morphological("すもももももももものうち");
        foreach ($aryy as $ing){
            $logger->debug($ing[0] . "::" . $ing[1]);
        }
        $keyphrase_aryy = $api->keyphrase("すもももももももものうち");
        foreach ($keyphrase_aryy as $ing){
            $logger->debug($ing[0] . "::" . $ing[1]);
        }
        $distance_aryy = $api->distance("すもも");
        foreach ($distance_aryy as $ing){
            $logger->debug($ing[0] . "::" . $ing[1]);
        }
        $similarity = $api->similarity("すもも", "りんご");
        $logger->debug("類似度:" . $similarity);
        
        $htmlAnalyzer = $this->container->get('html.analyzer.api');
        $body = $htmlAnalyzer->getBody("http://d.hatena.ne.jp/keyword/%A5%D7%A5%ED%A5%B0%A5%E9%A5%DE%A1%BC");
        $text = $htmlAnalyzer->textExtraction($body);
        $logger->debug("本文:" . $text);
        
        $this->container->get('session')->set('home_url', $form_data);
        return $this->redirect($this->generateUrl('rss_recommend_home'));
    }
    
    public function synonymAction()
    {
        $logger = $this->get('logger');
        $logger->info("synonym post action");
        
        $form = $this->createForm(
            new UrlType(), 
            $this->container->get('session')->get('home_url')
        );
        $form->bind($this->getRequest());
        $form_data = $form->getData();
        // 登録されているデータがない場合、新規データを設定
        foreach ($form_data->getSynonym() as $synonym_data) {
            if($synonym_data->getSynonymSeverity() === "sub"){
                $target_synonym_data = $synonym_data;
            }
        }
        if(!isset($target_synonym_data)) {
            // 類似語の追加
            $synonym = new Synonym();
            // TODO: 類義語を取得する処理を追加
            // 仮データを追加
            $synonym->setSynonym("新規類義語辞典");
            $synonym->setSynonymSeverity("sub");
            $form_data->setSynonym($synonym);
        } else {
            // TODO: 類義語を取得する処理を追加
            // 仮データに上書き
            $target_synonym_data->setSynonym("変更類義語辞典");
        }
        
        $this->container->get('session')->set('home_url', $form_data);
        return $this->render('RssRecommendBundle:Home:home.html.twig', 
            array(
                'form' => $form->createView(),
                'loginUser' => $this->get('security.context')->getToken()->getUser()
            )
        );
    }
}

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
        
//        self::sampleAPICall();
        $test_url = "http://d.hatena.ne.jp/keyword/%A5%D7%A5%ED%A5%B0%A5%E9%A5%DE%A1%BC";
        self::conceptSearch($test_url);
        
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
    
    private function sampleAPICall(){
        $logger = $this->get('logger');
        // service.ymlに定義したクラスをDIコンテナから取得
        $api = $this->container->get('machine.learning.api');
        $morpho_array = $api->morphological("すもももももももものうち");
        foreach ($morpho_array as $morpho){
            $logger->debug($morpho[0] . "::" . $morpho[1]);
        }
        $keyphrase_array = $api->keyphrase("すもももももももものうち");
        foreach ($keyphrase_array as $keyphrase){
            $logger->debug($keyphrase[0] . "::" . $keyphrase[1]);
        }
        $distance_array = $api->distance("すもも");
        foreach ($distance_array as $distance){
            $logger->debug($distance[0] . "::" . $distance[1]);
        }
        $similarity = $api->similarity("すもも", "りんご");
        $logger->debug("類似度:" . $similarity);
        
        $htmlAnalyzer = $this->container->get('html.analyzer.api');
        $test_url = "http://d.hatena.ne.jp/keyword/%A5%D7%A5%ED%A5%B0%A5%E9%A5%DE%A1%BC";
        $body = $htmlAnalyzer->getBody($test_url);
        $text = $htmlAnalyzer->textExtraction($body);
        $logger->debug("本文:" . $text);
    }
    
    /**
     * 概念検索（言語モデル）
     * 
     * @param string $url
     */
    private function conceptSearch($url){
        $logger = $this->get('logger');
        $logger->info("conceptSearch");
        // HTML body から本文を取得
        $htmlAnalyzer = $this->container->get('html.analyzer.api');
        $body = $htmlAnalyzer->getBody($url);
        $text = $htmlAnalyzer->textExtraction($body);
        $logger->debug("本文：" . $text);
        // 本文の形態素解析とキーフレーズの抽出
        $api = $this->container->get('machine.learning.api');
        $morpho_array = $api->morphological($text);
        foreach ($morpho_array as $morpho) {
            $logger->debug("形態素解析結果：" . $morpho[0] . "::" . $morpho[1]);
        }
        $keyphrase_array = self::scoreAverage($api->keyphrase($text));
        foreach ($keyphrase_array as $keyphrase){
            $logger->debug("キーワードのソート結果：" . $keyphrase[0] . "::" . $keyphrase[1]);
        }
    }
    
    /**
     * 同じキーワードは平均を取って一つにまとめる
     * 
     * @param array[Keyphrase][Score] $keyphrase_array
     * @return array[Keyphrase][Score] $keyphrase_array
     */
    private function scoreAverage($keyphrase_array) {
        $kye_loop_count = count($keyphrase_array);
        /** 本体ループカウンタ */
        $kye_same_i = 0;
        $pre_keyphrase_array = $keyphrase_array;
        //直接削除しないのは、ループカウンタを用いて配列の制御を行っているから、削除するとカウンタ値が変わる
        // 本体ループ
        while ($kye_same_i < $kye_loop_count) {
            /** SUBループ内カウンタ */
            $kye = 0;
            /** 初期加算値 */
            $plus = $keyphrase_array[$kye_same_i][1];
            /** 一致カウンタ */
            $kye_sum = 1;
            // スコア0は削除対象のため飛ばす
            if ($keyphrase_array[$kye_same_i][1] == 0) {
                $kye_same_i++;
                continue;
            }
            foreach ($pre_keyphrase_array as $pre_keyphrase) {
                // 同じindexを参照しているため飛ばす
                if ($kye_same_i == $kye) {
                    $kye++;
                    continue;
                }
                // スコア0は削除対象のため飛ばす
                if ($keyphrase_array[$kye][1] == 0) {
                    $kye++;
                    continue;
                }
                //キーワードが一致
                if (strcmp($keyphrase_array[$kye_same_i][0], $pre_keyphrase[0]) == 0) {
                    // 一致キーワードの合計を算出
                    $plus = $plus + $pre_keyphrase[1];
                    /** 一致カウンタインクリメント */
                    $kye_sum++;
                    // 削除することを示すことを０を代入して示す
                    $keyphrase_array[$kye][1] = 0;
                }
                $kye++;
            }
            // キーワードが一致した場合平均を算出
            if ($kye_sum != 1) {
                $keyphrase_array[$kye_same_i][1] = $plus / $kye_sum;
            }

            $kye_same_i++;
        }

        // 重複要素を削除
        $sort_keyphrase_array = $keyphrase_array;
        $kye = 0;
        foreach ($sort_keyphrase_array as $sort_keyphrase) {
            // ０にした要素を削除
            if ($sort_keyphrase[1] == 0) {
                unset($keyphrase_array[$kye]);
            }
            $kye++;
        }
        // 順番の振り直し
        array_values($keyphrase_array);
        // 重要度の大きい順にソート
        $sort_array = array(0.0);
        $sort_i = 0;
        foreach ($keyphrase_array as $keyphrase) {
            $sort_array[$sort_i] = $keyphrase[1];
            $sort_i++;
        }
        // ソート用に用意した配列を用いて、数値として比較しソート(降順)
        array_multisort($sort_array, SORT_DESC, SORT_NUMERIC, $keyphrase_array);
        
        return $keyphrase_array;
    }

    private function createSynonymArray($synonym_array){
        $logger = $this->get('logger');
        $api = $this->container->get('machine.learning.api');
        foreach ($synonym_array as $synonym){
        $distance_array = $api->distance($synonym);
            foreach ($distance_array as $distance){
                $logger->debug($distance[0] . "::" . $distance[1]);
            }
        }
    }
}

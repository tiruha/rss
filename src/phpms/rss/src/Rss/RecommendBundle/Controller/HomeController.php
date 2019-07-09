<?php

namespace Rss\RecommendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Rss\RecommendBundle\Form\Bean\HomeUrlFormBean;
use Rss\RecommendBundle\Form\Type\UrlType;
use Rss\RecommendBundle\Entity\Synonym;

/**
 * ログイン後メイン画面
 */
class HomeController extends Controller
{
    /**
     * 初回画面表示
     * 
     * HttpMethod GET
     * @return RssRecommendBundle:Home:home.html.twig
     */
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
    
    /**
     * 新規監視登録リクエスト受付
     * 
     * HttpMethod post
     * @return rss_recommend_home
     */
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
        //formから取得したデータを出力
        $logger->debug("HomeUrlFormBean.getId():" . $form_data->getId());
        $logger->debug("HomeUrlFormBean.getUrl():" . $form_data->getUrl());
        $url = $form_data->getUrl();
        $logger->debug("HomeUrlFormBean.getLastUpdatingTime():" . $form_data->getLastUpdatingTime());
        $logger->debug("HomeUrlFormBean.getWebsiteBytesNumber():" . $form_data->getWebsiteBytesNumber());
        $logger->debug("HomeUrlFormBean.getGroup().getUrlGroup():" . $form_data->getGroup()->getUrlGroup());
        //選択したグループを取得
        $match_group = preg_replace('/userid[0-9]+_group[0-9]+_/', '', $form_data->getGroup()->getUrlGroup());
        $logger->debug("選択グループ=" . $match_group);
        //検索語、類義語をcsvから変換
        //Synonym配列[0]が検索語(main)、[1]が類義語(sub)
        $synonym_array = array();
        $synonym_counter = 0;
        foreach ($form_data->getSynonym() as $synonym_data) {
            $logger->debug("HomeUrlFormBean.getSynonym().getSynonym():" . $synonym_data->getSynonym());
            $logger->debug("HomeUrlFormBean.getSynonym().getSynonymSeverity():" . $synonym_data->getSynonymSeverity());
            $synonym_data_split = explode(",", $synonym_data->getSynonym());
            foreach ($synonym_data_split as $synonym) {
                $logger->debug("検索語・類義語:" . $synonym);
                $synonym_array[$synonym_counter][0] = $synonym;
                $synonym_array[$synonym_counter][1] = $synonym_data->getSynonymSeverity();
                $synonym_counter++;
            }
        }
        
        //概念検索の呼び出し
        $relevance_score = self::conceptSearch($url, $synonym_array);
//#########################################################################################
        //機械学習呼び出しの確認
//        self::sampleAPICall();
        //テスト用のサンプル呼び出し設定
//        $test_url = "http://d.hatena.ne.jp/keyword/%A5%D7%A5%ED%A5%B0%A5%E9%A5%DE%A1%BC";
////        $test_url = "http://www.etechno.co.jp/company/company_top.html";
//        $test_synonym_array = array();
//        $test_synonym_array[0][0] = "イーテクノ";
//        $test_synonym_array[0][1] = "main";
//        $test_synonym_array[1][0] = "開発";
//        $test_synonym_array[1][1] = "sub";
//        $test_synonym_array[2][0] = "システム";
//        $test_synonym_array[2][1] = "sub";
//        $test_synonym_array[3][0] = "提案力";
//        $test_synonym_array[3][1] = "sub";
//        $test_synonym_array[4][0] = "高付加価値サービス";
//        $test_synonym_array[4][1] = "sub";
//        $relevance_score = self::conceptSearch($test_url, $test_synonym_array);
//#########################################################################################
        
        //セッションに保存
        $this->container->get('session')->set('home_url', $form_data);
        //結果表示画面への遷移
        return $this->render('RssRecommendBundle:Result:result.html.twig'
                , array('relevanceScore' => ($relevance_score)? "関連度が'高い'検索です。":"関連度が'低い'検索です。"));
    }
    
    /**
     * 類義語検索リクエスト受付
     * 
     * @return RssRecommendBundle:Home:home.html.twig
     */
    public function synonymAction()
    {
        $logger = $this->get('logger');
        $logger->info("synonym post action");
        $api = $this->container->get('machine.learning.api');
        
        $form = $this->createForm(
            new UrlType(), 
            $this->container->get('session')->get('home_url')
        );
        $form->bind($this->getRequest());
        $form_data = $form->getData();
        // 検索語を取得
        foreach ($form_data->getSynonym() as $synonym_data) {
            if($synonym_data->getSynonymSeverity() === "main"){
                $target_synonym_data = $synonym_data->getSynonym();
            }
        }
        //指定した検索語からさらに類義語を検索
        $sub_synonym_data = "";
        $synonym_data_split = explode(",", $target_synonym_data);
        foreach ($synonym_data_split as $synonym) {
            $distance_array = $api->distance($synonym);
            foreach ($distance_array as $distance){
                $logger->debug($distance[0] . "::" . $distance[1]);
                $sub_synonym_data = $sub_synonym_data . "," . $distance[0];
            }
        }
        $logger->debug("sub検索結果単語:" . $sub_synonym_data);
        // 類似語の追加
        $synonym = new Synonym();
        $synonym->setSynonym(ltrim($sub_synonym_data, ","));
        $synonym->setSynonymSeverity("sub");
        $form_data->removeSynonym(1);
        $form_data->setSynonym($synonym);
        //セッションに保存
        $this->container->get('session')->set('home_url', $form_data);
        return $this->render('RssRecommendBundle:Home:home.html.twig', 
            array(
                'form' => $form->createView(),
                'loginUser' => $this->get('security.context')->getToken()->getUser()
            )
        );
    }
    
    /**
     * API呼び出しのサンプル
     * 
     */
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
     * @param string $url 検索対象のURL
     * @param array[類義語][main_sab] $synonym_data 類似語単語配列
     * @return boolean true:関連度の高い検索 / false:関連度の低い検索
     */
    private function conceptSearch($url, $synonym_data){
        $logger = $this->get('logger');
        $logger->info("conceptSearch");
        
        ############################
        # HTML body から本文を取得 
        ############################
        $htmlAnalyzer = $this->container->get('html.analyzer.api');
        $body = $htmlAnalyzer->getBody($url);
        $text = $htmlAnalyzer->textExtraction($body);
        $logger->debug("本文：" . $text);
        
        #################################
        # 本文の形態素解析
        #################################
        $api = $this->container->get('machine.learning.api');
        $text_morpho_array = $api->morphological($text);
        foreach ($text_morpho_array as $morpho) {
            $logger->debug("形態素解析結果：" . $morpho[0] . "::" . $morpho[1]);
        }
        
        #################################
        # 本文からキーフレーズの抽出
        #################################
        $keyphrase_array = self::scoreAverage($api->keyphrase($text));
        foreach ($keyphrase_array as $keyphrase){
            $logger->debug("キーワードのソート結果：" . $keyphrase[0] . "::" . $keyphrase[1]);
        }
        
        #################################
        # 類似単語の取得
        #################################
        //メインの類似語を設定し、概念検索時にメイン単語かを判定するのに使用
        $main_synonym = "";
        foreach ($synonym_data as $synonym){
            if(strcmp($synonym[1], "main") == 0){
                $main_synonym = $synonym[0];
            }
        }
        $synonym_array = self::createSynonymArray($synonym_data);
        foreach ($synonym_array as $synonym){
            $logger->debug("類義語単語の形態素解析結果：" . $synonym[0] . "::" . $synonym[1]);
        }
        
        #################################
	# 概念検索（言語モデル）
	#################################
        ################
        # パラメータ・定数
        ################
        $p_kanren_main = 0.3;       //関連単語と本文の一致の範囲の指定
        $p_kanren_main_sub = 1.2;   //関連単語と本文が範囲内でサブが一致したときの重み
        $p_kanren_main_main = 1.4;  //関連単語と本文が範囲内でメインが一致したときの重み

        $p_kanren_kye = 2.0;        //キーワードと関連単語が一致した場合の重み、キーワードの重要度の１００分率もかける

        $p_main_kye = 0.2;          //本文とキーワードが一致したときの範囲の指定
        $p_main_kye_sub = 1.1;      //本文とキーワードが一致したときの範囲内で、関連単語（サブ）と本文が一致したときの重み
        $p_main_kye_main = 1.2;     //本文とキーワードが一致したときの範囲内で、関連単語（メイン）と本文が一致したときの重み
        
        $p_similarity_matched = 0.7;//比較結果の類似度が一致したと判断する値
        
        //使用可能品詞の指定
        $pos_definition = array("名詞", "動詞");
        
        ################
        # 本文の名詞と動詞の数を数える
        ################
        //d_pos[0]:名詞の数 d_pos[1]:動詞の数
        $d_pos = array(0, 0);
        //現在の品詞カウンタの位置
        $d_pos_i = 0;
        foreach($pos_definition as $pos){
            foreach($text_morpho_array as $text_morpho){
                if(strcmp($text_morpho[0], $pos) == 0){
                    $d_pos[$d_pos_i]++;
                }
            }
            $d_pos_i++;
        }
        //品詞の数
        $d = $d_pos[0] + $d_pos[1];
        $logger->debug("本文の名詞と動詞の数：" . $d_pos[0] . "+" . $d_pos[1] . "=" . $d);
        
        ################
        # 品詞毎に類似度を求めて、$p_wdを品詞関係なく求めていく
        ################
        $wd_pos = 0;                //名詞・動詞が関連単語と本文で一致した数を数える
        $p_wd = array(0);           //それぞれの関連単語に対する類似度（重み）の数値
        $p_wd_i = 0;                //類似度の数値用カウンタ
        $main_near = -1.0;          //関連単語とキーワードが一致したら、キーワードの重要度の１００分率
        //一致した単語の近くでもう一度一致するかの判断カウンタ、最初の１回目で重みをかけないために＋１にする
        $near_d_counter = (int)($d * $p_kanren_main) + 1;
        //一致した単語から、重みを上げる範囲の指定。ただし:整数に型変換、全名詞数からの割合で近くかどうかを決める
        $near_d = (int)($d * $p_kanren_main);
        $flg_sub = 0;               //関連単語が一致した場合には、少し低くして重みをかける
        $flg_main = 0;              //検索単語（クエリ、メインの単語）が一致した場合には、高い重みをかける
        //本文とキーワードが一致したら、範囲内でもう一度一致した場合重みをかける
        $main_body = (int)($d * $p_main_kye);
        //メイン単語が一致した単語の近くでもう一度一致するかの判断カウンタ、最初の１回目で重みをかけないために＋１にする
        $main_body_counter = (int)($d * $p_main_kye) + 1;
        $flg_sub_main = 0;          //関連単語が一致した場合には、少し低くして重みをかけるカウンタ
        $flg_main_main = 0;         //検索単語（クエリ、メインの単語）が一致した場合には、高い重みをかけるカウンタ
        
        //品詞カウンタの初期化
        $d_pos_i = 0;
        foreach ($pos_definition as $pos) {
            //関連単語のループ開始
            foreach ($synonym_array as $synonym) {
                //関連単語の品詞が名詞か動詞にあるかを見ていく
                if (strcmp($synonym[0], $pos) != 0) {
                    //名詞か動詞のみを見るので、その他は飛ばす
                    continue;
                }

                //本文のループ開始
                foreach ($text_morpho_array as $text_morpho) {
                    //名詞か動詞のみを見る
                    if (strcmp($text_morpho[0], $pos) != 0) {
                        continue;
                    }
                    //本文と本文のキーワード単語が一致するかの判断
                    foreach ($keyphrase_array as $keyphrase) {
                        //回数制限があるので、完全一致にする
                        if (strcmp($keyphrase[0], $text_morpho[1]) == 0) {
//                        if ($api->similarity($keyphrase[0], $text_morpho[1]) >= $p_similarity_matched) {
                            //キーワードと一致したので範囲内カウンタ初期化
                            $main_body_counter = 0;
                        }
                    }

                    //本文の名詞・動詞と関連単語が一致した時の処理
                    if (strcmp($synonym[1], $text_morpho[1]) == 0) {
//                    if ($api->similarity($synonym[1], $text_morpho[1]) >= $p_similarity_matched) {
                        $wd_pos++;    //一致回数インクリメント
                        $logger->debug("関連単語：" . $synonym[1] . " === " . "本文：" . $text_morpho[1]);
                        $logger->debug("near_d_counter" . $near_d_counter . ":near_d" . $near_d);
                        //関連単語の近くの範囲内でまた一致した場合
                        if ($near_d_counter <= $near_d) {
                            //メイン単語かどうかの判断
                            if (strcmp($synonym[1], $main_synonym) == 0) {
//                            if ($api->similarity($synonym[1], $nearstr) >= $p_similarity_matched) {
                                $flg_main++;
                            } else {
                                $flg_sub++;
                            }
                        }
                        $near_d_counter = 0;    //関連単語の範囲内カウンタ初期化
                        $logger->debug("main_body_counter" . $main_body_counter . ":main_body" . $main_body);
                        //キーワード単語の近くの範囲で関連単語が一致した
                        if ($main_body_counter <= $main_body) {
                            //メイン単語かどうかの判断
                            if (strcmp($synonym[1], $main_synonym) == 0) {
//                            if ($api->similarity($synonym[1], $nearstr) >= $p_similarity_matched) {
                                $flg_main_main++;
                            } else {
                                $flg_sub_main++;
                            }
                        }
                    }
                    //毎回インクリメントしておき、範囲外にいるのが通常状態としておく
                    $near_d_counter++;
                    $main_body_counter++;
                }
                //関連単語毎にキーワードとの一致を検索して、一致したら強い重みをかける
                //関連単語と本文のキーワード単語が一致するかの判断
                foreach ($keyphrase_array as $keyphrase) {
                    //キーワードと一致したので強い重みをかける
                    if (strcmp($keyphrase[0], $synonym[1]) == 0) {
//                    if ($api->similarity($keyphrase[0], $synonym[1]) >= $p_similarity_matched) {
                        $main_near = $keyphrase[1] * 0.01;    //１００％が最大の値になるように、１００分率の値に直す
                        $logger->debug("関連単語と本文のキーワードが一致100分率：" . $main_near);
                    }
                }

                $logger->debug("関連単語それぞれの一致単語数：" . $wd_pos . " : 関連単語の近くで、また関連単語（メイン）が一致した：" . $flg_main . " : 関連単語の近くで、また関連単語（サブ）が一致した：" . $flg_sub);
                $logger->debug("全本文単語数：" . $d . " : キーワード単語の近くで、関連単語（メイン）が一致：" . $flg_main_main . " : キーワード単語の近くで、関連単語（サブ）が一致：" . $flg_sub_main);
                //P(w|d)の計算＋重み掛け
                $p_wd[$p_wd_i] = $wd_pos / $d;
                $logger->debug("各関連単語の重み：" . $p_wd[$p_wd_i]);

                //それぞれ求まったフラグを用いた重み掛け
                $flg_counter = 1;
                while ($flg_sub >= $flg_counter) {
                    $p_wd[$p_wd_i] = $p_wd[$p_wd_i] * $p_kanren_main_sub;
                    $flg_counter++;
                    $logger->debug("各関連単語の重み付け後の重み[関連単語と本文が範囲内でサブが一致]：" . $p_wd[$p_wd_i]);
                }
                $flg_counter = 1;
                while ($flg_main >= $flg_counter) {
                    $p_wd[$p_wd_i] = $p_wd[$p_wd_i] * $p_kanren_main_main;
                    $flg_counter++;
                    $logger->debug("各関連単語の重み付け後の重み[関連単語と本文が範囲内でメインが一致]：" . $p_wd[$p_wd_i]);
                }
                $flg_counter = 1;
                while ($flg_sub_main >= $flg_counter) {
                    $p_wd[$p_wd_i] = $p_wd[$p_wd_i] * $p_main_kye_sub;
                    $flg_counter++;
                    $logger->debug("各関連単語の重み付け後の重み[本文とキーワードが一致したときの範囲内で、関連単語（サブ）と本文が一致]：" . $p_wd[$p_wd_i]);
                }
                $flg_counter = 1;
                while ($flg_main_main >= $flg_counter) {
                    $p_wd[$p_wd_i] = $p_wd[$p_wd_i] * $p_main_kye_main;
                    $flg_counter++;
                    $logger->debug("各関連単語の重み付け後の重み[本文とキーワードが一致したときの範囲内で、関連単語（メイン）と本文が一致]：" . $p_wd[$p_wd_i]);
                }
                if ($main_near > 0) {
                    $p_wd[$p_wd_i] = $p_wd[$p_wd_i] * $p_kanren_kye * $main_near;
                }
                $logger->debug("各関連単語の重み付け後の重み[キーワードと関連単語が一致]：" . $p_wd[$p_wd_i]);
 
                //各種変数の変更
                $p_wd_i++;    //関連単語毎の重みを保存する
                $wd_pos = 0;    //各関連単語の累計を求めるため初期化
                $main_near = -1.0;    //キーワードとの一致１００分率を初期化
                //各種フラグを関連単語毎に初期化
                $flg_sub = 0;
                $flg_main = 0;
                $flg_sub_main = 0;
                $flg_main_main = 0;
                //範囲外に持っていくための初期化
                $near_d_counter = (int) ($d * $p_kanren_main) + 1;
                $main_body_counter = (int) ($d * $p_main_kye) + 1;
            }
        }
        
        ###################################################
        ## 類似度比較用の本文とキーワードの$p_wdを求める
        ###################################################
        $logger->debug("類似度比較用の本文とキーワードの数値を求める");
        $wd = 0;  //本文内にキーワードが含まれる回数
        $p_wd_kye_i = 0;  //キーワード毎の類似度を保持する
        foreach ($keyphrase_array as $keyphrase) {
            //キーワード毎に初期化
            $wd = 0;
            foreach ($text_morpho_array as $text_morpho) {
                if (strcmp($keyphrase[0], $text_morpho[1]) == 0) {
//                if ($api->similarity($keyphrase[0], $text_morpho[1]) >= $p_similarity_matched) {
                    $wd++;
                }
            }
            $p_wd_kye[$p_wd_kye_i] = $wd / $d;
            $logger->debug("全本文単語数：" . $d . "一致した数：" . $wd);
            $p_wd_kye_i++;
        }
        //重要度の高いキーワードから計算するようにソートする
        rsort($p_wd_kye, SORT_NUMERIC);
        $logger->debug("各キーワードの重み降順での並び：");
        $logger->debug(implode(", ", $p_wd_kye));

        ########################
        ## 各種求めた値の表示
        ########################
        $logger->debug("一致した各関連単語の重み：");
        $logger->debug(implode(", ", $p_wd));

        ########################################
        ## 最終的な類似度の数値を求め比較する
        ########################################
        //求めたP(w|d)からΠP(w|d)を繰り返しかけて求める、ただし、重みパラメータもかける
        //関連単語の類似度計算
        $p_rd = 1.0;           //クエリrに対する検索対象文献の離散集合dの類似度
        $synonym_counter = 0;  //関連単語の数
        foreach ($p_wd as $weight) {
            if ($weight > 0) {
                $p_rd = $p_rd * $weight;
                $synonym_counter++;
            }
        }
        $logger->debug("関連単語の類似度：" . $p_rd);
        $logger->debug("関連単語の数" . $synonym_counter);

        //キーワード単語の類似度計算
        $counter = 0;     //計算回数
        $p_rd_kye = 1.0;  //キーワードに対する検索対象文献の離散集合dの類似度
        foreach ($p_wd_kye as $weight) {
            //関連単語の重み計算を実行した回数のみ計算を実施することで、類似度が小さくなりすぎるのを防ぐ
            if ($counter >= $synonym_counter) {
                break;
            }
            if ($weight > 0) {
                $p_rd_kye = $p_rd_kye * $weight;
            }
            $counter++;
        }
        $logger->debug("キーワードと本文の類似度：" . $p_rd_kye);

        //類似度数値の比較
        //関連単語類似度 > キーワードの類似度*一致判定割合
        if ($p_rd > ($p_rd_kye * $p_similarity_matched)) {
            $logger->debug("関連度（重要度）の高い更新です");
            return true;
        } else {
            $logger->debug("関連度（重要度）の低い更新です");
            return false;
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

    /**
     * 類義語/同義語配列の作成
     * 類義語を形態素解析にかけた結果を返却
     * 
     * @param array[類義語][main_sab] $synonym_data 形態素解析対象の類義語
     * @return array[pos][baseform] $morpho_array 形態素解析結果
     */
    private function createSynonymArray($synonym_data){
        $logger = $this->get('logger');
        $logger->debug("createSynonymArray");
        $api = $this->container->get('machine.learning.api');

        // 類義語を形態素解析にかけるために全ての単語を空白区切りで連結する
        $morphological_str = "";
        foreach ($synonym_data as $synonym){
            $morphological_str = $morphological_str." ".$synonym[0];
        }
        $logger->debug("連結後文字列:" . $morphological_str);
        // 形態素解析
        $morpho_array = $api->morphological($morphological_str);
        foreach ($morpho_array as $morpho){
            $logger->debug($morpho[0] . "::" . $morpho[1]);
        }
        return $morpho_array;
    }
}

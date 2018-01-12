<?php

namespace Rss\RecommendBundle\TextAnalysis\API;

require_once 'HTTP/Request2.php';

use Rss\RecommendBundle\Log\Log;

/**
 * Description of ApitoreAPI
 *
 * @author tiruha
 */
class ApitoreAPI {
    // ここにあなたのアプリケーションIDを設定してください。
    const APP_ID = 'f0e78e20-4dbe-4c30-855d-21da0908ce0c';

    /**
     * 類義語/同義語検索
     * 
     * @param string $word
     * @return $result_array[word][distance]
     */
    public function distanceAPI($word){

        try{
            $url = "https://api.apitore.com/api/9/word2vec-neologd-jawiki/distance";
            $word_encode = mb_convert_encoding($word, 'utf-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS');
            LOG::info("$word_encode:" . $word_encode);
            $num = "10";
            $parameter = [
                    "access_token" => self::APP_ID,
                    "num" => $num,
                    "word" => $word_encode
                ];
            // Httpリクエストの生成
            $request = new \HTTP_Request2();
            $request->setMethod(\HTTP_Request2::METHOD_GET);
            $request->setAdapter('curl');
            $request->setUrl($url);
            // パラメータを付加
            $param_url = $request->getUrl();
            $param_url->setQueryVariables($parameter);
            $request->setUrl($param_url);
            LOG::info($param_url);
            // リクエストの送信
            $response = $request->send();
            $data = $response->getBody();
            $json = json_decode( $data , true ) ;
            LOG::info("responceStatus:" . $response->getStatus());
            LOG::info("responceBody:" . $data);
            // レスポンスの格納
            $i = 0;
            $result_array = array();
            if ($response->getStatus() == 200) {
                $result_num = count($json["distances"]);
                if ($result_num > 0) {
                    for ($i = 0; $i < $result_num; $i++) {
                        $result = $json["distances"][$i];
                        $result_array[$i][0] = $result["word"];
                        $result_array[$i][1] = $result["distance"];
                    }
                }
            }
            return $result_array;
        } catch (\HTTP_Request2_Exception $e) {
            die($e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    /**
     * 単語同士の類似度
     * 
     * @param String $word1
     * @param String $word2
     * @return double $result_similarity
     */
    public function similarityAPI($word1, $word2){

        try{
            $url = "https://api.apitore.com/api/8/word2vec-neologd-jawiki/similarity";
            $word1_encode = mb_convert_encoding($word1, 'utf-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS');
            $word2_encode = mb_convert_encoding($word2, 'utf-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS');
            LOG::info("$word1_encode:" . $word1_encode);
            LOG::info("$word2_encode:" . $word2_encode);
            $parameter = [
                    "access_token" => self::APP_ID,
                    "word1" => $word1_encode,
                    "word2" => $word2_encode
                ];
            // Httpリクエストの生成
            $request = new \HTTP_Request2();
            $request->setMethod(\HTTP_Request2::METHOD_GET);
            $request->setAdapter('curl');
            $request->setUrl($url);
            // パラメータを付加
            $param_url = $request->getUrl();
            $param_url->setQueryVariables($parameter);
            $request->setUrl($param_url);
            LOG::info($param_url);
            // リクエストの送信
            $response = $request->send();
            $data = $response->getBody();
            $json = json_decode( $data , true ) ;
            LOG::info("responceStatus:" . $response->getStatus());
            LOG::info("responceBody:" . $data);
            // レスポンスの格納
            $result_similarity = 0;
            if ($response->getStatus() == 200) {
                $result_similarity = $json["similarity"];
            }
            return $result_similarity;
        } catch (\HTTP_Request2_Exception $e) {
            die($e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

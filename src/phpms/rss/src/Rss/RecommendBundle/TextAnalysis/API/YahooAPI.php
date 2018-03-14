<?php

namespace Rss\RecommendBundle\TextAnalysis\API;

require_once 'HTTP/Request2.php';

use Rss\RecommendBundle\Log\Log;

/**
 * YahooAPIs
 *
 * @author tiruha
 */
class YahooAPI {
    // ここにあなたのアプリケーションIDを設定してください。
    const APP_ID = '';

    /**
     * 形態素解析
     * 
     * @param string $sentence
     * @return $result_array[pos][baseform]
     */
    public function morphologicalAPI($sentence){

        try{
            $url = "https://jlp.yahooapis.jp/MAService/V1/parse";
            $sentence_encode = mb_convert_encoding($sentence, 'utf-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS');
            LOG::debug("文字コード変換後形態素解析対象: " . $sentence_encode);
            // 品詞と標準化した単語を抽出
            $ma_response = "pos,baseform";
            // 名詞と動詞を抽出
            $ma_filter = "9|10";
            $parameter = [
                    "appid" => self::APP_ID,
                    "results" => "ma",
                    "ma_response" => $ma_response,
                    "ma_filter" => $ma_filter,
                    "sentence" => $sentence_encode
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
            LOG::debug($param_url);
            // リクエストの送信
            $response = $request->send();
            $data = $response->getBody();
            LOG::debug("responceStatus:" . $response->getStatus());
            LOG::debug("responceBody:" . $data);
            $xml = simplexml_load_string($data);
            // レスポンスの格納
            $i = 0;
            $result_array = array();
            if ($response->getStatus() == 200) {
                foreach ($xml->ma_result->word_list->word as $cur) {
                    $result_array[$i][0] = $cur->pos;
                    $result_array[$i][1] = $cur->baseform;
                    $i++;
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
     * キーフレーズ抽出
     * 
     * @param string $sentence
     * @return $result_array[Keyphrase][Score]
     */
    public function keyphraseAPI($sentence) {
        
        try{
            $url = "https://jlp.yahooapis.jp/KeyphraseService/V1/extract";
            $sentence_encode = mb_convert_encoding($sentence, 'utf-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS');
            LOG::debug("文字コード変換後キーフレーズ抽出対象: " . $sentence_encode);
            $output = "xml";
            $parameter = [
                    "appid" => self::APP_ID,
                    "output" => $output,
                    "sentence" => $sentence_encode
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
            LOG::debug($param_url);
            // リクエストの送信
            $response = $request->send();
            $data = $response->getBody();
            LOG::debug("responceStatus:" . $response->getStatus());
            LOG::debug("responceBody:" . $data);
            $xml = simplexml_load_string($data);
            // レスポンスの格納
            $i = 0;
            $result_array = array();
            if ($response->getStatus() == 200) {
                $result_num = count($xml->Result);
                if ($result_num > 0) {
                    for ($i = 0; $i < $result_num; $i++) {
                        $result = $xml->Result[$i];
                        $result_array[$i][0] = $result->Keyphrase;
                        $result_array[$i][1] = $result->Score;
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
}

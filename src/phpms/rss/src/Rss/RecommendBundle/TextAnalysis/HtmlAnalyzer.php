<?php

namespace Rss\RecommendBundle\TextAnalysis;

use Rss\RecommendBundle\Log\Log;

/**
 * Description of HtmlAnalyzer
 *
 * @author tiruha
 */
class HtmlAnalyzer {
    /** 本文と判断しない短い文章 */
    const SMALL_TEXT = 10;
    
    /**
     * URLからHTML Bodyを取得する
     * 
     * @param string $url
     * @return string body
     */
    public function getBody($url){
        // Httpリクエストの生成
        $request = new \HTTP_Request2();
        $request->setMethod(\HTTP_Request2::METHOD_GET);
        $request->setAdapter('curl');
        $request->setUrl($url);
        // リクエストの送信
        $response = $request->send();
        return $response->getBody();
    }
    
    /**
     * 本文抽出
     * 
     * @param string $sentence
     * @return string $text
     */
    public function textExtraction($sentence){
        $sentence_encode = mb_convert_encoding($sentence, 'utf-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS');
        // strpos() 検索文字列で最初に、指定した文字列を最初に見つけた先頭からの相対位置を返す。
        // substr() 検索文字列で、指定した位置以降の文字列を取得する。
        $body = substr($sentence_encode, strpos($sentence_encode, '</head>'));
        // JavaScript CSS を削除
        $body = preg_replace('!<noscript.*?>.*?</noscript.*?>!is', '', $body);
        $body = preg_replace('!<script.*?>.*?</script.*?>!is', '', $body);
        $body = preg_replace('!<style.*?>.*?</style.*?>!is', '', $body);
        // リンクについては本文内文字列をリンクにしていることがあるため対象外にする
        /*
        $body = preg_replace('!<a.*?>.*?</a.*?>!is', '', $body);
        */
        $text = '';
        // 本文になりうるタグ内の文字列を取得
        // "/パターン/i"　文字列の大文字・小文字を区別しない ,パターンの正規表現に一致した部分を消して、それまでの文字列を配列にいれる。
        $match = preg_split(" '(<meta[^>]*content=?)|(<td[^>]*?>)|(</td>)|(<div[^>]*?>)|(</div>)|(<p[^>]*?>)|(</p>)|(<pre[^>]*?>)|(</pre>)|(<h[1-6]*?>)|(</h[1-6]*?>)' i", $body, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($match as $val) {//$matchの先頭配列番号から順に見ていき、配列があったら取得して$valにいれる。
            $cnt = 0;
            // strip_tags($val) HTMLタグの除去をした後に、trim()で前後の空白を削除
            $val = trim(strip_tags($val));
            // 制御文字は削除
            $val = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $val);
            // 短すぎる文章は、本文でないと判断する。
            if(strlen($val) <= self::SMALL_TEXT){
                continue;
            }
            //句読点等をカウントする
            $cnt = $cnt + substr_count($val, ",");
            $cnt = $cnt + substr_count($val, ".");
            $cnt = $cnt + substr_count($val, "、");
            $cnt = $cnt + substr_count($val, "。");
            $cnt = $cnt + substr_count($val, "！");
            $cnt = $cnt + substr_count($val, "？");
            //不要文字列の削除
            $val = str_replace(" ", "", $val);
            $val = str_replace("　", "", $val);
            $val = str_replace("\n", "", $val);
            $val = str_replace("\t", "", $val);
            //句読点等が３つ以上ある場合本文とする
            if($cnt>=3){
                $text = $text.$val;
            }
        }
        return $text;
    }
}

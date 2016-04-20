# RSS拡張システム  
* ソースコード    
  * rss/src/phpms/rss/src/Rss/RecommendBundle/ 配下  
* ディレクトリ構造  

```
rss/src/phpms/rss/src/Rss/RecommendBundle/
+-- Controller ... MVCモデルのコントローラーを作成
¦   +-- HomeController.php
¦   +-- LoginController.php
+-- DependencyInjection ... Rss/RecommendBundleの設定ファイルを設定
+-- Entity ... ORM
¦   +-- LoginUser.php
¦   +-- RssData.php
+-- Form/Type ... フォームとエンティティやバリデーションの設定
¦   +-- LoginUserType.php
¦   +-- RssDataType.php
+-- Log
¦   +-- Log.php ... コントローラー以外から使えるロガーを作成
+-- Repository
¦   +-- LoginUserRepository.php ... LoginUserテーブルに対する処理を作成
+-- Resources
¦   +-- config
¦   ¦   +-- doctrine ... テーブルとEntityの対応を設定（現状アノテーションで実施しているため不要）
¦   ¦   +-- routing.yml ... URLとControllerの対応を設定
¦   ¦   +-- services.yml ... Rss/RecommendBundleのサービスを登録
¦   ¦   +-- validation.yml ... バリデーションの設定
¦   --- views ... MVCモデルのビューを作成
¦       +-- Home ... ホーム画面に関するビューを作成
¦       +-- Login ... ログイン画面に関するビューを作成
¦       +-- layout.html.twig ... 共通のレイアウト
+-- Tests/Controller ... テストコード
+-- Validator ... バリデーション関数を作成
¦    +-- LoginUserValidator.php ... LoginUserに対する独自のバリデーション関数を追加
--- RssRecommendBundle.php
```

* URL  
  * [RSS拡張システム](http://vmcentos/rss/web/app_dev.php/login)  
  * [Symfonyの動作確認](http://vmcentos/rss/web/config.php)  

# 正しく動作しているかのテスト  
* ソースコード    
  * rss/src/phpms/public/ 配下  
* URL  
  * [PHPの動作確認](http://vmcentos/public/index.php)  
  * [DB接続の動作確認](http://vmcentos/public/select_test.php)  

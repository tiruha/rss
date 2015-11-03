# RSS拡張システム
* 開発環境  
  * Apache 2.2.15 (Unix)  
  * Symfony 2.7.3  
    * /var/www/src/phpms/rss/  
  * mysql  Ver 14.14 Distrib 5.6.26, for Linux (x86_64) using  EditLine wrapper  
  * PHP 5.5.28  
  * CentOS release 6.5 (Final) x86_64 GNU/Linux  

# CentOS6.4 への簡単インストール  
※VertualBox Hyper-V VMware 等VM環境内では、新たにVMを立ち上げることが出来ない(タイムアウトエラーによって、VMの立ち上げに失敗する)  
$ sudo yum -y install git  
$ git clone https://github.com/tiruha/rss.git  
$ cd rss  
$ ./full_setup.sh  

# PHP環境の自動化  
`vagrant up` 実行に必要なコマンド群等をインストール  

1. リポジトリの追加  
  1. epel  
  2. remi  
  3. rpmforge(dkmsのインストールに必要)  
1. コマンドのインストール  
  1. wget
  2. dkms(VertualBoxの管理に推奨)  
  3. ruby(1.9.3以上のバージョン)
1. 自動環境  
  1. VirtualBox  
  2. vagrant  
  3. chef  
  4. knife-solo  

# 参考サイト  
http://totech.hateblo.jp/entry/2015/02/25/213922
http://qiita.com/inouet/items/b36638adc2b5772db457
http://shusatoo.net/infra/chef/vagrant-chef-solo-php-mysql-development-environment/
http://tsuchikazu.net/chef_solo_start/
http://qiita.com/harapeko_wktk/items/72985bfccaae60c69384
http://qiita.com/TsuyoshiUshio@github/items/89030baca68b05a9783d
http://hanocha.hateblo.jp/entry/2015/01/30/145555
http://hanocha.hateblo.jp/entry/2015/01/30/160801
https://www.rootlinks.net/2015/06/19/centos-7%E3%81%ABepelremi-repository%E3%82%92%E8%BF%BD%E5%8A%A0%E3%81%99%E3%82%8B/
http://d.hatena.ne.jp/yk5656/20140410/1397390498
http://www.d-wood.com/blog/2014/08/25_6669.html
http://qiita.com/shn/items/67aa26667ca810d48630
http://qiita.com/osamu1203/items/10e19c74c912d303ca0b
http://smallpalace.hatenablog.com/entry/2013/06/28/162853
http://www.atmarkit.co.jp/ait/articles/1001/29/news107.html
http://nigohiroki.hatenablog.com/entry/2012/12/24/151512
http://blog.machacks.net/2013/06/18/pdo%E3%81%A7mysql%E6%8E%A5%E7%B6%9A%E3%81%97%E3%81%9F%E3%82%89%E6%96%87%E5%AD%97%E5%8C%96%E3%81%91%E3%81%97%E3%81%9F%E6%99%82%E3%81%AE%E3%83%A1%E3%83%A2/
http://pxp-ss.hatenablog.com/entry/2014/01/19/201426
http://www.tokumaru.org/d/20110322.html
http://qiita.com/lethe2211/items/bc03e8b987d7de27434c
http://success.tracpath.com/blog/2014/01/25/chef%E3%82%92%E7%94%A8%E3%81%84%E3%81%9Flamp%E9%96%8B%E7%99%BA%E7%92%B0%E5%A2%83%E3%81%AE%E6%A7%8B%E7%AF%89%E6%96%B9%E6%B3%95%EF%BD%9Elamp%E7%92%B0%E5%A2%83%E6%A7%8B%E7%AF%89%E7%B7%A8/
http://box406.hatenablog.com/entry/2013/04/24/161921
http://www.starlod.net/symfony2-blog-tutorial-01.html
http://www.happytrap.jp/blogs/2012/03/30/8552/
https://sites.google.com/site/kazu0831csw2012/php_works/framework-symfony
http://www.karakaram.com/symfony2-every-work-setup
http://ka-zoo.net/2013/02/centos5-8-php5-3-w-php-process/
http://docs.symfony.gr.jp/symfony2/osc2011-nagoya-symfony2-tutorial/symfony2-php-framework-development-tutorial.html
http://qiita.com/suthio/items/988e8d66aa067fef2033
http://ism1000ch.hatenablog.com/entry/2014/04/05/232935
http://qiita.com/Itomaki/items/9a6a314a853cdcd00f80
http://dotnsf.blog.jp/archives/1020034700.html
http://otukutun.hatenablog.com/entry/2013/04/12/005749
http://heroween.hateblo.jp/entry/2014/05/28/125451
http://www.aiz-one.com/linux/20150128/
http://www.lesstep.jp/wiki/index.php?CentOS%206%E3%81%ABRPMforge%E3%82%92%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB%E3%81%99%E3%82%8B%E6%96%B9%E6%B3%95
http://oki2a24.com/2012/03/13/what-is-rpmforge-remi-epel/
http://easyramble.com/install-ruby-with-rbenv.html


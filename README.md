# PHP-MeCab を使うサンプル

PHP の MeCab モジュールが使える場合はモジュール版 PHP-MeCab を利用し、モジュールが使えない場合で、コマンド・ラインから `$ mecab` が利用できる場合は、`exec()`で代用します。

## 使い方

1. `set_dictionary()` の引数に辞書ファイルのパスを指定する。
1. `parse_mecab()` の引数に分かち処理をしたい**文字列**を指定する。

## サンプルの使い方

1. PHP ファイルに `index.php` のソースをコピペします。
1. 使用する辞書ファイルのパスを `$path_dic_mecab` の値に設定し保存します。
1. ファイルに実行パーミッション（'0755'など）があることを確認します。
1. コマンド・ラインから `$ php index.php` を実行します。

サンプル文書を MeCab で分かち処理をした配列の結果が出力されます。

## 動作検証済み環境

|確認|内容|
|:---|:---|
|検証日|2018/03/10|
|OS|macOS High Sierra（OSX 10.13.3）|
|マシン| MacBookPro（Retina, 13-inch, Early 2015）|
|`$ php -v`|PHP 7.1.8 (cli) (built: Aug  7 2017 15:02:45) ( NTS )<br>Copyright (c) 1997-2017 The PHP Group<br>Zend Engine v3.1.0, Copyright (c) 1998-2017 Zend Technologies|
|`$ mecab -v`|mecab of 0.996|
|`$ php -r "phpinfo();" | grep MeCab`|MeCab Support => enabled<br>MeCab Library => 0.996 => 0.996|


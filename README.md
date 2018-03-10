# PHP-MeCab を使うサンプル

[PHP-MeCab](https://github.com/rsky/php-mecab) などの PHP の MeCab モジュールが使える場合はモジュールを利用し、モジュールが使えない場合でコマンド・ラインから `$ mecab` が利用できる場合は、`exec()`で代用します。

## 使い方

1. `set_dictionary()` の引数に辞書ファイルのディレクトリ・パスを指定する。
1. `parse_mecab()` の引数に分かち処理をしたい**文字列**を指定する。

## サンプルの使い方

1. PHP ファイルに [`index.php` のソース](https://github.com/KEINOS/Practice_PHP-MeCab/blob/master/index.php)をコピペします。
1. 使用する辞書ファイル（`sys.dic`）のあるディレクトリまでのパスを `$path_dic_mecab` の値に設定し保存します。
1. ファイルに実行パーミッション（'0755'など）があることを確認します。
1. コマンド・ラインから `$ php index.php` を実行します。

サンプル文書を MeCab で分かち処理をした配列の結果が出力されます。

### エラーが出る場合

```
$ php index.php
PHP Warning:  MeCab\Tagger::__construct():  in /PATH/TO/YOUR/Practice_PHP-MeCab/index.php on line 145

Warning: MeCab\Tagger::__construct():  in /PATH/TO/YOUR/Practice_PHP-MeCab/index.ph on line 145
Segmentation fault: 11
```

```
phpが予期しない理由で終了しました。
詳細を確認してAppleにレポートを送信するには、"レポート"をクリックしてください。
```

上記のようなエラーが発生する場合は、**辞書ファイルのパス指定にディレクトリではなくファイルを指定している**可能性があります。


## 動作検証済み環境

|確認|内容|
|:---|:---|
|検証日|2018/03/10|
|OS|macOS High Sierra（OSX 10.13.3）|
|マシン| MacBookPro（Retina, 13-inch, Early 2015）|
|`$ php -v`|PHP 7.1.8 (cli) (built: Aug  7 2017 15:02:45) ( NTS )<br>Copyright (c) 1997-2017 The PHP Group<br>Zend Engine v3.1.0, Copyright (c) 1998-2017 Zend Technologies|
|`$ mecab -v`|mecab of 0.996|
|`$ php -r "phpinfo();" | grep MeCab`|MeCab Support => enabled<br>MeCab Library => 0.996 => 0.996|

## 参考文献

- [Mecabのシステム辞書・ユーザ辞書の利用方法について](https://qiita.com/hiro0217/items/cfcf801023c0b5e8b1c6)

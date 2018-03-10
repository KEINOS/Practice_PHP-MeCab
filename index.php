<?php
/**
 * php-mecab/examples.
 * parse string, wakati output format
 * charset=utf-8
 */

/* Constants -------------------------------------------------------- */

const USE_MECAB_OS  = false; //CLI経由でMeCabを利用
const USE_MECAB_DL  = true;  //モジュール(DynamicLibrary)版MeCabを利用
const UNAVAILABLE   = null;
const FILE_NOT_FOND = '404 File Not Found';
const SIZE_BUFFER   = 8192 * 2;

/* Sample Data ------------------------------------------------------ */

$string = <<<EOS
MeCab は 京都大学情報学研究科−日本電信電話株式会社コミュニケーション科学基礎研究所
共同研究ユニットプロジェクトの一環として開発されたオープンソース形態素解析エンジンです。
言語、辞書、コーパスに依存しない汎用的な設計を基本方針としています。
EOS;

/* Main Program ----------------------------------------------------- */

//$path_dic_mecab = '/usr/local/lib/mecab/dic/ipadic';
//$path_dic_mecab = '/usr/local/lib/mecab/dic/mecab-ipadic-neologd';
$path_dic_mecab = '/PATH/TO/YOUR/DICTIONARY/';

set_dictionary($path_dic_mecab);

$result = parse_mecab($string);
print_r($result);

die;

/* Functions -------------------------------------------------------- */

function get_path_dic_mecab()
{
    return (defined('PATH_FILE_DIC')) ? PATH_FILE_DIC : UNAVAILABLE;
}

function get_mode_mecab()
{
    return (defined('USE_MECAB_MODE')) ? USE_MECAB_MODE : UNAVAILABLE;
}

function initialize_mecab()
{
    if (! is_mecab_available()) {
        die('Error: No MeCab found. Please install MeCab.' . PHP_EOL);
    }

    switch (get_path_dic_mecab()) {
        case UNAVAILABLE:
            die(
                'Error: No dictionary set. ' .
                'Use \'set_dictionary()\' before use.' .
                PHP_EOL
            );
            break;
        case FILE_NOT_FOND:
            die(
                'Error: Dictinonary not found. ' .
                'Set the proper MeCab dictionary.' .
                PHP_EOL
            );
            break;
        default:
            //
            break;
    }
}

function is_mecab_available()
{
    $result = extension_loaded('mecab');

    if ($result) {
        set_mode_mecab(USE_MECAB_DL);
        return $result;
    }

    if (! $result) {
        $_module_suffix = (PHP_SHLIB_SUFFIX == 'dylib') ? 'so' : PHP_SHLIB_SUFFIX;
        if (dl('mecab.' . $_module_suffix)) {
            $result = true;
            set_mode_mecab(USE_MECAB_DL);
        }
    }

    if (! $result) {
        $cmd = "mecab -v";
        $msg = `$cmd`;
        if (strpos(strtolower($msg), 'not found') !== false) {
            $result = true;
            set_mode_mecab(USE_MECAB_OS);
        }
    }

    return $result;
}


function parse_mecab(string $string)
{
    initialize_mecab();

    $mode_mecab = get_mode_mecab();

    if (USE_MECAB_DL == $mode_mecab) {
        $parsed = parse_mecab_dl($string);
    } elseif (USE_MECAB_OS == get_mode_mecab()) {
        $parsed = parse_mecab_os($string);
    } else {
        die('Error: Can not determine MeCab mode.' . PHP_EOL);
    }

    $lines  = explode(PHP_EOL, $parsed);
    $result = array();

    foreach ($lines as $key => $line) {
        $tmp   = explode("\t", $line);

        if (! isset($tmp[1])) {
            continue;
        }
        $word  = trim($tmp[0]);
        $mecab = explode(',', $tmp[1]);
        $result[$key] = [
            'word'     => $word,
            'parsed'   => $mecab,
        ];
    }

    return $result;
}

function parse_mecab_dl(string $string)
{
    $path_file_dic = get_path_dic_mecab();

    $mecab = new MeCab\Tagger([
        '-d' => $path_file_dic,
    ]);

    return $mecab->parseToString($string);
}

function parse_mecab_os(string $string)
{
    //UTF-8 Unicode text, with no line terminators エラー回避用に改行追加
    $string = $string . PHP_EOL;

    $name_file_temp = 'mecab_' . md5(microtime());
    $path_file_temp = temporaryFile($name_file_temp, $string);
    $path_file_dic  = get_path_dic_mecab();
    $size_buffer    = SIZE_BUFFER;

    $cmd = "mecab -d \"${path_file_dic}\" \"${path_file_temp}\" -b ${size_buffer}";

    return `$cmd`;
}


function set_dictionary($path)
{
    if (! file_exists($path)) {
        $path = FILE_NOT_FOND;
    }

    if (! defined('PATH_FILE_DIC')) {
        define('PATH_FILE_DIC', $path);
    }

    return PATH_FILE_DIC;
}

function set_mode_mecab(bool $mecab_mode)
{
    if (! defined('USE_MECAB_MODE')) {
        define('USE_MECAB_MODE', $mecab_mode);
    }

    return USE_MECAB_MODE;
}

/**
 * @ref http://php.net/manual/ja/function.tmpfile.php#120062
 */
function temporaryFile($name, $content)
{
    $file = DIRECTORY_SEPARATOR .
            trim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR .
            ltrim($name, DIRECTORY_SEPARATOR);

    file_put_contents($file, $content);

    register_shutdown_function(function () use ($file) {
        unlink($file);
    });

    return $file;
}

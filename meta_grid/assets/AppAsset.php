<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        [
            'href' => 'images/favicon-32x32.png',
            'rel' => 'icon',
            'sizes' => '32x32',
        ],
        [
            'href' => 'images/android-chrome-192x192.png',
            'rel' => 'icon',
            'sizes' => '192x192',
        ],
        [
            'href' => 'images/apple-touch-icon.png',
            'rel' => 'apple-touch-icon-precomposed',
        ],
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'xtetis\bootstrap4glyphicons\assets\GlyphiconAsset',
    ];
}

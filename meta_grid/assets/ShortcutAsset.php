<?php
namespace app\assets;
 
use yii\web\AssetBundle;
 
class ShortcutAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
      'js/shortcut.js',
      'js/shortcut_meta_grid_init.js',
    ];
}
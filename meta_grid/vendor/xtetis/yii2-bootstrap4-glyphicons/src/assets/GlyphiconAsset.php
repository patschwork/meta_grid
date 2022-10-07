<?php
namespace xtetis\bootstrap4glyphicons\assets;

use yii\web\AssetBundle;

class GlyphiconAsset extends AssetBundle
{
    public $css = [
        'css/only_glyphicons.min.css',
    ];
    
    public function init()
    {
        $this->sourcePath = dirname(__DIR__).'/web';
        parent::init();
    }
}

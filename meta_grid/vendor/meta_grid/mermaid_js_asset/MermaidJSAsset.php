<?php

namespace vendor\meta_grid\mermaid_js_asset;

/**
 * Meta#Grid
 *
 * LICENSE: This is only a wrapper to use mermaid. Please read the license which is in included and bundled in the $sourcePath for mermaid itself
 */

use yii\web\AssetBundle;

class MermaidJSAsset extends AssetBundle
{
    public $sourcePath = '@bower/mermaid/dist';
    public $js =
    [
        'mermaid.js',
    ];
}
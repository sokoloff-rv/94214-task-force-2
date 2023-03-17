<?php

namespace app\assets;

use yii\web\AssetBundle;

class LandingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/landing.css',
    ];
    public $js = [
        'js/landing.js',
    ];
}

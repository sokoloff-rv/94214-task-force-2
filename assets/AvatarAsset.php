<?php

namespace app\assets;

use yii\web\AssetBundle;

class AvatarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/avatar-preview.js',
    ];
}

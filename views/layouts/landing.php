<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=Html::encode($this->title) . ' - Taskforce'?></title>
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/landing.css">
</head>
<body class="landing">
<?php $this->beginBody()?>

<?php if (!empty($this->params['breadcrumbs'])): ?>
    <?=Breadcrumbs::widget(['links' => $this->params['breadcrumbs']])?>
<?php endif?>
<?=Alert::widget()?>
<?=$content?>
<div class="overlay"></div>
<script src="/js/landing.js"></script>

<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>

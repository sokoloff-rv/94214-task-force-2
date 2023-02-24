<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\User;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
if (!Yii::$app->user->isGuest) {
    $user = User::getCurrentUser();
}
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=Html::encode($this->title) . ' - Taskforce'?></title>
    <link rel="stylesheet" href="/css/style.css">
    <!-- <?php $this->head()?> Все вставки вида $this->method() просили сохранить, но эта ломает верстку -->
</head>
<body>
<?php $this->beginBody()?>

<header class="page-header">
    <nav class="main-nav">
        <a href='/' class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
        </a>
        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="nav-wrapper">
                <ul class="nav-list">
                    <li class="list-item list-item--active">
                        <a href="<?=Url::to(['/tasks'])?>" class="link link--nav">Новое</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav">Мои задания</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav">Создать задание</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav">Настройки</a>
                    </li>
                </ul>
            </div>
        <?php endif;?>
    </nav>
    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="user-block">
            <?php if ($user->avatar): ?>
                <a href="#">
                    <img class="user-photo" src="<?=$user->avatar?>" width="55" height="55" alt="Аватар">
                </a>
            <?php endif;?>
            <div class="user-menu">
                <p class="user-name"><?=$user->name?></p>
                <div class="popup-head">
                    <ul class="popup-menu">
                        <li class="menu-item">
                            <a href="#" class="link">Настройки</a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="link">Связаться с нами</a>
                        </li>
                        <li class="menu-item">
                            <a href="<?=Url::to(['/users/logout'])?>" class="link">Выход из системы</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif;?>
</header>

<?php if (!empty($this->params['breadcrumbs'])): ?>
    <?=Breadcrumbs::widget(['links' => $this->params['breadcrumbs']])?>
<?php endif?>
<?=Alert::widget()?>
<?=$content?>

<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>

<?php
use app\models\User;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Menu;

use Taskforce\Models\Task as TaskBasic;

$this->title = 'Мои задания';
$formatter = Yii::$app->formatter;
if (!Yii::$app->user->isGuest) {
    $user = User::getCurrentUser();
}
?>

<main class="main-content container">

    <div class="left-menu">
        <h3 class="head-main head-task">Мои задания</h3>
        <?= Menu::widget([
            'options' => [
                'class' => 'side-menu-list',
            ],
            'items' => [
                ['label' => 'Новые', 'url' => ['/my-tasks/new'], 'visible' => $user->role === User::ROLE_CUSTOMER],
                ['label' => 'В процессе', 'url' => ['/my-tasks/working']],
                ['label' => 'Просрочено', 'url' => ['/my-tasks/overdue'], 'visible' => $user->role === User::ROLE_EXECUTOR],
                ['label' => 'Закрытые', 'url' => ['/my-tasks/closed']],
            ],
            'itemOptions' => [
                'class' => 'side-menu-item',
            ],
            'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
            'activeCssClass' => 'side-menu-item--active',
            'activateItems' => true,
            'activateParents' => false,
        ]); ?>
    </div>

    <div class="left-column left-column--task">
        <h3 class="head-main head-regular">Задания</h3>

        <?php foreach ($tasks as $task): ?>
            <div class="task-card">
                <div class="header-task">
                    <a href="<?=Url::toRoute(['/tasks/view/', 'id' => $task->id])?>" class="link link--block link--big"><?=$task->title?></a>
                    <p class="price price--task">
                        <?=$task->budget ? $formatter->asCurrency($task->budget) : 'Без бюджета'?>
                    </p>
                </div>
                <p class="info-text">
                    <?=$formatter->format(
                        $task->creation_date, 'relativeTime'
                    )?>
                </p>
                <p class="task-text"><?=$task->description?></p>
                <div class="footer-task">
                    <p class="info-text <?=isset($task->city->name) ? 'town-text' : 'laptop-text'?>">
                        <?=isset($task->city->name) ? $task->city->name : 'Удаленная работа'?>
                    </p>
                    <p class="info-text category-text"><?=$task->category->name?></p>
                    <a href="<?=Url::toRoute(['/tasks/view/', 'id' => $task->id])?>" class="button button--black">Смотреть задание</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="pagination-wrapper">
            <?= LinkPager::widget([
                'pagination' => $pagination,
                'options' => ['class' => 'pagination-list'],
                'linkOptions' => ['class' => 'link link--page',],
                'linkContainerOptions' => ['class' => 'pagination-item'],
                'activePageCssClass' => 'pagination-item--active',
                'nextPageCssClass' => 'mark',
                'prevPageCssClass' => 'mark',
                'disabledPageCssClass' => 'disabled',
                'prevPageLabel' => '',
                'nextPageLabel' => '',
            ]) ?>
        </div>
    </div>
</main>

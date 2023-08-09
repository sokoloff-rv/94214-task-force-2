<?php

use app\models\Category;
use app\models\forms\TasksFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Задания';
$formatter = Yii::$app->formatter;
$categoriesQuery = Category::find()->select(['id', 'name'])->all();
$categories = ArrayHelper::map($categoriesQuery, 'id', 'name');
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>

        <?php foreach ($tasks as $task) : ?>
            <div class="task-card">
                <div class="header-task">
                    <a href="<?= Url::toRoute(['/tasks/view/', 'id' => $task->id]) ?>" class="link link--block link--big"><?= $task->title ?></a>
                    <p class="price price--task">
                        <?= $task->budget ? $formatter->asCurrency($task->budget) : 'Без бюджета' ?>
                    </p>
                </div>
                <p class="info-text">
                    <?= $formatter->format(
                        $task->creation_date,
                        'relativeTime'
                    ) ?>
                </p>
                <p class="task-text"><?= $task->description ?></p>
                <div class="footer-task">
                    <p class="info-text <?= isset($task->city->name) ? 'town-text' : 'laptop-text' ?>">
                        <?= isset($task->city->name) ? $task->city->name : 'Удаленная работа' ?>
                    </p>
                    <p class="info-text category-text"><?= $task->category->name ?></p>
                    <a href="<?= Url::toRoute(['/tasks/view/', 'id' => $task->id]) ?>" class="button button--black">Смотреть задание</a>
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
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php $form = ActiveForm::begin([
                    'id' => 'filter-form',
                    'method' => 'get',
                    'fieldConfig' => [
                        'template' => "{input}",
                    ],
                ]); ?>

                <h4 class="head-card">Категории</h4>
                <?= $form->field($filter, 'categories')->checkboxList(
                    $categories,
                    [
                        'class' => 'checkbox-wrapper',
                        'itemOptions' => [
                            'labelOptions' => [
                                'class' => 'control-label',
                            ],
                        ],
                    ]
                ); ?>

                <h4 class="head-card">Дополнительно</h4>
                <?= $form->field($filter, 'distantWork')->checkbox(
                    [
                        'id' => 'distant-work',
                        'labelOptions' => [
                            'class' => 'control-label',
                        ],
                    ]
                ); ?>
                <?= $form->field($filter, 'noResponse')->checkbox(
                    [
                        'id' => 'no-response',
                        'labelOptions' => [
                            'class' => 'control-label',
                        ],
                    ]
                ); ?>

                <h4 class="head-card">Период</h4>
                <?= $form->field($filter, 'period')->dropDownList(
                    TasksFilter::getPeriodsMap(),
                    [
                        'id' => 'period-value',
                    ]
                ); ?>

                <input type="submit" class="button button--blue" value="Искать">
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>

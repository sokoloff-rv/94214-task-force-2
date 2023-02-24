<?php
use app\models\Category;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

Yii::$app->formatter->defaultTimeZone = 'Asia/Bishkek';
Yii::$app->formatter->locale = 'ru-RU';
$this->title = 'Новое задание';

$categoriesQuery = Category::find()->select(['id', 'name'])->all();
$categories = ArrayHelper::map($categoriesQuery, 'id', 'name');
?>

<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
        <?php $form = ActiveForm::begin([
            'id' => 'new-task-form',
            'method' => 'post',
            'fieldConfig' => [
                'template' => "{label}{input}\n{error}",
            ],
        ]);?>
            <h3 class="head-main head-main">Публикация нового задания</h3>
            <?=$form->field($newTask, 'title');?>
            <?=$form->field($newTask, 'description')->textarea();?>
            <?=$form->field($newTask, 'category')->dropDownList($categories);?>
            <?=$form->field($newTask, 'location')->textInput(
                ['class' => 'location-icon']
            );?>
            <div class="half-wrapper">
                <?=$form->field($newTask, 'budget')->textInput(
                    ['class' => 'budget-icon']
                );?>
                <?=$form->field($newTask, 'deadline')->input('date');?>
            </div>
            <?=$form->field($newTask, 'files')->fileInput(
                ['class' => 'new-file', 'multiple' => true]
            );?>
            <input type="submit" class="button button--blue" value="Опубликовать">
        <?php ActiveForm::end();?>
    </div>
</main>

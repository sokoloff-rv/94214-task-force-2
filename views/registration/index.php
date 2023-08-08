<?php

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\City;

$this->title = 'Регистрация';
$formatter = Yii::$app->formatter;
$cities = ArrayHelper::map(City::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
?>

<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php $form = ActiveForm::begin([
                'id' => 'registration-form',
                'method' => 'post',
                'fieldConfig' => [
                    'template' => "{label}{input}\n{error}",
                ],
            ]); ?>
            <h3 class="head-main head-task">Регистрация нового пользователя</h3>
            <?= $form->field($registration, 'name'); ?>
            <div class="half-wrapper">
                <?= $form->field($registration, 'email'); ?>
                <?= $form->field($registration, 'city',)->dropDownList($cities); ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($registration, 'password')->passwordInput(); ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($registration, 'passwordRepeat')->passwordInput(); ?>
            </div>
            <?= $form->field($registration, 'isExecutor')->checkbox(
                [
                    'labelOptions' => [
                        'class' => 'control-label',
                    ],
                ]
            ); ?>
            <input type="submit" class="button button--blue" value="Создать аккаунт">
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</main>

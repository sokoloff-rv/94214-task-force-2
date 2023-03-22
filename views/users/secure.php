<?php
use yii\widgets\ActiveForm;
use yii\widgets\Menu;

$this->title = "Редактирование профиля";
?>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <?=Menu::widget([
            'options' => [
                'class' => 'side-menu-list',
            ],
            'items' => [
                ['label' => 'Мой профиль', 'url' => ['/users/edit']],
                ['label' => 'Безопасность', 'url' => ['/users/secure']],
            ],
            'itemOptions' => [
                'class' => 'side-menu-item',
            ],
            'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
            'activeCssClass' => 'side-menu-item--active',
            'activateItems' => true,
            'activateParents' => false,
        ]);?>
    </div>
    <div class="my-profile-form">
        <?php $form = ActiveForm::begin([
            'id' => 'secure-form',
            'method' => 'post',
            'fieldConfig' => [
                'template' => "{label}{input}\n{error}",
            ],
        ]);?>
            <h3 class="head-main head-regular">Безопасность</h3>
            <?=$form->field($secure, 'oldPassword')->passwordInput();?>
            <?=$form->field($secure, 'newPassword')->passwordInput();?>
            <?=$form->field($secure, 'repeatPassword')->passwordInput();?>
            <?=$form->field($secure, 'hiddenContacts')->checkbox();?>
            <input type="submit" class="button button--blue" value="Сохранить">
        <?php ActiveForm::end();?>
    </div>
</main>

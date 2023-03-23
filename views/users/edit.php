<?php
use app\assets\AvatarAsset;
use app\models\Category;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Menu;

AvatarAsset::register($this);

$this->title = "Редактирование профиля";
$categoriesQuery = Category::find()->select(['id', 'name'])->all();
$categories = ArrayHelper::map($categoriesQuery, 'id', 'name');
$dateTimeObject = new DateTime($user->birthday);
$userBirthday = $dateTimeObject->format('Y-m-d');
$userSpecializations = explode(', ', $user->specializations);
?>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <?= Menu::widget([
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
        ]); ?>
    </div>
    <div class="my-profile-form">
        <?php $form = ActiveForm::begin([
            'id' => 'profile-form',
            'method' => 'post',
            'fieldConfig' => [
                'template' => "{label}{input}\n{error}",
            ],
        ]);?>
            <h3 class="head-main head-regular">Мой профиль</h3>
            <div class="photo-editing">
                <div>
                    <p class="form-label">Аватар</p>
                    <img class="avatar-preview" src="<?=$user->avatar ? $user->avatar : "/img/default-avatar.webp";?>" width="83" height="83">
                </div>
                <?=$form->field($profile, 'avatar')->fileInput(['id' => 'profile-avatar', 'hidden' => true])->label(false);?>
                <label for="profile-avatar" class="button button--black">Сменить аватар</label>
            </div>
            <?=$form->field($profile, 'name')->textInput(['value' => $user->name]);?>
            <div class="half-wrapper">
                <?=$form->field($profile, 'email')->input('email', ['value' => $user->email]);?>
                <?=$form->field($profile, 'birthday')->input('date', ['value' => $userBirthday]);?>
            </div>
            <div class="half-wrapper">
                <?=$form->field($profile, 'phone')->input('tel', ['value' => $user->phone]);?>
                <?=$form->field($profile, 'telegram')->textInput(['value' => $user->telegram]);?>
            </div>
            <?=$form->field($profile, 'information')->textarea(['value' => $user->information]);?>
            <?php if ($user->role !== User::ROLE_CUSTOMER): ?>
                <?=$form->field($profile, 'specializations')->checkboxList(
                    $categories,
                    [
                        'class' => 'checkbox-profile',
                        'item' => function ($index, $label, $name, $checked, $value) use ($userSpecializations) {
                            $checked = in_array($value, $userSpecializations) ? 'checked' : '';
                            return "<label class='control-label'><input type='checkbox' name='{$name}' value='{$value}' {$checked}> {$label}</label>";
                        },
                    ]
                );?>
            <?php endif;?>
            <input type="submit" class="button button--blue" value="Сохранить">
        <?php ActiveForm::end();?>
    </div>
</main>

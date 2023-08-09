<?php
use app\assets\MapAsset;
use app\models\User;
use app\models\File;
use Taskforce\Helpers\RateHelper;
use Taskforce\Helpers\ResponsesHelper;
use Taskforce\Helpers\TasksHelper;
use Taskforce\Models\Task as TaskBasic;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

MapAsset::register($this);

$this->title = "Просмотр задания c id $task->id";
$formatter = Yii::$app->formatter;
$files = File::find()->where(['task_id' => $task->id])->all();
if (!Yii::$app->user->isGuest) {
    $user = User::getCurrentUser();
}
?>

<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?=$task->title?></h3>
            <p class="price price--big">
                <?=$task->budget ? $formatter->asCurrency($task->budget) : 'Бюджет не указан'?>
            </p>
        </div>
        <p class="task-description">
            <?=$task->description?>
        </p>

        <?php if (TasksHelper::userCanSeeResponseButton($user->id, $user->role, $task->status, $task->responses)): ?>
            <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
        <?php endif;?>

        <?php if (TasksHelper::userCanSeeRefusalButton($user->id, $task->status, $task->executor_id)): ?>
            <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
        <?php endif;?>

        <?php if (TasksHelper::userCanSeeCompletionButton($user->id, $task->status, $task->customer_id)): ?>
            <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
        <?php endif;?>

        <?php if (TasksHelper::userCanSeeCancelButton($user->id, $task->status, $task->customer_id)): ?>
            <a href="#" class="button button--yellow action-btn" data-action="cancel">Отменить задание</a>
        <?php endif;?>

        <div class="task-map">
            <?php if ($task->city): ?>
                <div id="map" style="width: 725px; height: 346px;"></div>
            <?php endif;?>
            <p class="map-address town" style="padding-top: 25px;">
                <?=isset($task->city->name) ? $task->city->name : 'Удаленная работа'?>
            </p>
            <?php if (isset($task->location)): ?>
                <p class="map-address"><?=$task->location?></p>
            <?php endif;?>
        </div>

        <?php if (ResponsesHelper::userCanSeeResponsesList($task->responses, $user->id, $task->customer_id)): ?>
            <h4 class="head-regular">Отклики на задание</h4>

            <?php foreach ($task->responses as $response): ?>
                <?php if (ResponsesHelper::userCanSeeResponse($user->id, $task->customer_id, $response->executor_id)): ?>
                    <div class="response-card">
                        <a class="image-wrapper" href="<?=Url::toRoute(['/users/view/', 'id' => $response->executor->id])?>"/>
                            <img class="customer-photo" src="<?=$response->executor->avatar ? $response->executor->avatar : "/img/default-avatar.webp"?>" width="146" height="156" alt="Фото исполнителя">
                        </a>
                        <div class="feedback-wrapper">
                            <a href="<?=Url::toRoute(['/users/view/', 'id' => $response->executor->id])?>" class="link link--block link--big"><?=$response->executor->name?></a>
                            <div class="response-wrapper">
                                <div class="stars-rating small">
                                    <?=RateHelper::getStars($response->executor->userRating)?>
                                </div>
                                <p class="reviews">
                                    <?=Yii::t('app', '{n, plural, =0{# отзывов} one{# отзыв} =2{# отзыва} =3{# отзыва} =4{# отзыва} few{# отзыва} many{# отзывов} other{# отзывов}}', ['n' => count($response->executor->reviewsOnExecutor)]);?>
                                </p>
                            </div>
                            <p class="response-message">
                                <?=$response->comment?>
                            </p>
                        </div>
                        <div class="feedback-wrapper">
                            <p class="info-text"><span class="current-time">
                                <?=$formatter->format(
                                    $response->creation_date, 'relativeTime'
                                )?>
                            </p>
                            <p class="price price--small">
                                <?=$response->price ? $formatter->asCurrency($response->price) : 'Бюджет не указан'?>
                            </p>
                        </div>
                        <?php if (ResponsesHelper::userCanSeeResponseButtons($user->id, $task->customer_id, $task->status, $response->status)): ?>
                            <div class="button-popup">
                                <a href="<?= Url::toRoute(['/tasks/accept', 'responseId' => $response->id, 'taskId' => $task->id, 'executorId' => $response->executor->id]) ?>" class="button button--blue button--small">Принять</a>
                                <a href="<?= Url::toRoute(['/tasks/refuse', 'responseId' => $response->id]) ?>" class="button button--orange button--small">Отказать</a>
                            </div>
                        <?php endif;?>
                    </div>
                <?php endif;?>
            <?php endforeach;?>

        <?php endif;?>

    </div>
    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?=$task->category->name?></dd>
                <dt>Дата публикации</dt>
                <dd>
                    <?=$formatter->format(
                        $task->creation_date, 'relativeTime'
                    )?>
                </dd>
                <dt>Срок выполнения</dt>
                <dd>
                    <?=$formatter->asDate(
                        $task->deadline, 'php:d F, H:i'
                    )?>
                </dd>
                <dt>Статус</dt>
                <dd><?=TaskBasic::getStatusName($task->status)?></dd>
            </dl>
        </div>
        <?php if ($files): ?>
            <div class="right-card white file-card">
                <h4 class="head-card">Файлы задания</h4>
                <ul class="enumeration-list">
                    <?php foreach ($files as $file): ?>
                        <li class="enumeration-item">
                            <a href="<?=Yii::$app->urlManager->baseUrl.$file->link?>" class="link link--block link--clip">
                                <?=str_replace('/uploads/', '', $file->link)?>
                            </a>
                            <p class="file-size">
                                <?= Yii::$app->formatter->asShortSize(
                                    filesize(Yii::getAlias('@webroot').$file->link)
                                )?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</main>
<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a href="<?= Url::toRoute(['/tasks/fail', 'taskId' => $task->id, 'executorId' => $user->id]) ?>" class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">

            <?php $form = ActiveForm::begin([
                'id' => 'new-review',
                'method' => 'post',
                'action' => Url::toRoute(['/tasks/review/', 'taskId' => $task->id, 'executorId' => $task->executor_id]),
                'fieldConfig' => [
                    'template' => "{label}{input}\n{error}",
                ],
            ]);?>
                <?=$form->field($reviewForm, 'comment')->textarea();?>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></div>
                <?=$form->field($reviewForm, 'grade')->hiddenInput(['id' => 'acceptance-form-rate'])->label(false);?>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            <?php ActiveForm::end();?>

        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">

            <?php $form = ActiveForm::begin([
                'id' => 'new-response',
                'method' => 'post',
                'action' => Url::toRoute(['/tasks/response/', 'taskId' => $task->id]),
                'fieldConfig' => [
                    'template' => "{label}{input}\n{error}",
                ],
            ]);?>
                <?=$form->field($responseForm, 'comment')->textarea();?>
                <?=$form->field($responseForm, 'price');?>
                <input type="submit" class="button button--pop-up button--blue" value="Отправить">
            <?php ActiveForm::end();?>

        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--cancel pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отмена задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отменить это задание.<br>
            Это действие удалит задание из ленты заданий и вы не сможете найти исполнителя.
        </p>
        <a href="<?= Url::toRoute(['/tasks/cancel', 'taskId' => $task->id]) ?>" class="button button--pop-up button--yellow">Отменить задание</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<div class="overlay"></div>

<script type="text/javascript">
    ymaps.ready(init);
    function init(){
        var myMap = new ymaps.Map("map", {
            center: [<?=$task->latitude . ',' . $task->longitude?>],
            zoom: 15
        });

        var myPlacemark = new ymaps.Placemark([<?=$task->latitude . ',' . $task->longitude?>], {
            hintContent: 'Метка'
        }, {
            preset: 'islands#redIcon'
        });

        myMap.geoObjects.add(myPlacemark);
    }
</script>

<?php
use app\models\User;
use app\models\File;
use Taskforce\Helpers\RateHelper;
use Taskforce\Helpers\ResponsesHelper;
use Taskforce\Models\Task as TaskBasic;
use yii\helpers\Url;

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
                <?=$task->budget ? $formatter->asCurrency($task->budget) : 'Бюджен не указан'?>
            </p>
        </div>
        <p class="task-description">
            <?=$task->description?>
        </p>

        <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>

        <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>

        <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>

        <div class="task-map">
            <img class="map" src="/img/map.png"  width="725" height="346" alt="">
            <p class="map-address town">
                <?=isset($task->city->name) ? $task->city->name : 'Удаленная работа'?>
            </p>
            <?php if (isset($task->city->name)): ?>
                <p class="map-address">Здесь, видимо, будет адрес, хотя такого поля в БД пока нет</p>
            <?php endif;?>
        </div>

        <?php if (ResponsesHelper::userCanSeeResponsesList($task->responses, $user->id, $task->customer_id)): ?>
            <h4 class="head-regular">Отклики на задание</h4>

            <?php foreach ($task->responses as $response): ?>
                <?php if (ResponsesHelper::userCanSeeResponse($user->id, $task->customer_id, $response->executor_id)): ?>
                    <div class="response-card">
                        <img class="customer-photo" src="<?=$response->executor->avatar?>" width="146" height="156" alt="Фото исполнителя">
                        <div class="feedback-wrapper">
                            <a href="<?=Url::toRoute(['/users/view/', 'id' => $response->executor->id])?>" class="link link--block link--big"><?=$response->executor->name?></a>
                            <div class="response-wrapper">
                                <div class="stars-rating small">
                                    <?=RateHelper::getStars($response->executor->UserRating)?>
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
                                <a href="#" class="button button--blue button--small">Принять</a>
                                <a href="#" class="button button--orange button--small">Отказать</a>
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
        <a class="button button--pop-up button--orange">Отказаться</a>
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
            <form>
                <div class="form-group">
                    <label class="control-label" for="completion-comment">Ваш комментарий</label>
                    <textarea id="completion-comment"></textarea>
                </div>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
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
            <form>
                <div class="form-group">
                    <label class="control-label" for="addition-comment">Ваш комментарий</label>
                    <textarea id="addition-comment"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label" for="addition-price">Стоимость</label>
                    <input id="addition-price" type="text">
                </div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<div class="overlay"></div>
<script src="js/main.js"></script>

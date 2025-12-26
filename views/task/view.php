<?php
/** @var app\models\Task $task */

use app\models\Review;
use HtmlAcademy\core\Task as CoreTask;
use HtmlAcademy\enums\OfferStatus;
use HtmlAcademy\enums\TaskStatus;
use yii\widgets\ActiveForm;

?>

<?php
    var_dump($isCustomer);
    var_dump($task->status);
    var_dump(TaskStatus::NEW->value);
    var_dump($userHasOffer);
    
?>

<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= $task->title ?></h3>
            <p class="price price--big"><?= $task->budget ?> ₽</p>
        </div>
        <p class="task-description">
            <?= $task->description ?></p>

        <?php if ((Yii::$app->user->identity?->role !== 'customer') && !$isCustomer && (!$userHasOffer && $task->status === TaskStatus::NEW->value)) : ?>
            <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
        <?php endif; ?>

        <?php if ($userHasOffer && $task->status === TaskStatus::INPROGRESS->value) : ?>
            <a href="#" class="button button--orange action-btn" data-action="fail">Отказаться от задания</a>
        <?php endif; ?>

        <?php if ($isCustomer && $task->status === TaskStatus::NEW->value) : ?>
            <a href="#" class="button button--pink action-btn" data-action="cancel">Отменить задание</a>
        <?php endif; ?>
        
        <?php if ($isCustomer && $task->status === TaskStatus::INPROGRESS->value) : ?>
            <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
        <?php endif; ?>
        
        <div class="task-map">
            <img class="map" src="<?= Yii::getAlias('@web/img/map.png') ?>"  width="725" height="346" alt="">
            <p class="map-address town"><?= $task->city->name ?></p>
            <p class="map-address"><?= $task->location ?></p>
        </div>

        <h4 class="head-regular">Отклики на задание</h4>
        <?php foreach ($offers as $offer): ?>
            <div class="response-card <?= $retVal = ($offer->status === OfferStatus::DENY->value) ? 'deny' : '' ; ?>">
                <img class="customer-photo" src="<?= $offer->performer->avatar ?>" width="146" height="156" alt="Фото заказчиков">
                <div class="feedback-wrapper">
                    <a href="/user/view/<?= $offer->performer_id; ?>" class="link link--block link--big">
                        <?= $offer->performer->name ?> 
                        <?= $offer->status ?>
                        <?= $offer->id ?>
                    </a>
                    <div class="response-wrapper">
                        <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                        <p class="reviews">2 отзыва</p>
                    </div>
                    <p class="response-message">
                        <?= $offer->comment ?>
                    </p>

                </div>
                <div class="feedback-wrapper">
                    <p class="info-text" title="<?= $offer->date_add; ?>"><?= Yii::$app->formatter->asRelativeTime($offer->date_add)?></p>
                    <p class="price price--small"><?= $offer->price ?> ₽</p>
                </div>
                <?php if ($isCustomer && $offer->status === OfferStatus::NEW->value) : ?>
                    <div class="button-popup">
                        <?= \yii\helpers\Html::a(
                            'Принять',
                            ['task/accept', 'taskId' => $task->id, 'offerId' => $offer->id],
                            [
                                'data-method' => 'post',
                                'class' => 'button button--blue button--small',
                                'data-confirm' => 'Принять исполнителя?'
                            ]
                        ) ?>

                        <?= \yii\helpers\Html::a(
                            'Отказать',
                            ['task/reject', 'taskId' => $task->id, 'offerId' => $offer->id],
                            [
                                'class' => 'button button--orange button--small',
                                'data-method' => 'post',
                                'data-confirm' => 'Отказать исполнителю?'
                            ]
                        ) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    </div>
    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= $task->category->name ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= Yii::$app->formatter->asDatetime($task->date_add, 'php:d.m.Y H:i') ?></dd>
                <dt>Срок выполнения</dt>
                <dd><?= Yii::$app->formatter->asDatetime($task->deadline, 'php:d.m.Y H:i') ?></dd>
                <dt>Статус</dt>
                <dd><?= CoreTask::statusGetName($task->status) ?></dd>
            </dl>
        </div>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                    <p class="file-size">356 Кб</p>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">information.docx</a>
                    <p class="file-size">12 Кб</p>
                </li>
            </ul>
        </div>
    </div>
</main>

<section class="pop-up pop-up--fail pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>

        <?php $form = ActiveForm::begin([
                'id' => 'fail-form',
                'action' => ['task/fail', 'taskId' => $task->id],
            ]); ?>

            <input style="width:auto;" type="submit" class="button button--orange action-btn" value="Отказаться">

        <?php ActiveForm::end(); ?>

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
                'id' => 'offer-form',
                'action' => ['task/completion', 'taskId' => $task->id],
            ]); ?>

            <?= $form->field($review, 'text')->textarea()->label('Ваш комментарий') ?>

            
            <?= $form->field($review, 'score')
                ->hiddenInput(['id' => 'review-score'])
                ->label('Оценка работы') ?>

            <div class="stars-rating big" data-input="#review-score">
                <span data-value="1">&nbsp;</span>
                <span data-value="2">&nbsp;</span>
                <span data-value="3">&nbsp;</span>
                <span data-value="4">&nbsp;</span>
                <span data-value="5">&nbsp;</span>
            </div>

            <input style="width:auto;" type="submit" class="button button--pop-up button--blue" value="Завершить">

        <?php ActiveForm::end(); ?>
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
            Вы собираетесь отменить это задание.
            <br>Это действие нельзя отменить.
        </p>
        <div class="cancel-form pop-up--form regular-form">

        <?php $form = ActiveForm::begin([
                'id' => 'offer-form',
                'action' => ['task/cancel', 'taskId' => $task->id],
            ]); ?>

            <input style="width:auto;" type="submit" class="button button--pop-up button--pink" value="Отменить задание">

        <?php ActiveForm::end(); ?>

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
            'id' => 'offer-form',
            'action' => ['task/create-offer', 'taskId' => $task->id],
            'enableAjaxValidation' => true,
        ]); ?>

            <?= $form->field($newOffer, 'comment')->textarea()->label('Ваш комментарий') ?>

            <?= $form->field($newOffer, 'price')->textInput(['class' => ''])->label('Стоимость') ?>


            <input style="width:auto;" type="submit" class="button button--pop-up button--blue" value="Опубликовать">

        <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>

<div class="overlay"></div>
<script src="<?= Yii::getAlias('@web/js/main.js') ?>"></script>

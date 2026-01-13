<?php
/** @var app\models\User $user */
?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main"><?= $user->name ?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <img class="card-photo" src="/<?= $user->avatar ?>" width="191" height="190" alt="Фото пользователя">
                <div class="card-rate">
                    <div class="stars-rating big"><span class="fill-star">&nbsp;</span></div>
                    <span class="current-rate"><?= round($user->ratingCalculator($user), 2); ?></span>
                </div>
            </div>
            <p class="user-description">
                <?= $user->about ?>
            </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach ($allSpecializations as $spec):?>
                        <li class="special-item">
                            <?= $spec->name; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info"><span class="country-info">Россия</span>, <span class="town-info"><?= $user->city->name; ?></span>, <span class="age-info"><?= $user->birthday ? (new \DateTime())->diff(new \DateTime($user->birthday))->y : null ?></span> лет</p>
            </div>
        </div>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($reviews as $review): ?>
        <div class="response-card">
            <img class="customer-photo" src="/<?= $review->customer->avatar; ?>" width="120" height="127" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <p class="feedback"><?= $review->text; ?></p>
                <p class="task">Задание «<a href="/task/view/<?= $review->task_id; ?>" class="link link--small"><?= $review->task->title; ?></a>» выполнено</p>
            </div>
            <div class="feedback-wrapper">
                <div class="stars-rating small"><span class="fill-star">&nbsp;</span><?= $review->score; ?></div>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($review->date_add)?></span></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                    <dt>Всего заказов</dt>
                    <dd><?= $completedOffers; ?> выполнено, <?= $failedOffers; ?> провалено</dd>
                    <dt>Место в рейтинге</dt>
                    <dd><?= round($rating['rating']); ?> место</dd>
                    <dt>Дата регистрации</dt>
                    <dd><?= Yii::$app->formatter->asDatetime($user->date_add, 'php:d F Y H:i') ?></dd>
                    <dt>Статус</dt>

                    <?php if (!$isPerformerFree): ?>
                        <dd>Открыт для новых заказов</dd>
                    <?php else: ?>
                        <dd>Занят</dd>
                    <?php endif; ?>

            </dl>
        </div>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="tel:<?= $user->phone; ?>" class="link link--block link--phone"><?= $user->phone; ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="mailto:<?= $user->email; ?>" class="link link--block link--email"><?= $user->email; ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="https://t.me/<?= $user->telegram; ?>" class="link link--block link--tg">@<?= $user->telegram; ?></a>
                </li>
            </ul>
        </div>
    </div>
</main>

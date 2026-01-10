<?php
    // print_r($newTasks);
?>
<style>
    .fixedMenu{
        position: fixed;
    }
    #new{
        color: white;
        background-color: blue;
        padding: 10px;
    }
    #inP{
        color: white;
        background-color: orangered;
        padding: 10px;
    }
    #closed{
        color: white;
        background-color: black;
        padding: 10px;
    }
</style>
<main class="main-content container">
    <div class="left-menu">
        <div class="fixedMenu">
            <h3 class="head-main head-task">Мои задания</h3>
            <ul class="side-menu-list">
                <li class="side-menu-item">
                    <a href="#new" class="link link--nav">Новые</a>
                </li>
                <li class="side-menu-item">
                    <a href="#inP" class="link link--nav">В процессе</a>
                </li>
                <li class="side-menu-item">
                    <a href="#closed" class="link link--nav">Закрытые</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="left-column left-column--task">
        <h3 id="new" class="head-main head-regular">Новые задания</h3>
        <?php if ($newTasks): ?>
            <?php foreach ($newTasks as $task): ?>
                <div class="task-card">
                    <div class="header-task">
                        <a  href="/task/view/<?= $task->id; ?>" class="link link--block link--big"><?= $task->title; ?></a>
                        <p class="price price--task"><?= $task->budget; ?> ₽</p>
                    </div>
                    <p class="info-text" title="<?= $task->date_add; ?>">
                        <span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->date_add)?></span>
                    </p>
                    <p class="task-text">
                        <?= $task->description; ?>
                    </p>
                    <div class="footer-task">
                        <p class="info-text town-text"><?= $task->city->name; ?></p>
                        <p class="info-text category-text"><?= $task->category->name; ?></p>
                        <a href="/task/view/<?= $task->id; ?>" class="button button--black">Смотреть Задание</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет новых задач</p>
        <?php endif; ?>

        <h3 id="inP" class="head-main head-regular">В процессе</h3>
        <?php if ($inProgressTasks): ?>
            <?php foreach ($inProgressTasks as $task): ?>
                <div class="task-card">
                    <div class="header-task">
                        <a  href="/task/view/<?= $task->id; ?>" class="link link--block link--big"><?= $task->title; ?></a>
                        <p class="price price--task"><?= $task->budget; ?> ₽</p>
                    </div>
                    <p class="info-text" title="<?= $task->date_add; ?>">
                        <span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->date_add)?></span>
                    </p>
                    <p class="task-text">
                        <?= $task->description; ?>
                    </p>
                    <div class="footer-task">
                        <p class="info-text town-text"><?= $task->city->name; ?></p>
                        <p class="info-text category-text"><?= $task->category->name; ?></p>
                        <a href="/task/view/<?= $task->id; ?>" class="button button--black">Смотреть Задание</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет задач в процессе</p>
        <?php endif; ?>

        <h3 id="closed" class="head-main head-regular">Закрытые</h3>
        <?php if ($closedTasks): ?>
            <?php foreach ($closedTasks as $task): ?>
                <div class="task-card">
                    <div class="header-task">
                        <a  href="/task/view/<?= $task->id; ?>" class="link link--block link--big"><?= $task->title; ?></a>
                        <p class="price price--task"><?= $task->budget; ?> ₽</p>
                    </div>
                    <p class="info-text" title="<?= $task->date_add; ?>">
                        <span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->date_add)?></span>
                    </p>
                    <p class="task-text">
                        <?= $task->description; ?>
                    </p>
                    <div class="footer-task">
                        <p class="info-text town-text"><?= $task->city->name; ?></p>
                        <p class="info-text category-text"><?= $task->category->name; ?></p>
                        <a href="/task/view/<?= $task->id; ?>" class="button button--black">Смотреть Задание</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет закрытых задач</p>
        <?php endif; ?>
    </div>
</main>
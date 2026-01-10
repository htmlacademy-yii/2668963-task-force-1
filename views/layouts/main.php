<?php
    use yii\bootstrap5\Html;
    use yii\web\User;

    if (!Yii::$app->user->isGuest) {
        $user = Yii::$app->user->identity;
        $username = $user->name;
        echo date('Y-m-d H:i:s'); echo ' | '; echo $user?->id; echo ' | '; echo $user?->email; 
    }
    // $user = Yii::$app->user->identity;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Taskforce</title>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/style.css') ?>">
    <style>
        .response-card.deny{
            filter: brightness(0.5);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.9/dist/autoComplete.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.9/dist/css/autoComplete.min.css">

    <script src="https://api-maps.yandex.ru/2.1/?apikey=<?= getenv('API_MAPS_KEY') ?>&lang=ru_RU" type="text/javascript">
    </script>
</head>
<body>
    <header class="page-header">
        <?php  if (Yii::$app->controller->route == 'signup/index'): ?>
            <nav class="main-nav">
                <a href='/' class="header-logo">
                    <img class="logo-image" src="<?= Yii::getAlias('@web/img/logotype.png') ?>" width=227 height=60 alt="taskforce">
                </a>
            </nav>
        <?php else: ?>
            <nav class="main-nav">
                <a href='/' class="header-logo">
                    <img class="logo-image" src="<?= Yii::getAlias('@web/img/logotype.png') ?>" width=227 height=60 alt="taskforce">
                </a>
                <div class="nav-wrapper">
                    <ul class="nav-list">
                        <li class="list-item"> <!--list-item--active-->
                            <a href="/task" class="link link--nav" >Новое</a>
                        </li>
                        <li class="list-item">
                            <a href="/mytask" class="link link--nav">Мои задания</a>
                        </li>
                        <?php if ($user->role === 'customer') { ?>
                            <li class="list-item">
                                <a href="/add" class="link link--nav">Создать задание</a>
                            </li>
                        <?php }; ?>
                        <li class="list-item">
                            <a href="/profile" class="link link--nav">Настройки</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="user-block">
                <a href="#">
                    <img class="user-photo" src="<?= $user->avatar; ?>" width="55" height="55" alt="Аватар">
                </a>
                <div class="user-menu">
                    <p class="user-name"><?= $username; ?></p>
                    <div class="popup-head">
                        <ul class="popup-menu">
                            <li class="menu-item">
                                <a href="/profile" class="">Настройки</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="link">Связаться с нами</a>
                            </li>
                            <li class="menu-item">
                                <?= Html::a('Выйти', ['/site/logout'], ['data-method' => 'post',]) ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </header>
    

<div class="main-container">

    <?=$content;?>

</div>

<!-- <footer>123</footer> -->
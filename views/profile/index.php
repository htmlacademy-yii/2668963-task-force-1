<?php

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
    // var_dump($model->category_ids);
?>

<style>
    .fixedMenu {
        position: fixed;
    }
    #forms{
        display: flex;
        flex-direction: column;

    }
    #user-email{
        opacity: 0.3;
        cursor: default;
    }
    #user-category_ids label{
        display: block;
        margin-top: 10px;
    }
    #secure{
        display: block;
        width: 100%;
    }
</style>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <div class="fixedMenu">
            <h3 class="head-main head-task">Настройки</h3>
            <ul class="side-menu-list">
                <li class="side-menu-item">
                    <a href="#profile"  class="link link--nav">Мой профиль</a>
                </li>
                <li class="side-menu-item">
                    <a href="#secure" class="link link--nav">Безопасность</a>
                </li>
            </ul>
        </div>
    </div>

    <div id="forms">
        <div id="profile" class="my-profile-form">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

                <img src="/<?= $model->avatar ?: 'img/man-glasses.png' ?>"
                    width="80" style="border-radius:10px">

                <?= $form->field($model, 'avatarFile')->fileInput() ?>

                <?= $form->field($model, 'name')->textInput() ?>

                <?= $form->field($model, 'email')->textInput(['readonly' => true]) ?>

                <?= $form->field($model, 'birthday')->input('date', [
                    'value' => date('Y-m-d', strtotime($model->birthday))
                ]) ?>

                <?= $form->field($model, 'phone')->textInput() ?>

                <?= $form->field($model, 'telegram')->textInput() ?>

                <?= $form->field($model, 'about')->textarea(['rows' => 4]) ?>

                <?php if ($model->role === 'performer'): ?>
                    <?= $form->field($model, 'category_ids')->checkboxList(
                        ArrayHelper::map($categories, 'id', 'name')
                    ) ?>
                <?php endif; ?>


                <?= Html::submitButton('Сохранить', ['class' => 'button button--blue']) ?>

            <?php ActiveForm::end(); ?>

        </div>

        <div id="secure" class="my-profile-form password-form">


        <h2>Смена пароля</h2>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin([
            'action' => ['profile/change-password'],
            'method' => 'post'
        ]) ?>

        <div class="half-wrapper">
            <?= $form->field($passwordForm, 'password')->passwordInput() ?>
        </div>

        <div class="half-wrapper">
            <?= $form->field($passwordForm, 'password_repeat')->passwordInput() ?>
        </div>

        <?= Html::submitButton('Сбросить пароль', ['class' => 'button button--orange']) ?>

        <?php ActiveForm::end(); ?>


        </div>
    </div>
    
</main>
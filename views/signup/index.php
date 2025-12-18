<?php
/** @var yii\web\View $this */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
    use yii\widgets\ActiveField;
    use yii\widgets\ActiveForm; 
?>

<?php
    // print_r($model->getAttributeLabel('name'));
    // var_dump($_POST);
?>

<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">

            <div class="registration">
                <?php $form = ActiveForm::begin(['options' => ['class' => 'registration__form']]); ?>

                <h3 class="head-main head-task">Регистрация нового пользователя</h3>

                <?= $form->field($model, 'name')
                    ->textInput(['placeholder' => 'Name', 'class' => 'field__input input input--big']) ?>
                
                <div class="half-wrapper">
                    <?= $form->field($model, 'email')
                        ->textInput(['placeholder' => 'Email']) ?>
                    
                    <?= $form->field($model, 'city_id')->dropDownList(
                        ArrayHelper::map($cities, 'id', 'name'), 
                        [
                            'class' => 'select',
                            'id' => 'town-user',
                        ])  
                    ?>
                </div>

                <div class="half-wrapper">
                    <?= $form->field($model, 'password')
                        ->passwordInput(['placeholder' => 'Password']) ?>
                </div>

                <div class="half-wrapper">
                    <?= $form->field($model, 'password_repeat')
                        ->passwordInput(['placeholder' => 'Repeat Password']) ?>
                </div>

                <?= $form->field($model, 'role')
                    ->checkbox(['label' => 'я собираюсь откликаться на заказы', 'value' => 'performer', 'uncheck' => 'customer'])?>

                <?= Html::input('submit', null, 'Создать аккаунт', ['class'=> 'button button--blue']) ?>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</main>

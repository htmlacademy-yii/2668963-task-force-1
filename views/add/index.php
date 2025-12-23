<?php
    use yii\widgets\ActiveForm;
    // var_dump($categories);
    // var_dump($_FILES);
?>

<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
        <h3 class="head-main head-main">Публикация нового задания</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'task-form',
            'options' => ['enctype' => 'multipart/form-data'],
            'enableAjaxValidation' => true,
        ]); ?>

        <?= $form->field($task, 'title')->textInput()->label('Опишите суть работы [title]') ?>

        <?= $form->field($task, 'description')->textarea()->label('Подробности задания [desc]') ?>

        <?= $form->field($task, 'category_id')->dropDownList($categoryList)->label('Категория [category]') ?>

        <?= $form->field($task, 'location')->textInput(['class' => 'location-icon'])->label('Локация') ?>
        
        <div class="half-wrapper">
            <?= $form->field($task, 'budget')->textInput(['class' => 'budget-icon'])->label('Бюджет') ?>

            <?= $form->field($task, 'deadline')->input('date')->label('Срок исполнения') ?>
        </div>

        <?= $form->field($task, 'files[]')->fileInput([
            'multiple' => true,
            // 'class' => 'new-file',
            ])->label('Файл') ?>

        <input type="submit" class="button button--blue" value="Опубликовать">

        <?php ActiveForm::end(); ?>

    </div>
</main>
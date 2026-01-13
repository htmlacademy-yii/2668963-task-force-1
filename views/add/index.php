<?php
    use yii\widgets\ActiveForm;
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

        <?= $form->field($task, 'location')->textInput([
            'class' => 'location-icon', 
            'id' => 'autoComplete', 
            'autocomplete' => 'off'])
            ->label('Локация') ?>

        <?= $form->field($task, 'city_id')
            ->hiddenInput(['id' => 'city_id'])
            ->label(false) ?>

        
        <div class="half-wrapper">
            <?= $form->field($task, 'budget')->textInput(['class' => 'budget-icon'])->label('Бюджет') ?>

            <?= $form->field($task, 'deadline')->input('date')->label('Срок исполнения') ?>
        </div>

        <?= $form->field($task, 'files[]')->fileInput([
            'multiple' => true,
            ])->label('Файл') ?>

        <input type="submit" class="button button--blue" value="Опубликовать">

        <?php ActiveForm::end(); ?>
        
    <script>
        const geoAutoComplete = new autoComplete({
            selector: '#autoComplete',
            placeHolder: 'Введите город...',
            data: {
                src: async (query) => {
                    const response = await fetch('/location/search?q=' + query);
                    return await response.json();
                },
                keys: ['label'],
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: (event) => {
                        const city = event.detail.selection.value;

                        geoAutoComplete.input.value = city.label;
                        document.getElementById('city_id').value = city.id;
                    }
                }
            }
        });
    </script>

    <script>
        document.getElementById('autoComplete').addEventListener('input', () => {
            document.getElementById('city_id').value = '';
        });
    </script>

    </div>
</main>
<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// foreach ($withoutPerformer as $wP) {
//     echo ($wP->title);
// }
var_dump($filterForm->attributes);

// die;
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php if ($tasks):?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-card">
                    <div class="header-task">
                        <a  href="#" class="link link--block link--big"><?= $task->title; ?></a>
                        <p class="price price--task"><?= $task->budget; ?> ₽</p>
                    </div>
                    <p class="info-text"><span class="current-time"><?= $task->date_add; ?></span></p>
                    <p class="task-text"><?= $task->description; ?>
                    </p>
                    <div class="footer-task">
                        <p class="info-text town-text"><?= $task->city->name; ?></p>
                        <p class="info-text category-text"><?= $task->category->name; ?></p>
                        <a href="/task/view/<?= $task->id; ?>" class="button button--black">Смотреть Задание</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>По вашему запросу задания не найдены</p>
        <?php endif; ?>
        <div class="pagination-wrapper">
            <ul class="pagination-list">
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">1</a>
                </li>
                <li class="pagination-item pagination-item--active">
                    <a href="#" class="link link--page">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">3</a>
                </li>
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => 'task',
                    'id' => 'search-form',
                    'fieldConfig' => [
                        'template' => "{input}",
                    ]
                 ]); ?>

                    <h4 class="head-card">Категории</h4>
                    <div class="filter-section">
                        <div class="checkbox-wrapper">
                            <?php foreach ($availableCategories as $category): ?>
                                <?php $id = 'cat-' . $category->id; ?>
                                <label class="control-label" for="<?= $id ?>">
                                    <?= Html::checkbox(
                                        'categories[]',
                                        in_array($category->id, $filterForm->categories),
                                        [
                                            'value' => $category->id,
                                            'id' => $id,
                                            'class' => 'checkbox-input',
                                        ]
                                    ) ?>
                                    <?= Html::encode($category->name) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                
                    <h4 class="head-card">Дополнительно</h4>
                    <div class="filter-section">
                        <div class="checkbox-wrapper">
                            <label class="control-label" for="without-performer">
                                <?= Html::checkbox(
                                    'withoutPerformer',
                                    $filterForm->withoutPerformer,
                                    [
                                        'id' => 'without-performer',
                                        'class' => 'checkbox-input',
                                    ]
                                ) ?>
                                Без исполнителя
                            </label>
                        </div>
                    </div>

                    <h4 class="head-card">Период</h4>
                    <div class="filter-section">
                        <?= $form->field($filterForm, 'creationTime')->dropDownList(
                        [
                            1 => '1 час',
                            12 => '12 часов',
                            24 => '24 часа',
                        ], 
                        [
                            'class' => 'select',
                            'name' => 'creationTime',
                            'id' => 'creationTime'
                        ]) ?>
                    </div>


                    <div class="filter-submit">
                        <?= Html::input('submit', null, 'Искать', [
                            'class' => 'button button--blue',
                        ]) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>

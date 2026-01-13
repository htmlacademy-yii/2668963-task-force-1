<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php if ($tasks):?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-card">
                    <div class="header-task">
                        <a  href="/task/view/<?= $task->id; ?>" class="link link--block link--big"><?= strip_tags($task->title); ?></a>
                        <p class="price price--task"><?= strip_tags($task->budget); ?> ₽</p>
                    </div>
                    <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->date_add); ?></span></p>
                    <p class="task-text"><?= strip_tags($task->description); ?>
                    </p>
                    <div class="footer-task">
                        <p class="info-text town-text"><?= strip_tags($task->city->name); ?></p>
                        <p class="info-text category-text"><?= strip_tags($task->category->name); ?></p>
                        <a href="/task/view/<?= $task->id; ?>" class="button button--black">Смотреть Задание</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>По вашему запросу задания не найдены</p>
        <?php endif; ?>



<?php if ($pagination->pageCount > 1): ?>
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'options' => ['class' => 'pagination-list'],
        'linkContainerOptions' => ['class' => 'pagination-item'],
        'linkOptions' => ['class' => 'link link--page'],
        'activePageCssClass' => 'pagination-item--active',
        'disabledPageCssClass' => 'mark',
        'prevPageLabel' => '',
        'nextPageLabel' => '',
        'prevPageCssClass' => 'pagination-item mark',
        'nextPageCssClass' => 'pagination-item mark',
        'maxButtonCount' => 5,
    ]) ?>
<?php endif; ?>



    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => 'index',
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
                            0 => '-',
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

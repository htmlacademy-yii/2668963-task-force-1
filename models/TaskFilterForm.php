<?php

namespace app\models;

use yii\base\Model;

class TaskFilterForm extends Model
{
    public array $categories = [];
    public bool $withoutPerformer = false;
    public int $creationTime = 1;


    public function rules(): array
    {
        return [
            [['categories'], 'safe'],       
            ['categories', 'each', 'rule' => ['integer']],
            ['withoutPerformer', 'boolean'],
            ['creationTime', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'withoutPerformer' => 'Без исполнителя',
            'creationTime' => 'Период',
        ];
    }
}
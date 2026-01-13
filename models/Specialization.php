<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "specializations".
 *
 * @property int $id
 * @property string $title
 * @property string $code
 *
 * @property Users[] $users
 */
class Specialization extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'specializations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['performer_id', 'category_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'performer_id' => 'performer_id',
            'category_id' => 'category_id',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'performer_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id']);
    }

}

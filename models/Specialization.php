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
        // return [
        //     [['title', 'code'], 'required'],
        //     [['title', 'code'], 'string', 'max' => 128],
        //     [['title'], 'unique'],
        //     [['code'], 'unique'],
        // ];
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

}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $date_add
 * @property string $role
 * @property string|null $birthday
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $about
 * @property string|null $avatar
 * @property string|null $phone
 * @property string|null $telegram
 * @property int $city_id
 * @property int|null $specialization_id
 *
 * @property Cities $city
 * @property Offers[] $offers
 * @property Reviews[] $reviews
 * @property Reviews[] $reviews0
 * @property Specializations $specialization
 * @property Tasks[] $tasks
 * @property Tasks[] $tasks0
 */
class User extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */

    public $password_repeat;

    public function rules()
    {
        return [
            [['birthday', 'about', 'avatar', 'phone', 'telegram', 'specialization_id'], 'default', 'value' => null],
            [['date_add', 'birthday'], 'safe'],
            [['role', 'name', 'email', 'city_id'], 'required'],
            [['email'], 'email'],
            [['password', 'password_repeat'], 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            [['city_id', 'specialization_id'], 'integer'],
            [['role', 'name', 'email', 'about', 'telegram'], 'string', 'max' => 128],
            [['password', 'avatar'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['specialization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specializations::class, 'targetAttribute' => ['specialization_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_add' => 'Date Add',
            'role' => 'Role',
            'birthday' => 'Birthday',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'about' => 'About',
            'avatar' => 'Avatar',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'city_id' => 'City Name',
            'specialization_id' => 'Specialization ID',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Offers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(Reviews::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Specialization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialization()
    {
        return $this->hasOne(Specializations::class, ['id' => 'specialization_id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Tasks::class, ['performer_id' => 'id']);
    }

}

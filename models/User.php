<?php

namespace app\models;

use HtmlAcademy\enums\OfferStatus;
use Yii;
use yii\web\IdentityInterface;

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
class User extends \yii\db\ActiveRecord implements IdentityInterface
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
    public $avatarFile;
    public $category_ids = [];


    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
        return static::findOne(['access_token' => $token]);

    }
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->auth_key === $authKey;

    }

    public function rules()
    {
        return [

            [['birthday', 'about', 'avatar', 'phone', 'telegram'], 'default', 'value' => null],

            [['date_add', 'birthday', 'category_ids'], 'safe'],

            ['birthday', 'compare', 'compareValue' => date('Y-m-d', strtotime('-18 years')), 'operator' => '<=', 'message' => 'Вам должно быть не менее 18 лет'],


            [['role', 'name', 'email', 'city_id'], 'required', 'on' => 'signup'],
            [['password', 'password_repeat'], 'required', 'on' => 'signup'],

            [['email'], 'email'],
            [['email'], 'unique', 'on' => 'signup'],

            [['password'], 'string', 'max' => 255],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],

            [['role', 'name', 'email', 'about', 'telegram'], 'string', 'max' => 128],
            ['phone', 'match', 'pattern' => '/^\d{10,15}$/', 'message' => 'Телефон должен содержать только цифры, от 10 до 15 символов.'],
            ['telegram', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => 'Телеграм может содержать только латинские буквы, цифры и _'],
            ['about', 'string', 'max' => 255, 'tooLong' => 'Максимум 255 символов'],


            [['city_id'], 'integer'],

            ['avatar', 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2 * 1024 * 1024],

            [['city_id'], 'exist', 'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id']
            ],
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
        return $this->hasMany(Offer::class, ['performer_id' => 'id']);
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
        return $this->hasMany(Review::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Specialization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpecializations()
    {
        return $this->hasMany(Specialization::class, ['performer_id' => 'id']);
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
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->birthday) && strlen($this->birthday) === 10) {
                $this->birthday .= ' 00:00:00';
            }
            return true;
        }
        return false;
    }

    public function ratingCalculator($user)
    {
        $offersQuery = $user->getReviews0()->where(['performer_id' => $user->id]);

        if ($offersQuery->exists()) {
            $sumScore = $offersQuery->sum('score') ?? null;
        } else {
            return null;
        }

        $ratio = $offersQuery->count() + $user->getOffers()->where(['status' => OfferStatus::FAILED])->count();

        return $sumScore/$ratio;
    }
}

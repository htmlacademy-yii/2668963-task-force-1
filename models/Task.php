<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string|null $date_add
 * @property string $title
 * @property string $description
 * @property int $category_id
 * @property string $location
 * @property int $budget
 * @property string $deadline
 * @property string|null $status
 * @property int $city_id
 * @property int $customer_id
 * @property int|null $performer_id
 *
 * @property Categories $category
 * @property Cities $city
 * @property Users $customer
 * @property Files[] $files
 * @property Offers[] $offers
 * @property Users $performer
 * @property Reviews[] $reviews
 */
class Task extends \yii\db\ActiveRecord
{
    /** @var UploadedFile[] */
    public array $files = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'performer_id'], 'default', 'value' => null],
            [['date_add', 'deadline'], 'safe'],
            [['title', 'description', 'category_id', 'location', 'budget', 'deadline', 'city_id', 'customer_id'], 'required'],
            [['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 5],
            [['category_id', 'city_id', 'customer_id', 'performer_id'], 'integer'],
            [['deadline'], 'validateFutureDate'],
            [['budget'], 'integer', 'min' => 1],
            [['title', 'location', 'status'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 500],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['performer_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
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
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'location' => 'Location',
            'budget' => 'Budget',
            'deadline' => 'Deadline',
            'status' => 'Status',
            'city_id' => 'City ID',
            'customer_id' => 'Customer ID',
            'performer_id' => 'Performer ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Offers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(User::class, ['id' => 'performer_id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['task_id' => 'id']);
    }

    public function validateFutureDate($attribute)
    {
        if (!$this->$attribute) {
            return;
        }

        if (strtotime($this->$attribute) <= time()) {
            $this->addError(
                $attribute,
                'Дата должна быть больше текущей'
            );
        }
    }


}

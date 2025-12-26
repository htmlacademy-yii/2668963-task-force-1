<?php

declare(strict_types=1);
namespace app\models;

use Yii;
use yii\web\UploadedFile;
use HtmlAcademy\actions\Cancel;
use HtmlAcademy\actions\Respons;
use HtmlAcademy\actions\Done;
use HtmlAcademy\actions\Reject;
use HtmlAcademy\enums\OfferStatus;
use HtmlAcademy\enums\TaskActions;
use HtmlAcademy\enums\TaskStatus;
use HtmlAcademy\exceptions\TaskActionsException;
use HtmlAcademy\exceptions\TaskStatusException;

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

    /**
     * Принятие оффера заказчиком
     */
    public function accept(int $userId, int $offerId): bool
    {
        if (!$this->canBeChangedBy($userId)) {
            return false;
        }
        if ($this->status !== TaskStatus::NEW->value) {
            return false;
        }


        $offer = Offer::findOne([
            'id' => $offerId,
            'task_id' => $this->id
        ]);

        if (!$offer) {
            return false;
        }

        $this->performer_id = $offer->performer_id;
        $this->status = TaskStatus::INPROGRESS->value;

        $offer->status = OfferStatus::CONFIRM->value;
        $offer->save(false);
        
        Offer::updateAll(
            ['status' => OfferStatus::DENY->value],
            ['and', ['task_id' => $this->id], ['!=', 'id', $offerId]]
        );


        return $this->save(false);
    }

    /**
     * Отказ оффера заказчиком
     */    
    public function reject($userId, $offerId): bool
    {
        if (!$this->canBeChangedBy($userId)) {
            return false;
        }

        $offer = Offer::findOne([
            'id' => $offerId,
            'task_id' => $this->id
        ]);

        if (!$offer) {
            return false;
        }

        $offer->status = OfferStatus::DENY->value;
        return $offer->save(false);
    }

    /**
     *  Отмена задания заказчиком
     */    
    public function cancel($userId, $taskId): bool
    {
        if (!$this->canBeChangedBy($userId)) {
            return false;
        }

        $task = Task::findOne([
            'id' => $taskId,
        ]);

        if (!$task) {
            return false;
        }

        Offer::updateAll(
            ['status' => OfferStatus::DENY->value],
            ['and', ['task_id' => $this->id]]
        );
        
        $task->status = TaskStatus::CANCELED->value;
        return $task->save(false);
    }



    private function canBeChangedBy($userId): bool
    {
        return $this->customer_id === $userId
            && $this->status === TaskStatus::NEW->value;
    }


}

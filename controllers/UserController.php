<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\User;
use HtmlAcademy\enums\OfferStatus;

class UserController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        return $this->render('index', [

        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionProfile()
    {
        if ($id = \Yii::$app->user->getId()) {
            $user = User::findOne($id);

            print($user->email);
        }
    }

    public function actionView($id)
    {
        if (Yii::$app->user->identity->role === 'customer') {
            return $this->redirect(['/']);
        }
        $user = User::findOne($id);

        if ($user === null) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        $allSpecializations = [];

        foreach ($user->specializations as $spec) {
            $allSpecializations = array_merge($allSpecializations, $spec->category);
        }

        $isPerformerFree = $user->getOffers()->where(['status' => 'confirm'])->exists();

        $completedOffers = $user->getOffers()
            ->where(['status' => 'completed'])
            ->count();
        
        $failedOffers = $user->getOffers()
            ->where(['status' => 'failed'])
            ->count();

        $reviews = $user->getReviews0()
            ->where(['performer_id' => $user->id])
            ->all();

        
        $rating = (new \yii\db\Query())
            ->select([
                'user_id',
                'rating',
                'position' => 'RANK() OVER (ORDER BY rating DESC)'
            ])
            ->from([
                'sub' => (new \yii\db\Query())
                    ->select([
                        'users.id AS user_id',
                        'rating' => 'CASE WHEN (COUNT(reviews.id) + COUNT(offers.id)) = 0 THEN 0 ELSE COALESCE(SUM(reviews.score),0) / (COUNT(reviews.id) + COUNT(offers.id)) END'
                    ])
                    ->from('users')
                    ->leftJoin('reviews', 'reviews.performer_id = users.id')
                    ->leftJoin('offers', 'offers.performer_id = users.id AND offers.status = :failed', [':failed' => OfferStatus::FAILED])
                    ->groupBy('users.id')
            ])
            ->where(['user_id' => $user->id])
            ->one();

        return $this->render('view', [
            'user' => $user,
            'allSpecializations' => $allSpecializations,
            'isPerformerFree' => $isPerformerFree,
            'completedOffers' => $completedOffers,
            'failedOffers' => $failedOffers,
            'reviews' => $reviews,
            'rating' => $rating,

        ]);
    }
}
<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $createTask = $auth->createPermission('createTask');
        $createTask->description = 'Создать задания';
        $auth->add($createTask);

        $deleteTask = $auth->createPermission('deleteTask');
        $deleteTask->description = 'Удалить задания';
        $auth->add($deleteTask);

        $completeTask = $auth->createPermission('completeTask');
        $completeTask->description = 'Завершить задание';
        $auth->add($completeTask);

        
        $respondTask = $auth->createPermission('respondTask');
        $respondTask->description = 'Отклик на задание';
        $auth->add($respondTask);

        $refuseTask = $auth->createPermission('refuseTask');
        $refuseTask->description = 'Отказ от задания';
        $auth->add($refuseTask);



        $performer = $auth->createRole('performer');
        $auth->add($performer);
        $auth->addChild($performer, $respondTask);
        $auth->addChild($performer, $refuseTask);

        $customer = $auth->createRole('customer');
        $auth->add($customer);
        $auth->addChild($customer, $createTask);
        $auth->addChild($customer, $deleteTask);
        $auth->addChild($customer, $completeTask);
    }
}
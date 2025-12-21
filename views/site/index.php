<?php

use app\models\Category;
use HtmlAcademy\exceptions\TaskActionsException;
use HtmlAcademy\exceptions\TaskStatusException;
use HtmlAcademy\helpers\CsvToSqlConverter;

require_once('../vendor/autoload.php');


$userId = 10;
$cleanHouse = new HtmlAcademy\core\Task(customerId: 10, performerId: 30);
$action = 'done';


// $rows = Yii::$app->db->createCommand('SELECT * FROM categories')->queryAll();
// foreach ($rows as $row) {
//     echo $row['id'] . ' - ' . $row['name'] . "<br>";
// }

$rows = Category::findOne(1);
foreach ($rows as $row) {
    // var_dump($row);
}
echo "<br>";
echo "<br>";


try {
    $cleanHouse->setStatus(status: 'in_progress');
} catch (TaskStatusException $e) {
    error_log("Ошибка статуса: " . $e->getMessage());
    echo("Ошибка статуса: " . $e->getMessage());
}
try {
    $status = $cleanHouse->getStatus();
    // var_dump($cleanHouse->statusGetName($status));
} catch (TaskStatusException $e) {
    error_log("Ошибка статуса: " . $e->getMessage());
    echo("Ошибка статуса: " . $e->getMessage());
}

// var_dump($action->getCode());

try {
    $nextStatus = $cleanHouse->getNextStatus($action);
    // var_dump($cleanHouse->statusGetName(status: $nextStatus));
} catch (TaskActionsException $e) {
    error_log("Ошибка действия: " . $e->getMessage());
    echo("Ошибка действия: " . $e->getMessage());
}


try {
    $availableAction = $cleanHouse->getAvailableAction();
    foreach ($availableAction as $action) {
        // var_dump($action->checkPermissions($cleanHouse->customerId, $cleanHouse->performerId, $userId));
    }
} catch (TaskActionsException $e) {
    error_log("Ошибка действия: " . $e->getMessage());
    echo("Ошибка действия: " . $e->getMessage());
}

// CsvToSqlConverter::convert("../data/categories.csv", "../");
?>


<?php

require_once('vendor/autoload.php');

$userId = 10;
$cleanHouse = new HtmlAcademy\core\Task(customerId: 10, performerId: 30);
$action = new HtmlAcademy\actions\Cancel;

$cleanHouse->setStatus(status: 'in_progress');
var_dump($cleanHouse->statusMatchingTranslate(status: $cleanHouse->getStatus()));


// $nextStatus = $cleanHouse->getNextStatus($action);

// if ($nextStatus) {
//     var_dump($cleanHouse->statusMatchingTranslate(status: $nextStatus));
// } else {
//     echo('Нет доступных статусов');
// }



$availableAction = $cleanHouse->getAvailableAction($userId);

if ($availableAction) {
    foreach ($availableAction as $action) {
        var_dump($action->checkPermissions($cleanHouse->customerId, $cleanHouse->performerId, $userId));
    }
} else {
    echo('Нет доступных действий');
}
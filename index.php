<?php

require_once('Task.php');

$cleanHouse = new Task(customerId: 10, performerId: 30);

$cleanHouse->setStatus(status: 'new');
var_dump($cleanHouse->statusMatchingTranslate(status: $cleanHouse->getStatus()));


$nextStatus = $cleanHouse->getNextStatus(action: 'action_cancel');

if ($nextStatus) {
    var_dump($cleanHouse->statusMatchingTranslate(status: $nextStatus));
} else {
    echo('Нет доступных статусов');
}



$availableAction = $cleanHouse->getAvailableAction();

if ($availableAction) {
    foreach ($availableAction as $action) {
        var_dump($cleanHouse->actionMatchingTranslate(action: $action));
    }
} else {
    echo('Нет доступных действий');
}

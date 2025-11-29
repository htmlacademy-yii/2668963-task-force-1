<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;

abstract class Action
{

    abstract public function getName(): string;
    abstract public function getCode(): string;
    abstract public function checkPermissions(int $customerId, int $performerId, int $userId): bool;

}
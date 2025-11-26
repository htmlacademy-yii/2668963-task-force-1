<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;

class Reject extends Action
{

    public function getName(): string
    {
        return "Отказаться";
    }
    public function getCode(): string
    {
        return "action_reject";
    }
    public function checkPermissions($customerId, $performerId, $userId): bool
    {
        return $userId === $performerId && $userId !== $customerId;
    }

}
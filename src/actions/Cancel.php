<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;

class Cancel extends Action
{

    public function getName(): string
    {
        return "Отменить";
    }
    public function getCode(): string
    {
        return "action_cancel";
    }
    public function checkPermissions($customerId, $performerId, $userId): bool
    {
        return $userId === $customerId && $userId !== $performerId;
    }

}
<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;

class Respons extends Action
{

    public function getName(): string
    {
        return "Откликнуться";
    }
    public function getCode(): string
    {
        return "action_response";
    }
    public function checkPermissions($customerId, $performerId, $userId): bool
    {
        return $userId === $performerId && $userId !== $customerId;
    }

}
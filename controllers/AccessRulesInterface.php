<?php

namespace app\controllers;

interface AccessRulesInterface
{
    /**
     * Возвращает массив правил доступа для контроллера.
     *
     * @return array Массив правил доступа.
     */
    public function getAccessRules(): array;
}

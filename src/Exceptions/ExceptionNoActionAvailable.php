<?php

namespace Taskforce\Exceptions;

/**
 * Класс исключения для случая, когда нет доступных действий.
 */
class ExceptionNoActionAvailable extends \Exception
{
    /**
     * Сообщение об ошибке, которое будет выведено, если возникнет это исключение.
     */
    protected $message = 'Нет доступных действий';
}

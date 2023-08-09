<?php

namespace Taskforce\Exceptions;

/**
 * Класс исключения для случая, когда статус задания не существует.
 */
class ExceptionStatusNotExist extends \Exception
{
    /**
     * Сообщение об ошибке, которое будет выведено, если возникнет это исключение.
     */
    protected $message = 'Несуществующий статус задания!';
}

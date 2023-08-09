<?php

namespace Taskforce\Exceptions;

/**
 * Класс исключения для случая, когда запрашиваемое действие не существует.
 */
class ExceptionActionNotExist extends \Exception
{
    /**
     * @var string Сообщение об ошибке, связанной с несуществующим действием.
     */
    protected $message = 'Несуществующее действие!';
}

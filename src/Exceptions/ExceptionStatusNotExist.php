<?php

namespace Taskforce\Exceptions;

class ExceptionStatusNotExist extends \Exception
{
    protected $message = 'Несуществующий статус задания!';
}

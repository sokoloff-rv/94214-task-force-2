<?php

namespace Taskforce\Exceptions;

class ExceptionActionNotExist extends \Exception
{
    protected $message = 'Несуществующее действие!';
}

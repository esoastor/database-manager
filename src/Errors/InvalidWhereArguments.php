<?php
namespace Database\Errors;

class InvalidWhereArguments extends \Exception
{
    public $message = 'Invalid \'where\' statement arguments, must be string type';
}
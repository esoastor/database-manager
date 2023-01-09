<?php
namespace Database\Errors;

class InvalidArguments extends \Exception
{
    public $message = 'Invalid \'where\' statement arguments';
}
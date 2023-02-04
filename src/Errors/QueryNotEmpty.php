<?php
namespace Database\Errors;

class QueryNotEmpty extends \Exception
{
    public $message = 'You have query that have been not executed. Dont forget to ->execute() your querry.';
}
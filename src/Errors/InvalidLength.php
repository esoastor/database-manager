<?php
namespace Database\Errors;

class InvalidLength extends \Exception
{
    public function __construct(int $maximumLength)
    {
        $this->message("Field length is too big. Maximum length is $maximumLength\n");
    }
}
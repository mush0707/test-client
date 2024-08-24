<?php

namespace App\Http\Responses\Messages;

class ErrorsMessage
{
    public bool $success;
    public array $errors;

    public function __construct(array $errors)
    {
        $this->success = false;
        if ($errors) {
            $this->errors = $errors;
        }
    }
}

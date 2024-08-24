<?php

namespace App\Http\Responses\Messages;

class SuccessMessage
{
    public $data;
    public string $time;
    public string|null $message = null;

    public function __construct($data, string $message = null)
    {
        $this->data = $data;
        $this->time = microtime(true) - LARAVEL_START;
        $this->message = $message;
    }
}

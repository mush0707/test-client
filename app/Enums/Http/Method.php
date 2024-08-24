<?php

namespace App\Enums\Http;

enum Method: string
{
    case POST = 'post';
    case GET = 'get';
    case DELETE = 'delete';
}

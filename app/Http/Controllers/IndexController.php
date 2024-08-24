<?php

namespace App\Http\Controllers;

use App\Enums\Http\Method;
use App\Services\MainService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        (new MainService())->request(Method::GET, '/api/user', []);
    }
}

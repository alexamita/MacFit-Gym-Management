<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// Base controller class that other controllers will extend
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

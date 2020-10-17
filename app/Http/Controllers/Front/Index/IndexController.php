<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front\Index;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * IndexController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Front\Index
 */
class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requst)
    {
        return view('frontend.index.index');
    }
}

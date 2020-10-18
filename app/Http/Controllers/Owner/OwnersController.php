<?php

declare(strict_types=1);

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * OwnersController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Owner
 */
class OwnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requst)
    {
        //
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

/**
 * Ajax Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Ajax
 */
class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('force.https');
        $this->middleware('site.mode');
        $this->middleware('ajax.request');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

/**
 * Ajax Controller
 *
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Ajax
 */
class AjaxController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('force.https');
        $this->middleware('site.mode');
        $this->middleware('ajax.request');
    }
}

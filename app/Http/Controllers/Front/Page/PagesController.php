<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front\Page;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Page;
use App\Models\System\Role;
use App\Models\System\User;

/**
 * PagesController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Front\Page
 */
class PagesController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('xss.sanitization');
    }


    /**
     * Display page resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $slug = '')
    {
        $site = site(config('app.base_domain'));

        if (filled($slug) && $site->pages()->where('slug', $slug)->exists()) {
            $page = $site->pages()->where('slug', $slug)->first();

            if ($page->status == Page::ACTIVE) {
                return view(
                    'frontend.pages.default',
                    compact('site', 'page')
                );
            }
        }

        abort(404);
    }
}

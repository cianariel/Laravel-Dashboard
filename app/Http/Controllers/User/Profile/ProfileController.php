<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\User\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Role;
use App\Models\System\User;
use App\Rules\SanitizeHtml;
use App\Rules\PhoneRule;

/**
 * ProfileController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\User\Profile
 */
class ProfileController extends BaseController
{
    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard()->user();

        $breadcrumbs = breadcrumbs([
            __('Dashboard')     => [

            ],
            __('Edit Profile')  => [

            ]
        ]);

        return view('user.profile.edit', compact('site', 'breadcrumbs', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }
}

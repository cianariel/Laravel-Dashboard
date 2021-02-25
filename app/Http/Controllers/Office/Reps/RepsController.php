<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Reps;

use App\Http\Controllers\Office\OfficeController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\User;
use App\Models\System\Role;
use \Exception;

/**
 * RepsController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Office\Reps
 */
class RepsController extends OfficeController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();
        $officeUser = office_owner($user);
        $office = $officeUser->offices()->first();

        $breadcrumbs = breadcrumbs([
            __('Dashboard')     => [
                'path'          => route('office.dashboard'),
                'active'        => false
            ],
            __('Reps Database') => [
                'path'          => route('office.reps.index'),
                'active'        => true
            ]
        ]);

        $specialities = [
            'Biotechnology',
            'Disposable Supplies',
            'Durable Medical Equipment',
            'Hospice Care',
            'Imaging Services',
            'Lab Services',
            'Medical Device',
            'Other'
        ];

        $approvedTypes = [
            'approved'      => 'Yes',
            'not_approved'  => 'No'
        ];

        $reps = User::role(Role::USER)->where('status', User::ACTIVE)->paginate(10);

        return view(
            'office.reps.index',
            compact(
                'site',
                'user',
                'office',
                'breadcrumbs',
                'reps',
                'specialities',
                'approvedTypes'
            )
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $username)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();
        $officeUser = office_owner($user);
        $office = $officeUser->offices()->first();

        if($repUser = User::role(Role::USER)->where('username', $username)->where('status', User::ACTIVE)->first()) {

            $breadcrumbs = breadcrumbs([
                __('Dashboard')     => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Reps Database') => [
                    'path'          => route('office.reps.index'),
                    'active'        => false
                ],
                __($repUser->company)   => [
                    'path'          => route('office.reps.show', $repUser),
                    'active'        => true
                ]
            ]);

            return view(
                'office.reps.show',
                compact(
                    'site',
                    'user',
                    'repUser',
                    'breadcrumbs',
                    'office'
                )
            );
        }

        abort(404);
    }
}

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
use Exception;

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

        $reps = User::role(Role::USER)->where('status', User::ACTIVE)->cursor();

        return view('office.reps.index',
            compact(
                'site',
                'breadcrumbs',
                'reps',
                'specialities',
                'approvedTypes'
            )
        );
    }
}

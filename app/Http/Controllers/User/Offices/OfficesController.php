<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Offices;

use App\Http\Controllers\User\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Office;
use Exception;

/**
 * OfficesController
 *
 * @author    Taylor <sykestaylor122@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\User\Offices
 */
class OfficesController extends BaseController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('force.https');
        $this->middleware('auth');
        $this->middleware('role:' . Role::USER);
        $this->middleware('user:' . User::GUARD);

    }

    private function checkCompletedProfile()
    {
        $user = auth()->guard(User::GUARD)->user();

        if ($user->setup_completed != User::SETUP_COMPLETED) {
            flash(__('Unauthorized access.'));
            return redirect('/');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->checkCompletedProfile();

        $user = auth()->guard(User::GUARD)->user();
        $offices = $user->offices()->get(['uuid', 'name', 'label', 'meta_fields']);

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('user.dashboard'),
                'active'    => false
            ],
            __('Offices')     => [
                'path'      => route('user.offices.index'),
                'active'    => true
            ]
        ]);

        return view('user.offices.index',
            compact('breadcrumbs', 'offices')
        );

    }

    /**
     * Add an office page
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $this->checkCompletedProfile();
        $user = auth()->guard(User::GUARD)->user();

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('user.dashboard'),
                'active'    => false
            ],
            __('Offices')     => [
                'path'      => route('user.offices.index'),
                'active'    => false
            ],
            __('Add')     => [
                'path'      => route('user.offices.add'),
                'active'    => true
            ]
        ]);

        $offices = Office::whereDoesntHave('users', function($query) use($user) {
            $query->where('id', $user->id);
        })
        ->where('status', Office::ACTIVE)
        ->get();

        return view('user.offices.add',
            compact('breadcrumbs', 'offices')
        );
    }

    /**
     * Get an office by UDID
     */
    public function getOffice(Request $request, $uuid)
    {
        $this->checkCompletedProfile();
        $user = auth()->guard(User::GUARD)->user();
        $office = Office::where('uuid', $uuid)->first();

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('user.dashboard'),
                'active'    => false
            ],
            __('Offices')     => [
                'path'      => route('user.offices.index'),
                'active'    => false
            ],
            __('Add')     => [
                'path'      => route('user.offices.add'),
                'active'    => false
            ],
            __($office->label)     => [
                'path'      => '#',
                'active'    => true
            ]
        ]);


        return view('user.offices.view',
            compact('breadcrumbs', 'office')
        );
    }

    /**
     * Add an office to my offices
     */
    public function addOffice(Request $request, $uuid)
    {
        $this->checkCompletedProfile();

        $user = auth()->guard(User::GUARD)->user();
        $office = Office::where('uuid', $uuid)->first();
        
        // Check if you already have the office
        if(!$user->offices()->where('uuid', $uuid)->count()){
            $user->offices()->save($office);
        }

        flash (__('Successfully added on office to your offices.'));
        return redirect()->route('user.offices.add');
    }

    /**
     * Search non my offices by keyword
     */
    public function searchNonMyOffices(Request $request)
    {
        $keystring = $request->get('keyword');
        $keywords = explodeBySpace($keystring);
        $user = auth()->guard(User::GUARD)->user();

        $offices = Office::whereDoesntHave('users', function($query) use($user) {
            $query->where('id', $user->id);
        })
        ->where('status', Office::ACTIVE);
        
        if($keywords){
            $offices->where(function($query) use($keywords){
                foreach($keywords as $keyword){
                    $keyword = strtolower($keyword);
                    $qeury = $query->orWhere(function($query) use($keyword) {
                        $query->whereRaw('(json_search(LOWER(offices.meta_fields), \'one\', \'%'. $keyword .'%\', null, \'$.location\')) IS NOT NULL')
                            ->orWhere("offices.name", "LIKE", "%{$keyword}%");
                    });
                }
            });
        }

        $offices = $offices->orderBy('name')->get(['uuid', 'name', 'label', 'meta_fields']);

        return response()->json([
            'status'    => 200,
            'message'   => __('success'),
            'data'    => compact('offices')
        ]);
        
    }

    /**
     * Search my offices by keyword
     */
    public function searchMyOffices(Request $request)
    {
        $keystring = $request->get('keyword');
        $keywords = explodeBySpace($keystring);
        $user = auth()->guard(User::GUARD)->user();

        $offices = $user->offices()
                ->where('status', Office::ACTIVE)
        ;
        
        if($keywords){
            $offices->where(function($query) use($keywords){
                foreach($keywords as $keyword){
                    $keyword = strtolower($keyword);
                    $query->orWhere("offices.name", "LIKE", "%{$keyword}%");
                }
            });
        }

        $offices = $offices->orderBy('name')->get(['uuid', 'name', 'label', 'meta_fields']);

        return response()->json([
            'status'    => 200,
            'message'   => __('success'),
            'data'    => compact('offices')
        ]);
        
    }

    /**
     * Return one office's partial info html from UUID by ajax request
     * 
     * @param  \Illuminate\Http\Request $request
     * @param $uuid
     * @return \Illuminate\Http\Response
     */
    public function getPartialInfoContent(Request $request)
    {
        $id = $request->get('id');
        $office = Office::where('uuid', $id)->first();
        
        $content = view('user.offices.partial-info',
            compact('office')
        )->render();
        
        return response()->json([
            'status'    => 200,
            'message'   => __('success'),
            'data'    => compact('content')
        ]);
    }
}
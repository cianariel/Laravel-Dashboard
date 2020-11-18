<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Folder;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Folder;
use App\Rules\SanitizeHtml;

/**
 * Folders Controller
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\Folder
 */
class FoldersController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->query();
        $perPage = 10;

        if (filled($query) && array_key_exists('per_page', $query)) {
            $perPage = strip_tags(trim($query['per_page']));

            if (is_numeric($perPage)) {
                $perPage = safe_integer($perPage);
            } else {
                $perPage = 10;
                $query['per_page'] = $per_page;
            }
        }

        $folders = Folder::paginate($perPage);

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Folders'       => ['path' => route('admin.folders.index'),           'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.folders.index', compact('folders', 'breadcrumbs', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $visible_types = Folder::VISIBLE_TYPES;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Folders'       => ['path' => route('admin.folders.index'),           'active' => false],
            'Add Folder'    => ['path' => route('admin.folders.create'),          'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.folders.create', compact('visible_types', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'label'     => ['required', 'string', 'alpha_num', 'unique:system.folders,label' ,'max:100', new SanitizeHtml],
                'visible'   => ['required', 'string', Rule::in(Folder::VISIBLE_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $folder = new Folder;
            $folder->name = Str::slug($request->input('label'));
            $folder->label = $request->input('label');
            $folder->visible = $request->input('visible');
            $folder->lock = Folder::UNLOCKED;
            $folder->save();

            flash('Successfully created folder.');
            return redirect()->route('admin.folders.edit', $folder);
        }

        flash('Invaild action');
        return redirect()->route('admin.folders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.folders.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Folder::where('id', $id)->exists()) {
            $lock_types = Folder::LOCK_TYPES;
            $visible_types = Folder::VISIBLE_TYPES;
            $folder = Folder::where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Folders'       => ['path' => route('admin.folders.index'),           'active' => false],
                'Edit Folder'    => ['path' => route('admin.folders.edit', $folder),  'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);
            return view('admin.folders.edit', compact('folder', 'breadcrumbs', 'lock_types', 'visible_types'));
        }

        flash('Folder does not exist.');
        return redirect()->route('admin.folders.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->isMethod('put') && Folder::where('id', $id)->exists()) {
            $rules = [
                'label'     => ['required', 'string', 'alpha_num', 'max:100', new SanitizeHtml],
                'visible'   => ['required', 'string', Rule::in(Folder::VISIBLE_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $folder = Folder::where('id', $id)->firstOrFail();

            $folder->label = $request->input('label');
            $folder->visible = $request->input('visible');
            $folder->saveOrFail();

            flash('Successfully updated folder.');
            return redirect()->route('admin.folders.edit', $folder);
        }

        flash('Invaild action or folder does not exist.');
        return redirect()->route('admin.folders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Folder::where('id', $id)->exists()) {
            $folder = Folder::where('id', $id)->firstOrFail();

            if ($folder->lock == Folder::UNLOCKED) {
                $folder->delete();

                flash('Folder deleted successfully.');
                return redirect()->route('admin.folders.index');
            } else {
                flash('Folder is locked and can not be deleted.');
                return redirect()->route('admin.folders.index');
            }
        }

        flash('Invaild action or folder does not exist.');
        return redirect()->route('admin.folders.index');
    }
}

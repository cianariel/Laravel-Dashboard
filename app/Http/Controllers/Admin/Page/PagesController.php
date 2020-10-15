<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\System\Page;
use App\Models\System\Role;
use App\Models\System\User;
use App\Rules\SanitizeHtml;

/**
 * Admin Pages Resource Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\Page
 */
class PagesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site();
        $query = $request->query();
        $perPage = 10;
        $withTrashed = false;

        if (filled($query)) {
            if ($request->has('per_page')) {
                $perPage = strip_tags(trim($query['per_page']));

                if (is_numeric($perPage)) {
                    $perPage = safe_integer($perPage);
                } else {
                    $perPage = 10;
                    $query['per_page'] = $per_page;
                }
            }

            if ($request->has('with_trashed')) {
                $with_trashed  = strip_tags($query['with_trashed']);

                if ($with_trashed == 'true') {
                    $withTrashed  = true;
                }
            }
        }

        if ($withTrashed === true) {
            $pages = $site->pages()->withTrashed()->paginate($perPage);
        } else {
            $pages = $site->pages()->paginate($perPage);
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Pages'         => ['path' => route('admin.pages.index'),             'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.pages.index', compact('pages', 'breadcrumbs', 'query', 'withTrashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_types = Page::STATUS_TYPES;
        $meta_robots = Page::META_ROBOTS;
        $visible_types = Page::VISIBLE_TYPES;
        $templates = Page::TEMPLATES;
        $users = User::where('status', User::ACTIVE)
                     ->whereHas('roles', function ($query) {
                        $roles = [
                            Role::SUPER_ADMIN,
                            Role::ADMIN,
                            Role::EDITOR,
                            Role::AUTHOR,
                            Role::SUPPORT
                        ];
                        $query->whereIn('name', $roles);
                     })->select(['id', 'first_name', 'last_name'])
                       ->cursor();
        $_users = [];

        foreach ($users as $user) {
            $_users[$user->id] = $user->first_name . ' ' . $user->last_name;
        }

        $users = $_users;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Pages'         => ['path' => route('admin.pages.index'),             'active' => false],
            'Add Page'      => ['path' => route('admin.pages.create'),            'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.pages.create', compact('breadcrumbs', 'status_types', 'meta_robots', 'visible_types', 'templates', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'user'              => ['required', 'integer', 'exists:system.users,id'],
                'title'             => ['required', 'string', 'max:100', new SanitizeHtml],
                'content'           => ['required', 'string'],
                'media'             => ['nullable', 'file', 'image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
                'excerpt'           => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'seo_title'         => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'meta_keywords'     => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'meta_description'  => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'meta_robots'       => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'template'          => ['required', 'string', Rule::in(Page::TEMPLATES)],
                'status'            => ['required', 'string', Rule::in(Page::STATUS_TYPES)],
                'visible'           => ['required', 'string', Rule::in(Page::VISIBLE_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            // Create new page.
            $page = new Page;
            $page->uuid = Str::uuid();
            $page->user_id = $request->input('user');
            $page->title = strip_tags($request->input('title'));
            $page->slug = unique_slug('page', strip_tags($request->input('title')));
            $page->content = $request->input('content');
            $page->excerpt = strip_tags($request->input('excerpt'));
            $page->seo_title = strip_tags($request->input('seo_title'));
            $page->meta_keywords = strip_tags($request->input('meta_keywords'));
            $page->meta_description = strip_tags($request->input('meta_description'));
            $page->meta_robots = strip_tags($request->input('meta_robots'));
            $page->template = $request->input('template');
            $page->status = $request->input('status');
            $page->visible = $request->input('visible');

            // File Uploads
            if ($request->hasFile('media')) {
                $file = $request->file('media');

                $page->addMedia($file)
                     ->toMediaCollection('images');
            }

            // Save
            if ($page->saveOrFail()) {
                $site = site();
                $site->assignPage($page);

                flash('Successfully created page.');
                return redirect()->route('admin.pages.edit', $page);
            }
        }

        flash('Invaild action.');
        return redirect()->route('admin.pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.pages.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Page::where('id', $id)->exists()) {
            $status_types = Page::STATUS_TYPES;
            $meta_robots = Page::META_ROBOTS;
            $visible_types = Page::VISIBLE_TYPES;
            $templates = Page::TEMPLATES;
            $users = User::where('status', User::ACTIVE)
                     ->whereHas('roles', function ($query) {
                        $roles = [
                            Role::SUPER_ADMIN,
                            Role::ADMIN,
                            Role::EDITOR,
                            Role::AUTHOR,
                            Role::SUPPORT
                        ];
                        $query->whereIn('name', $roles);
                     })->select(['id', 'first_name', 'last_name'])
                       ->cursor();
            $_users = [];

            foreach ($users as $user) {
                $_users[$user->id] = $user->first_name . ' ' . $user->last_name;
            }

            $users = $_users;
            $site = site();
            $page = $site->pages()->where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Pages'         => ['path' => route('admin.pages.index'),             'active' => false],
                'Edit Page'     => ['path' => route('admin.pages.edit', $page),       'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);
            return view('admin.pages.edit', compact('page', 'breadcrumbs', 'status_types', 'meta_robots', 'visible_types', 'templates', 'users'));
        }

        flash('Page does not exist.');
        return redirect()->route('admin.pages.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->isMethod('put') && Page::where('id', $id)->exists()) {
            $site = site();
            $page = $site->pages()->where('id', $id)->firstOrFail();

            $rules = [
                'user'              => ['required', 'integer', 'exists:system.users,id'],
                'title'             => ['required', 'string', 'max:100', new SanitizeHtml],
                'content'           => ['required', 'string'],
                'media'             => ['nullable', 'file', 'image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
                'excerpt'           => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'seo_title'         => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'meta_keywords'     => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'meta_description'  => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'meta_robots'       => ['nullable', 'string', 'max:150', new SanitizeHtml],
                'template'          => ['required', 'string', Rule::in(Page::TEMPLATES)],
                'status'            => ['required', 'string', Rule::in(Page::STATUS_TYPES)],
                'visible'           => ['required', 'string', Rule::in(Page::VISIBLE_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $page->user_id = $request->input('user');
            $page->title = strip_tags($request->input('title'));
            $page->content = $request->input('content');
            $page->excerpt = strip_tags($request->input('excerpt'));
            $page->seo_title = strip_tags($request->input('seo_title'));
            $page->meta_keywords = strip_tags($request->input('meta_keywords'));
            $page->meta_description = strip_tags($request->input('meta_description'));
            $page->meta_robots = strip_tags($request->input('meta_robots'));
            $page->template = $request->input('template');
            $page->status = $request->input('status');
            $page->visible = $request->input('visible');

            // File Uploads
            if ($request->hasFile('media')) {
                $file = $request->file('media');

                $page->addMedia($file)
                     ->toMediaCollection('images');
            }

            // Save
            if ($page->saveOrFail()) {
                flash('Successfully updated page.');
                return redirect()->route('admin.pages.edit', $page);
            }
        }

        flash('Invaild action or page does not exist.');
        return redirect()->route('admin.pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Page::where('id', $id)->exists()) {
            $site = site();
            $page = $site->pages()->where('id', $id)->firstOrFail();

            if ($page) {
                $page->status = Page::INACTIVE;
                $page->visible = Page::HIDDEN;
                $page->save();
                $page->delete();
                flash('Successfully deleted page');
                return redirect()->route('admin.pages.index');
            }
        }

        flash('Invaild action or page does not exist.');
        return redirect()->route('admin.pages.index');
    }

    /**
     * Delete the specified media image from page.
     *
     * @param int $id
     * @param int $image
     * @return \Illuminate\Http\Response
     */
    public function deleteMediaImage($id, $image)
    {
        if (Page::where('id', $id)->exists()) {
            $site = site();
            $page = $site->pages()->where('id', $id)->firstOrFail();
            $images = $page->getMedia('images');

            foreach ($images as $index => $_image) {
                if ($index == $image) {
                    $_image->delete();
                    flash('Successfully deleted media image.');
                    return redirect()->route('admin.pages.edit', $page);
                }
            }
        }

        flash('Page or media resource does not exist');
        return redirect()->route('admin.pages.index');
    }

    /**
     * Restores page resource from storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        if ($request->isMethod('put') && Page::where('id', $id)->withTrashed()->exists()) {
            $site = site();
            $page = $site->pages()->where('id', $id)->withTrashed()->firstOrFail();

            if ($page) {
                $page->restore();
            }

            flash('Successfully restored page from trash.');
            return redirect()->route('admin.pages.edit', $page);
        }

        flash('Invaild action or page does not exist anymore.');
        return redirect()->route('admin.pages.index');
    }

    /**
     * Deletes page resource from storage forever
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->isMethod('delete') && Page::where('id', $id)->withTrashed()->exists()) {
            $site = site();
            $page = $site->pages()->where('id', $id)->withTrashed()->firstOrFail();

            if ($page) {
                $images = $page->getMedia('images');

                foreach ($images as $image) {
                    $image->delete();
                }

                $site->unassignPage($page);
                $page->forceDelete();

                flash('Successfully deleted page from trashed forever.');
                return redirect()->route('admin.pages.index');
            }
        }

        flash('Invaild action or page does not exist anymore');
        return redirect()->route('admin.pages.index');
    }
}

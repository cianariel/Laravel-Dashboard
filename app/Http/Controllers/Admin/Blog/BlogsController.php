<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Group;
use App\Models\System\Blog;
use App\Models\System\Setting;
use App\Rules\SanitizeHtml;

/**
 * Blogs Resource Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package App\Http\Controllers\Admin\Blog
 */
class BlogsController extends AdminController
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
                $with_trashed  = strip_tags(trim($query['with_trashed']));

                if ($with_trashed == 'true') {
                    $withTrashed  = true;
                }
            }
        }

        if ($withTrashed === true) {
            $blogs = $site->blogs()->withTrashed()->paginate($perPage);
        } else {
            $blogs = $site->blogs()->paginate($perPage);
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Blogs'         => ['path' => route('admin.blogs.index'),       'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.blogs.index', compact('blogs', 'query', 'breadcrumbs', 'withTrashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $status_types = Blog::STATUS_TYPES;
        $visible_types = Blog::VISIBLE_TYPES;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Blogs'         => ['path' => route('admin.blogs.index'),             'active' => false],
            'Add Blog'      => ['path' => route('admin.blogs.create'),            'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.blogs.create', compact('status_types', 'visible_types', 'breadcrumbs'));
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
                'title'     =>  ['required', 'string', 'max:150', new SanitizeHtml],
                'visible'   =>  ['required', 'string', Rule::in(Blog::VISIBLE_TYPES)],
                'status'    =>  ['required', 'string', Rule::in(Blog::STATUS_TYPES)],
            ];

            $validatedData = $request->validate($rules);

            $title = strip_tags($request->input('title'));
            $slug = unique_slug('blog', $title);
            $blog = new Blog;
            $blog->uuid = Str::uuid();
            $blog->name = $slug;
            $blog->slug = $slug;
            $blog->title = $title;
            $blog->visible = $request->input('visible');
            $blog->status = $request->input('status');

            if ($blog->saveOrFail()) {
                // Assign blog to default site
                $site = site();
                $site->assignBlog($blog);
                $domain = Str::snake(config('app.domain'));

                // Default Blog settings.
                if (Group::where('name', 'blog')->exists()) {
                    $group = Group::where('name', 'blog')->firstOrFail();

                    $settings = [
                        'title'                 => $blog->title,
                        'meta_keywords'         => null,
                        'meta_description'      => null,
                        'meta_robots'           => 'NOINDEX,NOFOLLOW',
                        'tagline'               => null,
                        'language'              => env('DEFAULT_LOCALE'),
                        'posts_per_page'        => 10,
                        'show_newest_first'     => 'true',
                        'show_last_updated'     => 'true'
                    ];

                    if (filled($settings) && $site->hasBlog($blog)) {
                        foreach ($settings as $key => $value) {
                            $key = $domain . '_' . 'blog_' . $blog->name . '_' . $key;

                            if (!Setting::where('key', $key)->exists()) {
                                $setting = new Setting;
                                $setting->key = $key;
                                $setting->value = $value;
                                $setting->status = Setting::LOCKED;
                                $setting->saveOrFail();
                                $setting->assignGroup($group);
                                $blog->assignSetting($setting);
                            }
                        }
                    }
                }

                // Return back to edit
                flash('Successfully created blog.');
                return redirect()->route('admin.blogs.edit', $blog);
            }
        }

        flash('Invaild action.');
        return view('admin.blogs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.blogs.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Blog::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $id)->firstOrFail();

            $status_types = Blog::STATUS_TYPES;
            $visible_types = Blog::VISIBLE_TYPES;

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Blogs'         => ['path' => route('admin.blogs.index'),             'active' => false],
                $blog->title    => ['path' => route('admin.posts.index', $blog),      'active' => false],
                'Edit'          => ['path' => route('admin.blogs.edit', $blog),       'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.blogs.edit', compact('blog', 'breadcrumbs', 'status_types', 'visible_types'));
        }

        flash('Blog does not exists.');
        return redirect()->route('admin.blogs.index');
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
        if ($request->isMethod('put') && Blog::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $id)->firstOrFail();

            $rules = [
                'title'     =>  ['required', 'string', 'max:150', new SanitizeHtml],
                'visible'   =>  ['required', 'string', Rule::in(Blog::VISIBLE_TYPES)],
                'status'    =>  ['required', 'string', Rule::in(Blog::STATUS_TYPES)],
            ];

            $validatedData = $request->validate($rules);

            $blog->title = strip_tags($request->input('title'));
            $blog->visible = $request->input('visible');
            $blog->status = $request->input('status');

            if ($blog->saveOrFail()) {
                flash('Successfully updated blog.');
                return redirect()->route('admin.blogs.edit', $blog);
            }
        }

        flash('Invaild action or blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Blog::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $id)->firstOrFail();

            if ($blog) {
                $blog->status = Blog::INACTIVE;
                $blog->visible = Blog::HIDDEN;
                $blog->save();
                $blog->delete();
            }

            flash('Successfully deleted blog.');
            return redirect()->route('admin.blogs.index');
        }

        flash('Invaild action or blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Restores blog resource from storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        if ($request->isMethod('put') && Blog::where('id', $id)->withTrashed()->exists()) {
            $site = site();
            $blog = $site->blogs()->withTrashed()->where('id', $id)->firstOrFail();

            if ($blog) {
                $blog->restore();
                $blog->deleted_at = null;
                flash('Successfully restored blog from trash.');
                return redirect()->route('admin.blogs.edit', $blog);
            }
        }

        flash('Invaild action or blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Deletes blog item from storage forever
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->isMethod('delete') && Blog::where('id', $id)->withTrashed()->exists()) {
            $site = site();
            $blog = $site->blogs()->withTrashed()->where('id', $id)->firstOrFail();

            if ($blog) {
                $posts = $blog->posts()->withTrashed()->cursor();

                // Delete all posts
                if ($posts) {
                    foreach ($posts as $post) {
                        $blog->unassignPost($post);
                        $post->forceDelete();
                    }
                }

                // Delete all settings
                $settings = $blog->settings()->cursor();

                if ($settings) {
                    foreach ($settings as $setting) {
                        $blog->unassignSetting($setting);
                        $setting->delete();
                    }
                }

                $site->unassignBlog($blog);
                $blog->forceDelete();

                flash('Successfully deleted blog with posts from trashed forever.');
                return redirect()->route('admin.blogs.index');
            }
        }

        flash('Invaild action or blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Shows blog settings resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showSettings(Request $request, $id)
    {
        if (Blog::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $id)->firstOrFail();
            $settings = $blog->settings()->cursor();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Blogs'         => ['path' => route('admin.blogs.index'),             'active' => false],
                $blog->title    => ['path' => route('admin.blogs.show', $blog),       'active' => false],
                'Settings'      => ['path' => route('admin.blogs.settings', $blog),   'active' => true]

            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.blogs.settings.edit', compact('blog', 'settings', 'breadcrumbs'));
        }

        flash('Blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Clear blog settings resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function clearSetting(Request $request, $blog, $setting)
    {
        if (Blog::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $id)->firstOrFail();

            // Clear Setting.
            if ($blog->settings()->where('key', $setting)->exists()) {
                $setting = $blog->settings()->where('key', $setting)->firstOrFail();

                if ($setting->type == Setting::INPUT_FILE) {
                    if (Storage::exists($setting->value)) {
                        $thumb_path = $setting->value;
                        $thumb_path = str_replace('public/uploads/blog/', 'public/uploads/blog/150/', $thumb_path);

                        if (Storage::exists($thumb_path)) {
                            Storage::delete($thumb_path);
                        }
                        Storage::delete($setting->value);
                    }

                    $setting->value = null;
                    $setting->saveOrFail();

                    flash('Successfully removed image from setting');
                    return redirect()->route('admin.blogs.edit', $blog);
                } else {
                    flash('Setting type can not be cleared.');
                    return redirect()->route('admin.blogs.edit', $blog);
                }
            } else {
                flash('No settings key in blog exists');
                return redirect()->route('admin.blogs.edit', $blog);
            }
        }

        flash('Blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Shows blog settings resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(Request $request, $id)
    {
        if ($request->isMethod('put') && Blog::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $id)->firstOrFail();
            $settings = $blog->settings()->cursor();
            $rules = [];

            foreach ($settings as $setting) {
                if ($setting->required == Setting::REQUIRED) {
                    $required = 'required';
                } else {
                    $required = 'nullable';
                }

                switch ($setting->type) {
                    case Setting::INPUT_FILE:
                        if (filled($setting->options)) {
                            $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'file' ,'mimes:' . implode(',', unserialize($setting->options))];
                        } else {
                            $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'file'];
                        }
                        break;
                    case Setting::INPUT_EMAIL:
                        $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'string', 'email:rfc,dns'];
                        break;
                    case Setting::INPUT_TEXT:
                        $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'string'];
                        break;
                    case Setting::INPUT_TEXTAREA:
                        $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'string'];
                        break;
                    case Setting::INPUT_NUMBER:
                        $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'numeric'];
                        break;
                    case Setting::INPUT_RANGE:
                        $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'numeric'];
                        break;
                    case Setting::INPUT_SELECT:
                        if (filled($setting->options)) {
                            $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'string', Rule::in(unserialize($setting->options))];
                        } else {
                            $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'string'];
                        }
                        break;
                    case Setting::INPUT_MULTISELECT:
                        $rules[str_replace(config('app.domain').'_', '', $setting->key)] = [$required, 'string'];
                        break;
                }
            }

            $validatedData = $request->validate($rules);

            foreach ($settings as $setting) {
                if ($request->has(str_replace(config('app.domain').'_', '', $setting->key))) {
                    $setting->value = $request->input(str_replace(config('app.domain').'_', '', $setting->key));
                    $setting->saveOrFail();
                }
            }

            flash('Successfully updated blog settings.');
            return redirect()->route('admin.blogs.settings', ['id' => $blog]);
        }

        flash('Blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }
}

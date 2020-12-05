<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Blog\Post;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Blog;
use App\Models\System\Post;
use App\Models\System\Role;
use App\Models\System\User;
use App\Rules\SanitizeHtml;

/**
 * Admin Blog Posts Controller
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package   App\Http\Controllers\Admin
 */
class PostsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $blog)
    {
        if (Blog::where('id', $blog)->exists()) {
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

            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();

            if ($withTrashed === true) {
                $posts = $blog->posts()->withTrashed()->paginate($perPage);
            } else {
                $posts = $blog->posts()->paginate($perPage);
            }

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Blogs'         => ['path' => route('admin.blogs.index'),             'active' => false],
                $blog->title    => ['path' => route('admin.blogs.edit', $blog),       'active' => false],
                'Posts'         => ['path' => route('admin.posts.index', $blog),      'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.blogs.posts.index', compact('blog', 'breadcrumbs', 'posts', 'query', 'withTrashed'));
        }

        return redirect()->route('admin.blogs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $blog)
    {
        if (Blog::where('id', $blog)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();
            $status_types = Post::STATUS_TYPES;
            $visible_types = Post::VISIBLE_TYPES;
            $meta_robots = Post::META_ROBOTS;

            $users = User::where('status', User::ACTIVE)
                ->whereHas(
                    'roles',
                    function ($query) {
                            $roles = [
                            Role::SUPER_ADMIN,
                            Role::ADMIN,
                            Role::EDITOR,
                            Role::AUTHOR,
                            Role::SUPPORT
                            ];
                            $query->whereIn('name', $roles);
                    }
                )->select(['id', 'first_name', 'last_name'])
                       ->cursor();
            $_users = [];

            foreach ($users as $user) {
                $_users[$user->id] = $user->first_name . ' ' . $user->last_name;
            }

            $users = $_users;

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Blogs'         => ['path' => route('admin.blogs.index'),             'active' => false],
                $blog->title    => ['path' => route('admin.blogs.edit', $blog),       'active' => false],
                'Posts'         => ['path' => route('admin.posts.index', $blog),      'active' => false],
                'Add Post'      => ['path' => route('admin.posts.create', $blog),     'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.blogs.posts.create', compact('blog', 'breadcrumbs', 'users', 'status_types', 'visible_types', 'meta_robots'));
        }

        flash('Blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $blog)
    {
        if (Blog::where('id', $blog)->exists()) {
            $rules = [
                'user'              => ['required', 'integer', 'exists:system.users,id'],
                'title'             => ['required', 'string', 'max:150', new SanitizeHtml()],
                'content'           => ['required', 'string'],
                'excerpt'           => ['nullable', 'string', 'max:150'],
                'media'             => ['nullable', 'file', 'image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
                'seo_title'         => ['nullable', 'string', 'max:100', new SanitizeHtml()],
                'meta_keywords'     => ['nullable', 'string', 'max:150', new SanitizeHtml()],
                'meta_description'  => ['nullable', 'string', 'max:150', new SanitizeHtml()],
                'meta_robots'       => ['nullable', 'string', new SanitizeHtml()],
                'visible'           => ['required', 'string', Rule::in(Post::VISIBLE_TYPES)],
                'status'            => ['required', 'string', Rule::in(Post::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();

            $title = strip_tags($request->input('title'));

            $post = new Post();
            $post->user_id = $request->input('user');
            $post->title = $title;
            $post->slug = unique_slug('post', $title);
            $post->content = $request->input('content');
            $post->excerpt = strip_tags($request->input('excerpt'));
            $post->seo_title = strip_tags($request->input('seo_title'));
            $post->meta_keywords = strip_tags($request->input('meta_keywords'));
            $post->meta_description = strip_tags($request->input('meta_description'));
            $post->meta_robots = strip_tags($request->input('meta_robots'));
            $post->visible = $request->input('visible');
            $post->status = $request->input('status');

            // File Uploads
            if ($request->hasFile('media')) {
                $file = $request->file('media');

                $post->addMedia($file)
                    ->toMediaCollection('images');
            }

            // Save
            if ($post->saveOrFail()) {
                $blog->assignPost($post);

                flash('Successfully created post');
                return redirect()->route('admin.posts.edit', ['post' => $post, 'blog' => $blog]);
            }
        }

        flash('Blog does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $blog, $id)
    {
        return redirect()->route('admin.posts.edit', ['post' => $id, 'blog' => $blog]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $blog, $id)
    {
        if (Blog::where('id', $blog)->exists() && Post::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();

            $status_types = Post::STATUS_TYPES;
            $visible_types = Post::VISIBLE_TYPES;
            $meta_robots = Post::META_ROBOTS;

            $users = User::where('status', User::ACTIVE)
                ->whereHas(
                    'roles',
                    function ($query) {
                            $roles = [
                            Role::SUPER_ADMIN,
                            Role::ADMIN,
                            Role::EDITOR,
                            Role::AUTHOR,
                            Role::SUPPORT
                            ];
                            $query->whereIn('name', $roles);
                    }
                )->select(['id', 'first_name', 'last_name'])
                       ->cursor();
            $_users = [];

            foreach ($users as $user) {
                $_users[$user->id] = $user->first_name . ' ' . $user->last_name;
            }

            $users = $_users;
            $post = $blog->posts()->where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                                                  'active' => false],
                'Blogs'         => ['path' => route('admin.blogs.index'),                                         'active' => false],
                $blog->title    => ['path' => route('admin.blogs.edit', $blog),                                   'active' => false],
                'Posts'         => ['path' => route('admin.posts.index', $blog),                                  'active' => false],
                'Edit Post'     => ['path' => route('admin.posts.edit', ['post' => $post, 'blog' => $blog]),      'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.blogs.posts.edit', compact('breadcrumbs', 'blog', 'post', 'users', 'status_types', 'visible_types', 'meta_robots'));
        }

        flash('Blog or post does not exist.');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $blog, $id)
    {
        if (Blog::where('id', $blog)->exists() && Post::where('id', $id)->exists()) {
            $rules = [
                'user'              => ['required', 'integer', 'exists:system.users,id'],
                'title'             => ['required', 'string', 'max:150', new SanitizeHtml()],
                'content'           => ['required', 'string'],
                'excerpt'           => ['nullable', 'string', 'max:150'],
                'media'             => ['nullable', 'file', 'image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
                'seo_title'         => ['nullable', 'string', 'max:100', new SanitizeHtml()],
                'meta_keywords'     => ['nullable', 'string', 'max:150', new SanitizeHtml()],
                'meta_description'  => ['nullable', 'string', 'max:150', new SanitizeHtml()],
                'meta_robots'       => ['nullable', 'string', new SanitizeHtml()],
                'visible'           => ['required', 'string', Rule::in(Post::VISIBLE_TYPES)],
                'status'            => ['required', 'string', Rule::in(Post::STATUS_TYPES)]
            ];

            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();
            $post = $blog->posts()->where('id', $id)->firstOrFail();

            $validatedData = $request->validate($rules);

            $post->user_id = $request->input('user');
            $post->title = strip_tags($request->input('title'));
            $post->content = $request->input('content');
            $post->excerpt = strip_tags($request->input('excerpt'));
            $post->seo_title = strip_tags($request->input('seo_title'));
            $post->meta_keywords = strip_tags($request->input('meta_keywords'));
            $post->meta_description = strip_tags($request->input('meta_description'));
            $post->meta_robots = strip_tags($request->input('meta_robots'));
            $post->visible = $request->input('visible');
            $post->status = $request->input('status');

            // File Uploads
            if ($request->hasFile('media')) {
                $file = $request->file('media');

                $post->addMedia($file)
                    ->toMediaCollection('images');
            }

            if ($post->saveOrFail()) {
                flash('Successfully updated post.');
                return redirect()->route('admin.posts.edit', ['blog' => $blog, 'post' => $post]);
            }
        }

        flash('Blog or post does not exist.');
        return redirect()->route('admin.posts.index', $blog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $blog, $id)
    {
        if (Blog::where('id', $blog)->exists() && Post::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();
            $post = $blog->posts()->where('id', $id)->firstOrFail();

            if ($post) {
                $post->status = Post::INACTIVE;
                $post->visible = Post::HIDDEN;
                $post->save();
                $post->delete();
                flash('Deleted blog post successfully.');
                return redirect()->route('admin.posts.index', $blog);
            }
        }

        flash('Blog or post does not exist.');
        return redirect()->route('admin.posts.index', $blog);
    }

    /**
     * Delete the specified media image from posts.
     *
     * @param  int $id
     * @param  int $image
     * @return \Illuminate\Http\Response
     */
    public function deleteMediaImage(Request $request, $blog, $id, $image)
    {
        if (Blog::where('id', $blog)->exists() && Post::where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();
            $post = $blog->posts()->where('id', $id)->firstOrFail();
            $images = $post->getMedia('images');

            foreach ($images as $index => $_image) {
                if ($index == $image) {
                    $_image->delete();
                    flash('Successfully deleted media image.');
                    return redirect()->route('admin.posts.edit', ['post' => $post, 'blog' => $blog]);
                }
            }

            flash('Media resource does not exist');
            return redirect()->route('admin.posts.edit', ['post' => $post, 'blog' => $blog]);
        }

        flash('Post or media resource does not exist');
        return redirect()->route('admin.posts.index', $blog);
    }

    /**
     * Deletes post from blog resource storage forever
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $blog, $id)
    {
        if (Blog::where('id', $blog)->exists() && Post::withTrashed()->where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();
            $post = $blog->posts()->withTrashed()->where('id', $id)->firstOrFail();

            if ($post) {
                // Delete all images from post
                $images = $post->getMedia('images');

                foreach ($images as $image) {
                    $image->delete();
                }

                // Remove from blog and delete.
                $blog->unassignPost($post);
                $post->forceDelete();

                flash('Successfully delete post from blog trash forever.');
                return redirect()->route('admin.posts.index', $blog);
            }
        }

        flash('Blog or post does not exist.');
        return redirect()->route('admin.posts.index', $blog);
    }

    /**
     * Restores post from blog resource storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $blog, $id)
    {
        if (Blog::where('id', $blog)->exists() && Post::withTrashed()->where('id', $id)->exists()) {
            $site = site();
            $blog = $site->blogs()->where('id', $blog)->firstOrFail();
            $post = $blog->posts()->withTrashed()->where('id', $id)->firstOrFail();

            // Restore post if exists
            if ($post) {
                $post->deleted_at = null;
                $post->save();
                $post->restore();

                flash('Successfully restored post from blog.');
                return redirect()->route('admin.posts.edit', ['post' => $id, 'blog' => $blog]);
            }
        }

        flash('Blog or post does not exist.');
        return redirect()->route('admin.posts.edit', $blog);
    }
}

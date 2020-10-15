<?php

declare(strict_types=1);


use Illuminate\Database\Seeder;
use App\Models\System\Site;
use App\Models\System\Blog;
use Illuminate\Support\Str;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Site::where('domain', config('app.base_domain'))->exists()) {
            $site = Site::where('domain', config('app.base_domain'))->select(['id'])->firstOrFail();

            // Public Blog
            if (!Blog::where('name', 'public')->exists()) {
                $blog = new Blog;
                $blog->uuid = Str::uuid();
                $blog->name = 'public';
                $blog->title = 'Public';
                $blog->slug = 'public';
                $blog->status = Blog::ACTIVE;
                $blog->visible = Blog::VISIBLE;
                $blog->saveOrFail();
                $site->assignBlog($blog);
            }

            // Private Blog.
            if (!Blog::where('name', 'private')->exists()) {
                $blog = new Blog;
                $blog->uuid = Str::uuid();
                $blog->name = 'private';
                $blog->title = 'Private';
                $blog->slug = 'private';
                $blog->status = Blog::ACTIVE;
                $blog->visible = Blog::HIDDEN;
                $blog->saveOrFail();
                $site->assignBlog($blog);
            }
        }
    }
}

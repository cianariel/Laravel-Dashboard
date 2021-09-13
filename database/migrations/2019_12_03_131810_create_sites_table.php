<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\System\Attachment;
use App\Models\System\Tag;
use App\Models\System\Cart;
use App\Models\System\CartLine;
use App\Models\System\Comment;
use App\Models\System\Country;
use App\Models\System\Currency;
use App\Models\System\Folder;
use App\Models\System\Forum;
use App\Models\System\ForumDiscussion;
use App\Models\System\ForumPost;
use App\Models\System\ForumCategory;
use App\Models\System\Group;
use App\Models\System\Menu;
use App\Models\System\MenuItem;
use App\Models\System\Message;
use App\Models\System\Note;
use App\Models\System\Invoice;
use App\Models\System\InvoiceItem;
use App\Models\System\Order;
use App\Models\System\Quote;
use App\Models\System\QuoteItem;
use App\Models\System\Package;
use App\Models\System\Page;
use App\Models\System\Payment;
use App\Models\System\Product;
use App\Models\System\ProductAttribute;
use App\Models\System\ProductType;
use App\Models\System\Redirect;
use App\Models\System\Refund;
use App\Models\System\Review;
use App\Models\System\Setting;
use App\Models\System\Site;
use App\Models\System\State;
use App\Models\System\SupportTicket;
use App\Models\System\TimeZone;
use App\Models\System\User;
use App\Models\System\Translation;

/**
 * Sites Table Seeder
 *
 * @copyright 2020 MDRepTime, LLC
 * @package Database\Migrations\Tenant
 *
 * @todo Tags foreign keys
 */
class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tags
        //----------------------------------------------------------//
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 25);
            $table->string('name');
            $table->string('label', 150);
        });

        // Translations
        //----------------------------------------------------------//
        Schema::create('translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->default(Translation::DEFAULT_LOCALE);
            $table->longText('content')->nullable();
            $table->timestamps();
        });

        // Currencies
        //----------------------------------------------------------//
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 5)->unqiue();
            $table->string('symbol', 5);
            $table->string('name', 100)->index();
            $table->string('name_plural', 150);
            $table->string('symbol_native', 25);
            $table->unsignedTinyInteger('decimal_digits');
            $table->string('status', 25)->default(Currency::INACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });

        // Timezones
        //----------------------------------------------------------//
        Schema::create('timezones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('zone', 150)->unique();
            $table->string('status', 25)->default(TimeZone::INACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });

        // Countries
        //----------------------------------------------------------//
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 2)->unique();
            $table->string('name')->index();
            $table->string('status', 25)->default(Country::INACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });

        // States
        //----------------------------------------------------------//
        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 2);
            $table->string('name', 50);
            $table->string('status', 15)->default(State::ACTIVE);
            $table->timestamps();
            $table->softDeletes();

            $table->index('code', 'name');
        });

        // Foreign Keys
        Schema::create('country_state', function (Blueprint $table) {

            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('state_id');

            $table->foreign('country_id')
                  ->references('id')
                  ->on('countries')
                  ->onDelete('cascade');

            $table->foreign('state_id')
                  ->references('id')
                  ->on('states')
                  ->onDelete('cascade');

            $table->primary(['country_id', 'state_id']);
        });

        // Sites
        //----------------------------------------------------------//
        Schema::create('sites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('name', 100)->default(config('app.name'))->index();
            $table->string('domain', 100)->default(config('app.base_domain'))->unique();
            $table->string('status', 25)->default(Site::INACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });

        // Forgein Keys
        Schema::create('country_site', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('country_id')
                  ->references('id')
                  ->on('countries')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['country_id', 'site_id']);
        });

        Schema::create('site_state', function (Blueprint $table) {
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('state_id');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->foreign('state_id')
                  ->references('id')
                  ->on('states')
                  ->onDelete('cascade');


            $table->primary(['site_id', 'state_id']);
        });

        // Groups
        //----------------------------------------------------------//
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 25)->default(Group::SYSTEM);
            $table->string('name')->unique();
            $table->string('label', 150);
            $table->string('visible', 25)->default(Group::HIDDEN);
            $table->string('lock', 25)->default(Group::UNLOCKED);
        });

        // Folders
        //---------------------------------------------------------//
        Schema::create('folders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('label', 150);
            $table->string('visible', 25)->default(Folder::HIDDEN);
            $table->string('lock', 25)->default(Folder::UNLOCKED);
        });


        // Comments
        //----------------------------------------------------------//
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('title', 150)->index();
            $table->longText('content')->nullable();
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->string('visibility', 25)->default(Comment::OWNER);
            $table->timestamps();
        });

        // Messages
        //----------------------------------------------------------//
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('recipient')->index();
            $table->string('type', 100);
            $table->string('subject', 190);
            $table->longText('body');
            $table->string('status', 25)->default(Message::QUEUE);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // Foregin Keys
        Schema::create('message_user', function (Blueprint $table) {

            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('message_id')
                  ->references('id')
                  ->on('messages')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['message_id', 'user_id']);
        });

        Schema::create('folder_message', function (Blueprint $table) {

            $table->unsignedBigInteger('folder_id');
            $table->unsignedBigInteger('message_id');

            $table->foreign('folder_id')
                  ->references('id')
                  ->on('folders')
                  ->onDelete('cascade');

            $table->foreign('message_id')
                  ->references('id')
                  ->on('messages')
                  ->onDelete('cascade');

            $table->primary(['folder_id', 'message_id']);
        });

        // Attachments
        //----------------------------------------------------------//
        Schema::create('attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->mediumText('name');
            $table->mediumText('path');
            $table->string('mime_type', 100);
            $table->string('status', 25)->default(Attachment::UNAVAILABLE);
            $table->unsignedInteger('downloads')->default(0);
            $table->timestamps();
        });

        // Foregin Keys
        //----------------------------------------------------------//
        Schema::create('attachment_message', function (Blueprint $table) {

            $table->unsignedBigInteger('attachment_id');
            $table->unsignedBigInteger('message_id');

            $table->foreign('attachment_id')
                  ->references('id')
                  ->on('attachments')
                  ->onDelete('cascade');

            $table->foreign('message_id')
                  ->references('id')
                  ->on('messages')
                  ->onDelete('cascade');

            $table->primary(['attachment_id', 'message_id']);
        });

        // Site Users
        //----------------------------------------------------------//
        Schema::create('site_user', function (Blueprint $table) {

            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['site_id', 'user_id']);
        });

        // Site Pages
        //----------------------------------------------------------//
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 100);
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('excerpt', 150)->nullable();
            $table->string('seo_title', 100)->nullable();
            $table->string('meta_keywords', 150)->nullable();
            $table->string('meta_description', 150)->nullable();
            $table->string('meta_robots', 150)->nullable();
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->mediumText('template')->nullable();
            $table->string('status', 25)->default(Page::INACTIVE);
            $table->string('visible', 25)->default(Page::HIDDEN);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        // Foreign Keys
        Schema::create('page_translation', function (Blueprint $table) {

            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('translation_id');

            $table->foreign('page_id')
                  ->references('id')
                  ->on('pages')
                  ->onDelete('cascade');

            $table->foreign('translation_id')
                  ->references('id')
                  ->on('translations')
                  ->onDelete('cascade');

            $table->primary(['page_id', 'translation_id']);
        });

        Schema::create('page_site', function (Blueprint $table) {

            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('page_id')
                  ->references('id')
                  ->on('pages')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['page_id', 'site_id']);
        });

        // Packages
        //----------------------------------------------------------//
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('label', 150);
            $table->string('type', 50)->default(Package::SUBSCRIPTION);
            $table->mediumText('description')->nullable();
            $table->unsignedInteger('price');
            $table->string('trial_enabled')->default(Package::TRIAL_DISABLED);
            $table->integer('trial_days')->default(15);
            $table->string('interval', 25)->default(Package::MONTHLY);
            $table->string('featured', 25)->default(Package::NOT_FEATURED);
            $table->string('status', 25)->default(Package::INACTIVE);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->string('stripe_plan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Site Packages
        //----------------------------------------------------------//
        Schema::create('package_site', function (Blueprint $table) {

            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('package_id')
                  ->references('id')
                  ->on('packages')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['package_id', 'site_id']);
        });

        // Product Types
        //----------------------------------------------------------//
        Schema::create('product_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('label', 150);
            $table->string('status', 25)->default(ProductType::INACTIVE);
        });

        // Products
        //----------------------------------------------------------//
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('label', 150);
            $table->string('type', 150);
            $table->unsignedInteger('price');
            $table->mediumText('description');
            $table->string('featured', 25)->default(Product::NOT_FEATURED);
            $table->string('status', 25)->default(Product::INACTIVE);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->string('stripe_product')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });


        // Attributes
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('label', 150);
            $table->text('value');
            $table->string('status', 25)->default(ProductAttribute::INACTIVE);
        });

        Schema::create('product_product_attribute', function (Blueprint $table) {

            $table->unsignedBigInteger('product_attribute_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('product_attribute_id')
                  ->references('id')
                  ->on('product_attributes')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->primary(['product_attribute_id', 'product_id'], 'fk_product_product_att');
        });

        // Site Products
        //----------------------------------------------------------//
        Schema::create('product_site', function (Blueprint $table) {

            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['product_id', 'site_id']);
        });

        // Foreign Keys
        Schema::create('product_type_site', function (Blueprint $table) {

            $table->unsignedBigInteger('product_type_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('product_type_id')
                  ->references('id')
                  ->on('product_types')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['product_type_id', 'site_id']);
        });

        Schema::create('product_attribute_site', function (Blueprint $table) {
            $table->unsignedBigInteger('product_attribute_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('product_attribute_id')
                  ->references('id')
                  ->on('product_attributes')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['product_attribute_id', 'site_id']);
        });


        // Site Packages with Products
        Schema::create('package_product', function (Blueprint $table) {

            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('package_id')
                  ->references('id')
                  ->on('packages')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->primary(['product_id', 'package_id']);
        });

        // Carts
        //-----------------------------------------------------------//
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('status', 25)->default(Cart::CREATED);
            $table->unsignedInteger('subtotal');
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamps();
        });

        Schema::create('cart_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150)->index();
            $table->unsignedInteger('price');
            $table->unsignedTinyInteger('quantity');
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamps();
        });

        // Cart Lines
        Schema::create('cart_cart_line', function (Blueprint $table) {

            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('card_line_id');

            $table->foreign('cart_id')
                  ->references('id')
                  ->on('carts')
                  ->onDelete('cascade');

            $table->foreign('card_line_id')
                  ->references('id')
                  ->on('cart_lines')
                  ->onDelete('cascade');

            $table->primary(['cart_id', 'card_line_id']);
        });

        Schema::create('cart_site', function (Blueprint $table) {

            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('cart_id')
                  ->references('id')
                  ->on('carts')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['cart_id', 'site_id']);
        });

        // Orders
        //----------------------------------------------------------//
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('reference', 40)->unique();
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->string('status', 25)->default(Order::CREATED);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_user', function (Blueprint $table) {

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['order_id', 'user_id']);
        });

        Schema::create('order_site', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['order_id', 'site_id']);
        });

        // Payments
        //----------------------------------------------------------//
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('type')->nullable();
            $table->string('reference', 40)->unique();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('address', 100);
            $table->string('address_2', 100)->nullable();
            $table->string('city', 50);
            $table->string('state', 50);
            $table->string('zipcode', 25);
            $table->string('country', 2);
            $table->string('status', 25)->default(Payment::UNPAID);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->unsignedInteger('amount');
            $table->unsignedInteger('charged');
            $table->unsignedInteger('paid');
            $table->unsignedInteger('due');
            $table->string('currency', 5)->default(Payment::DEFAULT_CURRENCY_CODE);
            $table->string('card_brand', 25);
            $table->string('card_full_name', 100);
            $table->unsignedSmallInteger('card_last_four')->nullable();
            $table->unsignedSmallInteger('card_exp_month')->nullable();
            $table->unsignedSmallInteger('card_exp_year')->nullable();
            $table->json('stripe_response')->nullable();
            $table->string('stripe_user')->nullable()->collation('utf8mb4_bin');
            $table->string('stripe_token')->index()->collation('utf8mb4_bin');
            $table->string('email_sent', 15)->default(Payment::EMAIL_NOT_SENT);
            $table->string('user_agent');
            $table->ipAddress('ip_address');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamps();
        });

        // Foregin Keys
        Schema::create('payment_user', function (Blueprint $table) {

            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('payment_id')
                  ->references('id')
                  ->on('payments')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['payment_id', 'user_id']);
        });

        Schema::create('order_payment', function (Blueprint $table) {

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('payment_id');

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->foreign('payment_id')
                  ->references('id')
                  ->on('payments')
                  ->onDelete('cascade');

            $table->primary(['order_id', 'payment_id']);
        });


        // Refunds
        //----------------------------------------------------------//

        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('refunded_by', 190);
            $table->string('type', 100)->default(Refund::PARTIAL_AMOUNT);
            $table->string('reference', 40)->unique();
            $table->json('stripe_response')->nullable();
            $table->string('stripe_token')->index()->collation('utf8mb4_bin');
            $table->unsignedBigInteger('amount');
            $table->string('status', 50)->default(Refund::PENDING);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->string('email_sent', 25)->default(Refund::EMAIL_NOT_SENT);
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });

        // Foregin Keys
        Schema::create('refund_order', function (Blueprint $table) {

            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('order_id');

            $table->foreign('refund_id')
                  ->references('id')
                  ->on('refunds')
                  ->onDelete('cascade');

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->primary(['refund_id', 'order_id']);
        });

        Schema::create('comment_refund', function (Blueprint $table) {

            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('refund_id');

            $table->foreign('comment_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');

            $table->foreign('refund_id')
                  ->references('id')
                  ->on('refunds')
                  ->onDelete('cascade');

            $table->primary(['comment_id', 'refund_id']);
        });

        Schema::create('refund_site', function (Blueprint $table) {

            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('refund_id')
                  ->references('id')
                  ->on('refunds')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['refund_id', 'site_id']);
        });

        // Subscriptions
        //----------------------------------------------------------//
        Schema::create('site_subscription', function (Blueprint $table) {

            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('subscription_id');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->foreign('subscription_id')
                  ->references('id')
                  ->on('subscriptions')
                  ->onDelete('cascade');

            $table->primary(['site_id', 'subscription_id']);
        });

        // Forums
        //----------------------------------------------------------//
        Schema::create('forums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('title', 150)->index();
            $table->string('status', 25)->default(Forum::INACTIVE);
            $table->string('visible', 25)->default(Forum::HIDDEN);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Forum Categories
        Schema::create('forum_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title', 150);
            $table->string('slug')->unqiue();
            $table->string('status', 25)->default(ForumCategory::INACTIVE);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->unsignedInteger('sort')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Forum Discussion.
        Schema::create('forum_discussions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('slug')->unique();
            $table->string('title', 150)->index();
            $table->string('status', 25)->default(ForumDiscussion::OPEN);
            $table->string('sticky', 25)->default(ForumDiscussion::STICKY_DISABLED);
            $table->string('answered', 25)->default(ForumDiscussion::NOT_ANSWERED);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->unsignedBigInteger('views')->nullable();
            $table->unsignedInteger('sort')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Forum Posts
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('discussion_id');
            $table->longText('body')->nullable();
            $table->string('status', 25)->default(ForumPost::DRAFT);
            $table->unsignedBigInteger('views')->nullable();
            $table->timestamps();
        });

        // Foreign Keys

        Schema::create('forum_forum_post', function (Blueprint $table) {
            $table->unsignedBigInteger('forum_id');
            $table->unsignedBigInteger('forum_post_id');

            $table->foreign('forum_id')
                  ->references('id')
                  ->on('forums')
                  ->onDelete('cascade');

            $table->foreign('forum_post_id')
                  ->references('id')
                  ->on('forum_posts')
                  ->onDelete('cascade');

            $table->primary(['forum_id', 'forum_post_id']);
        });

        Schema::create('forum_forum_discussion', function (Blueprint $table) {
            $table->unsignedBigInteger('forum_id');
            $table->unsignedBigInteger('forum_discussion_id');

            $table->foreign('forum_id')
                  ->references('id')
                  ->on('forums')
                  ->onDelete('cascade');

            $table->foreign('forum_discussion_id')
                  ->references('id')
                  ->on('forum_discussions')
                  ->onDelete('cascade');

            $table->primary(['forum_id', 'forum_discussion_id']);
        });

        Schema::create('comment_forum_post', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('forum_post_id');

            $table->foreign('comment_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');

            $table->foreign('forum_post_id')
                  ->references('id')
                  ->on('forum_posts')
                  ->onDelete('cascade');

            $table->primary(['comment_id', 'forum_post_id']);
        });

        Schema::create('forum_discussion_forum_post', function (Blueprint $table) {
            $table->unsignedBigInteger('forum_discussion_id');
            $table->unsignedBigInteger('forum_post_id');

            $table->foreign('forum_discussion_id')
                  ->references('id')
                  ->on('forum_discussions')
                  ->onDelete('cascade');

            $table->foreign('forum_post_id')
                  ->references('id')
                  ->on('forum_posts')
                  ->onDelete('cascade');

            $table->primary(['forum_discussion_id', 'forum_post_id'], 'fk_forum_discussion_posts');
        });


        Schema::create('forum_forum_category', function (Blueprint $table) {
            $table->unsignedBigInteger('forum_id');
            $table->unsignedBigInteger('forum_category_id');

            $table->foreign('forum_id')
                  ->references('id')
                  ->on('forums')
                  ->onDelete('cascade');

            $table->foreign('forum_category_id')
                  ->references('id')
                  ->on('forum_categories')
                  ->onDelete('cascade');

            $table->primary(['forum_id', 'forum_category_id']);
        });


        Schema::create('forum_site', function (Blueprint $table) {
            $table->unsignedBigInteger('forum_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('forum_id')
                  ->references('id')
                  ->on('forums')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary('forum_id', 'site_id');
        });


        // Redirects
        //----------------------------------------------------------//
        Schema::create('redirects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 190)->index();
            $table->mediumText('path');
            $table->mediumText('redirect_path');
            $table->unsignedSmallInteger('code');
        });

        // Site Redirects
        //----------------------------------------------------------//
        Schema::create('redirect_site', function (Blueprint $table) {

            $table->unsignedBigInteger('redirect_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('redirect_id')
                  ->references('id')
                  ->on('redirects')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['redirect_id', 'site_id']);
        });

        // Settings.
        //----------------------------------------------------------//
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('key');
            $table->mediumText('value')->nullable();
            $table->string('type', 100)->default(Setting::INPUT_TEXT);
            $table->mediumText('options')->nullable();
            $table->string('status', 15)->default(Setting::UNLOCKED);
            $table->string('required', 25)->default(Setting::NOT_REQUIRED);
        });

        Schema::create('group_setting', function (Blueprint $table) {

            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('setting_id');

            $table->foreign('group_id')
                  ->references('id')
                  ->on('groups')
                  ->onDelete('cascade');

            $table->foreign('setting_id')
                  ->references('id')
                  ->on('settings')
                  ->onDelete('cascade');

            $table->primary(['group_id', 'setting_id']);
        });

        // User Settings
        //----------------------------------------------------------//
        Schema::create('setting_user', function (Blueprint $table) {
            $table->unsignedBigInteger('setting_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('setting_id')
                  ->references('id')
                  ->on('settings')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary('setting_id', 'user_id');
        });

        // Site Settings
        //----------------------------------------------------------//
        Schema::create('setting_site', function (Blueprint $table) {
            $table->unsignedBigInteger('setting_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('setting_id')
                  ->references('id')
                  ->on('settings')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['setting_id', 'site_id']);
        });

        // Menu
        //----------------------------------------------------------//
        // Menus
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 100)->default(Menu::SIMPLE_LIST);
            $table->string('name')->unique();
            $table->string('label', 150);
            $table->string('location', 190);
            $table->text('css_classes')->nullable();
            $table->string('status', 25)->default(Menu::INACTIVE);
        });

        // Menu Items
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('type', 100)->default(MenuItem::PARENT_ITEM);
            $table->string('name');
            $table->string('title', 190);
            $table->string('label', 150);
            $table->text('url');
            $table->string('target', 25)->default(MenuItem::TARGET_SELF);
            $table->string('css_classes', 190)->nullable();
            $table->unsignedInteger('position')->nullable();
        });

        // Foregin Keys
        Schema::create('menu_menu_item', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('menu_item_id');

            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');

            $table->foreign('menu_item_id')
                  ->references('id')
                  ->on('menu_items')
                  ->onDelete('cascade');

            $table->primary(['menu_id', 'menu_item_id']);
        });

        Schema::create('menu_site', function (Blueprint $table) {

            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['menu_id', 'site_id']);
        });


        // Support Tickets
        //----------------------------------------------------//
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('priority')->default(SupportTicket::PRIORITY_LOW);
            $table->string('type', 150);
            $table->string('reference', 40)->unqiue();
            $table->string('status', 25)->default(SupportTicket::CREATED);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        // Foregin Keys

        Schema::create('message_support_ticket', function (Blueprint $table) {

            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('support_ticket_id');

            $table->foreign('message_id')
                  ->references('id')
                  ->on('messages')
                  ->onDelete('cascade');

            $table->foreign('support_ticket_id')
                  ->references('id')
                  ->on('support_tickets')
                  ->onDelete('cascade');

            $table->primary(['message_id', 'support_ticket_id']);
        });

        Schema::create('support_ticket_user', function (Blueprint $table) {

            $table->unsignedBigInteger('support_ticket_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('support_ticket_id')
                  ->references('id')
                  ->on('support_tickets')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['support_ticket_id', 'user_id']);
        });

        // Reviews
        //-------------------------------------------------------//
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('rate');
            $table->string('name', 150)->index();
            $table->string('status', 25)->default(Review::CREATED);
            $table->string('visible', 25)->default(Review::HIDDEN);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamps();
        });

        // Foregin Keys
        Schema::create('comment_review', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('review_id');

            $table->foreign('comment_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');

            $table->foreign('review_id')
                  ->references('id')
                  ->on('reviews')
                  ->onDelete('cascade');

            $table->primary(['comment_id', 'review_id']);
        });

        Schema::create('review_user', function (Blueprint $table) {
            $table->unsignedBigInteger('review_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('review_id')
                  ->references('id')
                  ->on('reviews')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['review_id', 'user_id']);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('reference', 40)->unique();
            $table->string('billing_company', 100)->nullable();
            $table->string('billing_first_name', 100)->nullable();
            $table->string('billing_last_name', 100)->nullable();
            $table->string('billing_address', 100)->nullable();
            $table->string('billing_address_2', 100)->nullable();
            $table->string('billing_city', 50)->nullable();
            $table->string('billing_state', 50)->nullable();
            $table->string('billing_zipcode', 25)->nullable();
            $table->string('billing_country', 2)->nullable();
            $table->string('billing_phone', 16)->nullable();
            $table->string('billing_mobile_phone', 16)->nullable();
            $table->string('billing_fax_number', 16)->nullable();
            $table->string('shipping_company', 100)->nullable();
            $table->string('shipping_first_name', 100)->nullable();
            $table->string('shipping_last_name', 100)->nullable();
            $table->string('shipping_address', 100)->nullable();
            $table->string('shipping_address_2', 100)->nullable();
            $table->string('shipping_city', 50)->nullable();
            $table->string('shipping_state', 50)->nullable();
            $table->string('shipping_zipcode', 25)->nullable();
            $table->string('shipping_country', 2)->nullable();
            $table->string('shipping_phone', 16)->nullable();
            $table->string('shipping_mobile_phone', 16)->nullable();
            $table->string('shipping_fax_number', 16)->nullable();
            $table->string('payment_terms')->nullable();
            $table->unsignedBigInteger('subtotal')->nullable();
            $table->unsignedBigInteger('taxes')->nullable();
            $table->unsignedBigInteger('total')->nullable();
            $table->unsignedBigInteger('due')->nullable();
            $table->unsignedBigInteger('paid')->nullable();
            $table->text('notes')->nullable();
            $table->text('private_notes')->nullable();
            $table->longText('terms')->nullable();
            $table->string('allow_partial_payments')->default(Invoice::PARTIAL_PAYMENTS_NOT_ACCEPTED);
            $table->string('status', 25)->default(Invoice::UNPAID);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Invoice Line Items
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 50)->default(InvoiceItem::NOT_INVENTORY_ITEM);
            $table->string('sku')->nullable();
            $table->string('label', 150)->nullable();
            $table->longText('description')->nullable();
            $table->string('unit')->nullable();
            $table->unsignedDecimal('quantity')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->text('notes')->nullable();
            $table->schemalessAttributes('meta_fields')->nullable();
        });

        Schema::create('invoice_site', function (Blueprint $table) {

            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('invoice_id')
                  ->references('id')
                  ->on('invoices')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');


            $table->primary(['invoice_id', 'site_id']);
        });

        Schema::create('invoice_user', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('invoice_id')
                  ->references('id')
                  ->on('invoices')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['invoice_id', 'user_id']);
        });

        if (!Schema::hasTable('quotes')) {
            Schema::create('quotes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('uuid')->unique();
                $table->unsignedBigInteger('user_id');
                $table->string('reference', 40)->unique();
                $table->string('type', 50)->default(Quote::GENERIC);
                $table->string('status', 25)->default(Quote::DRAFT);
                $table->text('notes')->nullable();
                $table->text('private_notes')->nullable();
                $table->schemalessAttributes('meta_fields')->nullable();
                $table->timestamps();
            });

            Schema::create('quote_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('label', 150)->nullable();
                $table->longText('description')->nullable();
                $table->string('unit')->nullable();
                $table->unsignedDecimal('quantity')->nullable();
                $table->unsignedBigInteger('price')->nullable();
                $table->schemalessAttributes('meta_fields')->nullable();
            });

            // Foriegn Keys
            Schema::create('site_quote', function (Blueprint $table) {

                $table->unsignedBigInteger('site_id');
                $table->unsignedBigInteger('quote_id');

                $table->foreign('site_id')
                      ->references('id')
                      ->on('sites')
                      ->onDelete('cascade');

                $table->foreign('quote_id')
                      ->references('id')
                      ->on('quotes')
                      ->onDelete('cascade');

                $table->primary(['site_id', 'quote_id']);
            });

            Schema::create('quote_user', function (Blueprint $table) {

                $table->unsignedBigInteger('quote_id');
                $table->unsignedBigInteger('user_id');

                $table->foreign('quote_id')
                      ->references('id')
                      ->on('quotes')
                      ->onDelete('cascade');

                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->primary(['quote_id', 'user_id']);
            });
        }

        // Notes
        //-----------------------------------------------//
        if (!Schema::hasTable('notes')) {
            Schema::create('notes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->longText('content');
                $table->schemalessAttributes('meta_fields')->nullable();
                $table->timestamps();
            });

            // Foriegn Keys
            Schema::create('note_user', function (Blueprint $table) {
                $table->unsignedBigInteger('note_id');
                $table->unsignedBigInteger('user_id');

                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->foreign('note_id')
                      ->references('id')
                      ->on('notes')
                      ->onDelete('cascade');

                $table->primary(['user_id', 'note_id'], 'fk_user_notes');
            });

            Schema::create('note_site', function (Blueprint $table) {
                $table->unsignedBigInteger('note_id');
                $table->unsignedBigInteger('site_id');

                $table->foreign('note_id')
                      ->references('id')
                      ->on('notes')
                      ->onDelete('cascade');

                $table->foreign('site_id')
                      ->references('id')
                      ->on('sites')
                      ->onDelete('cascade');


                $table->primary(['note_id', 'site_id'], 'fk_site_notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Pivots
        //-----------------------------------//
        Schema::dropIfExists('attachment_message');
        Schema::dropIfExists('cart_cart_line');
        Schema::dropIfExists('cart_site');
        Schema::dropIfExists('comment_forum_post');
        Schema::dropIfExists('comment_refund');
        Schema::dropIfExists('comment_review');
        Schema::dropIfExists('country_site');
        Schema::dropIfExists('country_state');
        Schema::dropIfExists('folder_message');
        Schema::dropIfExists('forum_discussion_forum_post');
        Schema::dropIfExists('forum_forum_category');
        Schema::dropIfExists('forum_forum_discussion');
        Schema::dropIfExists('forum_forum_post');
        Schema::dropIfExists('group_setting');
        Schema::dropIfExists('menu_menu_item');
        Schema::dropIfExists('menu_site');
        Schema::dropIfExists('message_support_ticket');
        Schema::dropIfExists('message_user');
        Schema::dropIfExists('note_user');
        Schema::dropIfExists('note_site');
        Schema::dropIfExists('invoice_site');
        Schema::dropIfExists('invoice_user');
        Schema::dropIfExists('invoice_invoice_item');
        Schema::dropIfExists('order_payment');
        Schema::dropIfExists('order_site');
        Schema::dropIfExists('order_user');
        Schema::dropIfExists('package_product');
        Schema::dropIfExists('package_site');
        Schema::dropIfExists('page_site');
        Schema::dropIfExists('page_translation');
        Schema::dropIfExists('payment_user');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('product_attribute_site');
        Schema::dropIfExists('product_product_attribute');
        Schema::dropIfExists('product_site');
        Schema::dropIfExists('product_type_site');
        Schema::dropIfExists('quote_user');
        Schema::dropIfExists('quote_site');
        Schema::dropIfExists('redirect_site');
        Schema::dropIfExists('refund_order');
        Schema::dropIfExists('refund_site');
        Schema::dropIfExists('review_user');
        Schema::dropIfExists('setting_site');
        Schema::dropIfExists('setting_user');
        Schema::dropIfExists('site_tag');
        Schema::dropIfExists('site_state');
        Schema::dropIfExists('site_quote');
        Schema::dropIfExists('site_subscription');
        Schema::dropIfExists('site_user');
        Schema::dropIfExists('support_ticket_user');

        // Tables
        //-----------------------------------//
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('cart_lines');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('folders');
        Schema::dropIfExists('forum_categories');
        Schema::dropIfExists('forum_discussions');
        Schema::dropIfExists('forum_posts');
        Schema::dropIfExists('forum_site');
        Schema::dropIfExists('forums');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('products');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('sites');
        Schema::dropIfExists('states');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('timezones');
        Schema::dropIfExists('translations');
    }
}

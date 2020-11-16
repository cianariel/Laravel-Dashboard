<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\System\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            // General
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('email')->unique();
            $table->string('username', 25)->unique();
            $table->string('password');
            $table->string('company', 50)->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('address_2', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zipcode', 25)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('phone', 16)->nullable();
            $table->string('mobile_phone', 16)->nullable();

            // Metafields
            $table->schemalessAttributes('meta_fields')->nullable();

            // Card Details
            $table->string('stripe_id')->nullable()->index()->collation('utf8mb4_bin');
            $table->string('payment_method')->nullable()->collation('utf8mb4_bin');
            $table->string('card_brand', 25)->nullable();
            $table->string('card_full_name', 100)->nullable();
            $table->string('card_country', 4)->nullable();
            $table->string('card_funding', 25)->nullable();
            $table->unsignedSmallInteger('card_last_four')->nullable();
            $table->unsignedSmallInteger('card_exp_month')->nullable();
            $table->unsignedSmallInteger('card_exp_year')->nullable();

            // Timestamp & Other
            $table->string('notifications')->nullable();
            $table->string('status', 25)->default(User::INACTIVE);
            $table->string('setup_completed', 25)->nullable();
            $table->string('terms', 10)->default(User::TERMS_DECLINED);
            $table->string('marketing', 10)->default(User::MARKETING_DECLINED);
            $table->string('user_agent', 1000)->nullable();
            $table->string('invite_code', 40)->nullable();
            $table->rememberToken();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

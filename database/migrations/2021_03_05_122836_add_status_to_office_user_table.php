<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddStatusToOfficeUserTable Migration Update
 *
 * @copyright 2020 MdRepTime, LLC
 */
class AddStatusToOfficeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_user', function (Blueprint $table) {
            $table->integer('status')->default(0)->comment('0: pending, 1: approved, 2: blocked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_user', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
}

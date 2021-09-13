<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AlterPreviousDateColumnAppointmentTable Migration Update
 *
 * @copyright 2020 MdRepTime, LLC
 */
class AlterPreviousDateColumnAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['previous_date']);
            $table->timestamp('previous_scheduled_on')->nullable()->after('scheduled_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['previous_scheduled_on']);
            $table->timestamp('previous_date')->nullable()->after('scheduled_on');
        });
    }
}

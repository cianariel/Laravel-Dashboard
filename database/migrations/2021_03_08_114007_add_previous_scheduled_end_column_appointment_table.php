<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddPreviousScheduledEndColumnAppointmentTable Migration Update
 *
 * @copyright 2020 MdRepTime, LLC
 */
class AddPreviousScheduledEndColumnAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('previous_scheduled_end')->nullable()->after('previous_scheduled_on');
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
            $table->dropColumn(['previous_scheduled_end']);
        });
    }
}

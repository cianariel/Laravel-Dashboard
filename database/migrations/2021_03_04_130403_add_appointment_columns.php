<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddAppointmentColumns Migration Update
 *
 * @copyright 2020 MdRepTime, LLC
 */
class AddAppointmentColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // References
        Schema::create('appointment_user', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('appointment_id')
                  ->references('id')
                  ->on('appointments')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['appointment_id', 'user_id'], 'fk_appointment_user');
        });

        Schema::create('appointment_office', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('office_id');

            $table->foreign('appointment_id')
                  ->references('id')
                  ->on('appointments')
                  ->onDelete('cascade');

            $table->foreign('office_id')
                  ->references('id')
                  ->on('offices')
                  ->onDelete('cascade');

            $table->primary(['appointment_id', 'office_id'], 'fk_appointment_office');
        });

        Schema::create('calendar_event_user', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_event_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('calendar_event_id')
                  ->references('id')
                  ->on('calendar_events')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['calendar_event_id', 'user_id'], 'fk_calendar_event_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_user');
        Schema::dropIfExists('appointment_office');
        Schema::dropIfExists('calendar_event_user');
    }
}

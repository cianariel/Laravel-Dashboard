<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateAppointmentCalendarEventTable Migration
 *
 * @copyright 2020 MdRepTime, LLC
 */
class CreateAppointmentCalendarEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_calendar_event', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id');
                $table->unsignedBigInteger('calendar_event_id');

                $table->foreign('appointment_id')
                      ->references('id')
                      ->on('appointments')
                      ->onDelete('cascade');

                $table->foreign('calendar_event_id')
                      ->references('id')
                      ->on('calendar_events')
                      ->onDelete('cascade');

                $table->primary(['appointment_id', 'calendar_event_id'], 'fk_appointment_calendar_event');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_calendar_event');
    }
}

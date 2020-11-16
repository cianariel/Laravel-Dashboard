<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\System\CalendarEvent;

/**
 * CreateCalendarEventsTable Migration
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 */
class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('title', 190);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->string('status', 25)->default(CalendarEvent::ACTIVE);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Foreign Keys
        Schema::create('calendar_event_office', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_event_id');
            $table->unsignedBigInteger('office_id');

            $table->foreign('calendar_event_id')
                  ->references('id')
                  ->on('calendar_events')
                  ->onDelete('cascade');

            $table->foreign('office_id')
                  ->references('id')
                  ->on('offices')
                  ->onDelete('cascade');

            $table->primary(['calendar_event_id', 'office_id'], 'fk_calendar_event_office');
        });

        Schema::create('calendar_event_site', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_event_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('calendar_event_id')
                  ->references('id')
                  ->on('calendar_events')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['calendar_event_id', 'site_id'], 'fk_calendar_event_site');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_events');
    }
}

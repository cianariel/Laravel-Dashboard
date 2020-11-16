<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\System\Appointment;

/**
 * CreateAppointmentsTable Migration
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 */
class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('user_id');
            $table->unsignedBigInteger('calendar_event_id')->nullable();
            $table->string('reference', 40)->unique();
            $table->string('description', 100)->nullable();
            $table->string('status', 40)->default(Appointment::PENDING);
            $table->schemalessAttributes('meta_fields')->nullable();
            $table->timestamp('scheduled_on');
            $table->timestamp('previous_date')->nullable();
            $table->timestamps();
        });

        // Foreign Keys
        Schema::create('appointment_site', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('site_id');

            $table->foreign('appointment_id')
                  ->references('id')
                  ->on('appointments')
                  ->onDelete('cascade');

            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('cascade');

            $table->primary(['appointment_id', 'site_id'], 'fk_appointment_site');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_site');
        Schema::dropIfExists('appointments');
    }
}

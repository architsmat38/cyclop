<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->string('title');
            $table->string('subtitle');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable($value = true);
            $table->time('end_time')->nullable($value = true);
            $table->unsignedInteger('distance')->comment('in meters');
            $table->string('currency')->default('INR');
            $table->double('amount', 8, 2)->default(0.00);
            $table->unsignedInteger('start_city_id');
            $table->unsignedInteger('end_city_id');
            $table->string('address');
            $table->float('latitude', 10, 6);
            $table->float('longitude', 10, 6);
            $table->unsignedInteger('terrain_id');
            $table->unsignedInteger('event_type_id')->nullable($value = true);
            $table->unsignedInteger('partner_id')->nullable($value = true);
            $table->boolean('cycle_available')->default(0);
            $table->boolean('is_active')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}

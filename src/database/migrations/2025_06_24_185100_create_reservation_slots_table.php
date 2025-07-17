<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('reserve_start')->comment('予約開始時間');
            $table->time('reserve_end')->nullable()->comment('予約終了時間');
            $table->tinyInteger('max_number')->unsigned()->default(20)->comment('人数上限');
            $table->tinyInteger('max_group')->unsigned()->default(6)->comment('組数上限');
            $table->boolean('is_active')->default(true)->comment('予約枠 true:あり false:なし');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_slots');
    }
}

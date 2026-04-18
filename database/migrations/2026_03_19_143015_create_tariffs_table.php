<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->boolean('is_trial')->default(false);
            $table->integer('duration_days');
            $table->integer('max_devices');
            $table->timestamps();
        });

        DB::table('tariffs')->insert([
            [
                'name'=>'Пробный',
                'price'=>0,
                'is_trial'=>true,
                'duration_days'=>7,
                'max_devices'=>1,
            ],
            [
                'name'=>'Одиночный',
                'price'=>50,
                'is_trial'=>false,
                'duration_days'=>30,
                'max_devices'=>1,
            ],
            [
                'name'=>'Семейный',
                'price'=>100,
                'is_trial'=>false,
                'duration_days'=>30,
                'max_devices'=>5,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};

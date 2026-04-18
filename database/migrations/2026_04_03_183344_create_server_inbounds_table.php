<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('server_inbounds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('server_id');
            $table->bigInteger('inbound');
            $table->string('port');
            $table->string('protocol');
            $table->string('type');
            $table->string('encryption');
            $table->string('security');
            $table->string('pbk');
            $table->string('fp');
            $table->string('sni');
            $table->string('sid');
            $table->string('spx');
            $table->text('pqv');
            $table->string('path')->nullable();
            $table->string('host')->nullable();
            $table->string('mode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_inbounds');
    }
};

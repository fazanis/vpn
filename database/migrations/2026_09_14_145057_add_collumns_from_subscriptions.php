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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('status')->default(0)->change();
            $table->integer('max_devices')->after('status');
            $table->integer('days')->after('max_devices');
            $table->dateTime('expires_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('status')->default(0)->change();

            if (Schema::hasColumn('subscriptions', 'max_devices')) {
                $table->dropColumn('max_devices');
            }
            if (Schema::hasColumn('subscriptions', 'days')) {
                $table->dropColumn('days');
            }
            $table->dateTime('expires_at')->nullable()->change();
        });
    }
};

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
        Schema::table('server_inbounds', function (Blueprint $table) {
            if (Schema::hasColumn('server_inbounds', 'inbound')) {
                $table->dropColumn('inbound');
            }
            if (Schema::hasColumn('server_inbounds', 'port')) {
                $table->dropColumn('port');
            }
            if (Schema::hasColumn('server_inbounds', 'protocol')) {
                $table->dropColumn('protocol');
            }
            if (Schema::hasColumn('server_inbounds', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('server_inbounds', 'encryption')) {
                $table->dropColumn('encryption');
            }
            if (Schema::hasColumn('server_inbounds', 'security')) {
                $table->dropColumn('security');
            }
            if (Schema::hasColumn('server_inbounds', 'pbk')) {
                $table->dropColumn('pbk');
            }
            if (Schema::hasColumn('server_inbounds', 'fp')) {
                $table->dropColumn('fp');
            }
            if (Schema::hasColumn('server_inbounds', 'sni')) {
                $table->dropColumn('sni');
            }
            if (Schema::hasColumn('server_inbounds', 'sid')) {
                $table->dropColumn('sid');
            }
            if (Schema::hasColumn('server_inbounds', 'spx')){
                $table->dropColumn('spx');
            }
            if (Schema::hasColumn('server_inbounds', 'pqv')) {
                $table->dropColumn('pqv');
            }

            $table->text('sub_template')->after('server_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server_inbounds', function (Blueprint $table) {
            if (!Schema::hasColumn('server_inbounds', 'inbound')){
                $table->bigInteger('inbound')->after('server_id');
            }
            if (!Schema::hasColumn('server_inbounds', 'port')){
                $table->string('port')->after('inbound');
            }
            if (!Schema::hasColumn('server_inbounds', 'protocol')){
                $table->string('protocol')->after('port');
            }
            if (!Schema::hasColumn('server_inbounds', 'type')){
                $table->string('type')->after('protocol');
            }
            if (!Schema::hasColumn('server_inbounds', 'encryption')){
                $table->string('encryption')->after('type');
            }
            if (!Schema::hasColumn('server_inbounds', 'security')){
                $table->string('security')->after('encryption');
            }
            if (!Schema::hasColumn('server_inbounds', 'pbk')){
                $table->string('pbk')->after('security');
            }
            if (!Schema::hasColumn('server_inbounds', 'fp')){
                $table->string('fp')->after('pbk');
            }
            if (!Schema::hasColumn('server_inbounds', 'sni')){
                $table->string('sni')->after('fp');
            }
            if (!Schema::hasColumn('server_inbounds', 'sid')){
                $table->string('sid')->after('sni');
            }
            if (!Schema::hasColumn('server_inbounds', 'spx')){
                $table->string('spx')->after('sid');
            }
            if (!Schema::hasColumn('server_inbounds', 'pqv')){
                $table->text('pqv')->after('spx');
            }

            $table->dropColumn('sub_template');
        });
    }
};

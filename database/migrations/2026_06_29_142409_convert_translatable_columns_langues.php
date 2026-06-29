<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('langues', function (Blueprint $table) {
            $table->renameColumn('niveau', 'niveau_old');
        });

        Schema::table('langues', function (Blueprint $table) {
            $table->text('niveau')->nullable();
        });

        DB::statement("UPDATE langues SET niveau = json_object('fr', niveau_old)");

        Schema::table('langues', function (Blueprint $table) {
            $table->dropColumn('niveau_old');
        });
    }

    public function down(): void
    {
        Schema::table('langues', function (Blueprint $table) {
            $table->renameColumn('niveau', 'niveau_json');
        });

        Schema::table('langues', function (Blueprint $table) {
            $table->string('niveau')->nullable();
        });

        DB::statement("UPDATE langues SET niveau = json_extract(niveau_json, '$.fr')");

        Schema::table('langues', function (Blueprint $table) {
            $table->dropColumn('niveau_json');
        });
    }
};

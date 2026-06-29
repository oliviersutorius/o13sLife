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
        Schema::table('profils', function (Blueprint $table) {
            $table->renameColumn('titre', 'titre_old');
        });

        Schema::table('profils', function (Blueprint $table) {
            $table->text('titre')->nullable();
        });

        DB::statement("UPDATE profils SET titre = json_object('fr', titre_old)");

        Schema::table('profils', function (Blueprint $table) {
            $table->dropColumn('titre_old');
        });
    }

    public function down(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            $table->renameColumn('titre', 'titre_json');
        });

        Schema::table('profils', function (Blueprint $table) {
            $table->string('titre')->nullable();
        });

        DB::statement("UPDATE profils SET titre = json_extract(titre_json, '$.fr')");

        Schema::table('profils', function (Blueprint $table) {
            $table->dropColumn('titre_json');
        });
    }
};

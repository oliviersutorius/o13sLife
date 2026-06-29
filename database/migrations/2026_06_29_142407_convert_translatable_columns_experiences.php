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
        Schema::table('experiences', function (Blueprint $table) {
            $table->renameColumn('titre_poste', 'titre_poste_old');
            $table->renameColumn('description', 'description_old');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->text('titre_poste')->nullable();
            $table->text('description')->nullable();
        });

        DB::statement("UPDATE experiences SET titre_poste = json_object('fr', titre_poste_old)");
        DB::statement("UPDATE experiences SET description = json_object('fr', description_old)");

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['titre_poste_old', 'description_old']);
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->renameColumn('titre_poste', 'titre_poste_json');
            $table->renameColumn('description', 'description_json');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->string('titre_poste')->nullable();
            $table->text('description')->nullable();
        });

        DB::statement("UPDATE experiences SET titre_poste = json_extract(titre_poste_json, '$.fr')");
        DB::statement("UPDATE experiences SET description = json_extract(description_json, '$.fr')");

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['titre_poste_json', 'description_json']);
        });
    }
};

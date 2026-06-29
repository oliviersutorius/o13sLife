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
        Schema::table('competences', function (Blueprint $table) {
            $table->renameColumn('categorie', 'categorie_old');
            $table->renameColumn('nom', 'nom_old');
        });

        Schema::table('competences', function (Blueprint $table) {
            $table->text('categorie')->nullable();
            $table->text('nom')->nullable();
        });

        DB::statement("UPDATE competences SET categorie = json_object('fr', categorie_old)");
        DB::statement("UPDATE competences SET nom = json_object('fr', nom_old)");

        Schema::table('competences', function (Blueprint $table) {
            $table->dropColumn(['categorie_old', 'nom_old']);
        });
    }

    public function down(): void
    {
        Schema::table('competences', function (Blueprint $table) {
            $table->renameColumn('categorie', 'categorie_json');
            $table->renameColumn('nom', 'nom_json');
        });

        Schema::table('competences', function (Blueprint $table) {
            $table->string('categorie')->nullable();
            $table->string('nom')->nullable();
        });

        DB::statement("UPDATE competences SET categorie = json_extract(categorie_json, '$.fr')");
        DB::statement("UPDATE competences SET nom = json_extract(nom_json, '$.fr')");

        Schema::table('competences', function (Blueprint $table) {
            $table->dropColumn(['categorie_json', 'nom_json']);
        });
    }
};

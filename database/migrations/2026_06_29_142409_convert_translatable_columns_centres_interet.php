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
        Schema::table('centres_interet', function (Blueprint $table) {
            $table->renameColumn('libelle', 'libelle_old');
        });

        Schema::table('centres_interet', function (Blueprint $table) {
            $table->text('libelle')->nullable();
        });

        DB::statement("UPDATE centres_interet SET libelle = json_object('fr', libelle_old)");

        Schema::table('centres_interet', function (Blueprint $table) {
            $table->dropColumn('libelle_old');
        });
    }

    public function down(): void
    {
        Schema::table('centres_interet', function (Blueprint $table) {
            $table->renameColumn('libelle', 'libelle_json');
        });

        Schema::table('centres_interet', function (Blueprint $table) {
            $table->string('libelle')->nullable();
        });

        DB::statement("UPDATE centres_interet SET libelle = json_extract(libelle_json, '$.fr')");

        Schema::table('centres_interet', function (Blueprint $table) {
            $table->dropColumn('libelle_json');
        });
    }
};

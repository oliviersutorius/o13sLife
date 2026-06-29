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
        Schema::table('formations', function (Blueprint $table) {
            $table->renameColumn('diplome', 'diplome_old');
        });

        Schema::table('formations', function (Blueprint $table) {
            $table->text('diplome')->nullable();
        });

        DB::statement("UPDATE formations SET diplome = json_object('fr', diplome_old)");

        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn('diplome_old');
        });
    }

    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->renameColumn('diplome', 'diplome_json');
        });

        Schema::table('formations', function (Blueprint $table) {
            $table->string('diplome')->nullable();
        });

        DB::statement("UPDATE formations SET diplome = json_extract(diplome_json, '$.fr')");

        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn('diplome_json');
        });
    }
};

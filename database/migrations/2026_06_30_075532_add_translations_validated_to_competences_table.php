<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competences', function (Blueprint $table) {
            $table->text('translations_validated')->nullable()->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('competences', function (Blueprint $table) {
            $table->dropColumn('translations_validated');
        });
    }
};

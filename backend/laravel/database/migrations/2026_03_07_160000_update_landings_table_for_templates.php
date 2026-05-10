<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landings', function (Blueprint $table): void {
            if (! Schema::hasColumn('landings', 'company_name')) {
                $table->string('company_name')->nullable()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('landings', function (Blueprint $table): void {
            if (Schema::hasColumn('landings', 'company_name')) {
                $table->dropColumn('company_name');
            }
        });
    }
};

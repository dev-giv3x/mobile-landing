<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('moonshine_user_id')->nullable()->constrained('moonshine_users');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->enum('status', ['new', 'in_process', 'closed'])->default('new');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

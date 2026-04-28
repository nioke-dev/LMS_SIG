<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g. Directorate, Group, Department, Unit
            $table->integer('order'); // 1, 2, 3, etc.
            $table->timestamps();
            
            $table->unique(['company_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_levels');
    }
};

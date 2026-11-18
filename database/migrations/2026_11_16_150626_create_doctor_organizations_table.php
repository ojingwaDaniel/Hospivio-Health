<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_organizations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete();

            $table->string('staff_number')->nullable();

            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamps();

            $table->unique(['doctor_id', 'organization_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_organizations');
    }
};
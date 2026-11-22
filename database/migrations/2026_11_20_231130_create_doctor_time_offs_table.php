<?php

use App\Models\DoctorOrganization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctor_time_offs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DoctorOrganization::class)->constrained()->cascadeOnDelete();
            $table->dateTime("start_at");
            $table->dateTime("end_at");
            $table->text("reason")->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['doctor_organization_id', 'start_at', 'end_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_time_offs');
    }
};

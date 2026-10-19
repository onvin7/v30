<?php

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
        Schema::create('race_editions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('year');
            $table->date('event_date')->nullable();
            $table->boolean('date_confirmed')->default(false);
            $table->boolean('is_current')->default(false);
            $table->string('status')->default('draft');
            $table->string('venue')->nullable();
            $table->text('course_summary')->nullable();
            $table->text('fee_notes')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'year']);
            $table->index(['event_id', 'is_current']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('race_editions');
    }
};

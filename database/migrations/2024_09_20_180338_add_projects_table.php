<?php

use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->date('start_date')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state', 2)->nullable();
            $table->string('address_zip')->nullable();
            $table->enum('status', [ProjectStatus::Active->value, ProjectStatus::Completed->value, ProjectStatus::Cancelled->value]);
            $table->fullText(['name']);
        });

        //        DB::statement('CREATE INDEX created_at_desc_index ON projects (created_at DESC)');

        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('file_name')->nullable();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('projects');
    }
};

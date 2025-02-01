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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->nullable();
            $table->string('po_number')->nullable();
            $table->string('number')->nullable();
            $table->json('parse_results')->nullable();
            $table->date('date')->nullable();
            $table->decimal('total');
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // create voucher line items table
        Schema::create('voucher_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->string('code')->nullable();
            $table->decimal('quantity');
            $table->decimal('each');
            $table->decimal('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop tables
        Schema::dropIfExists('voucher_lines');
        Schema::dropIfExists('vouchers');

    }
};

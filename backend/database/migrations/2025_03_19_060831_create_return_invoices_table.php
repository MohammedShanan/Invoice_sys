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
        Schema::create('return_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('representative');
            $table->foreignId('client_id')->constrained('clients');
            $table->enum('invoice_type', ['sales', 'maintenance']);
            $table->enum('discount', ['flat_rate', 'percentage'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('tax', 10, 2);
            $table->decimal('final_total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_invoices');
    }
};

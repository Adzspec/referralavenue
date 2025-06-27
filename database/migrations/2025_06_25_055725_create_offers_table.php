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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('product_url');
            $table->string('image_url')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('source', ['adtraction', 'addrevenue'])->default('adtraction');
            $table->string('external_id')->nullable(); // e.g. Adtraction product ID
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'external_id', 'source'], 'offer_unique_per_company_and_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

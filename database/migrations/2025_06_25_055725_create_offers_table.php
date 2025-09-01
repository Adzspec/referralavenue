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
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('product_url')->nullable(); // Fixed: removed duplicate
            $table->string('image_url')->nullable();
            $table->decimal('price', 10, 2)->nullable();

            $table->string('code')->nullable();
            $table->date('start_date')->nullable()->index();
            $table->date('end_date')->nullable()->index();

            $table->longText('link')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_exclusive')->default(false)->index();
            $table->boolean('is_deal')->default(false)->index();

            $table->string('path')->nullable();
            $table->longText('thumbnail')->nullable();
            $table->string('sku')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('product_price', 10, 2)->nullable();
            $table->decimal('old_price', 10, 2)->nullable();

            $table->string('source')->nullable()->index();
            $table->enum('type', ['deal', 'coupon', 'campaign'])->default('deal')->index();

            $table->string('external_id')->nullable();

            $table->boolean('status')->default(true)->index();

            $table->timestamps();

            // Unique constraint across company, external_id, and source
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

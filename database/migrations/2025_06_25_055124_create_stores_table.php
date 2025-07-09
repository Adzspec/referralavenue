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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->default(1)->comment('1 = active, 2 = inactive');

            $table->unsignedBigInteger('channelId')->nullable();
            $table->string('channelName')->nullable();
            $table->unsignedBigInteger('programId')->nullable();
            $table->string('categoryName')->nullable();
            $table->unsignedBigInteger('categoryId')->nullable();
            $table->unsignedBigInteger('productFeedId')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};

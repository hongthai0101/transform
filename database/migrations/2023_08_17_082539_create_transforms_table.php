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
        Schema::create('transforms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('providers');
            $table->string('code', 50)->unique()->index();
            $table->string('name');
            $table->string('description');
            $table->string('transform_type', 20)->enum(['json', 'xml'])->default('json');
            $table->string('to_url');
            $table->string('to_method', 20)->enum(['GET', 'POST', 'DELETE', 'PATCH', 'PUT'])->default('GET');
            $table->string('to_response_data_type', 20)->enum(['array', 'object'])->default('object');
            $table->json('request_transform')->nullable();
            $table->json('response_transform')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transforms');
    }
};

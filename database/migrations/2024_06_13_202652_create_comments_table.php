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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('opportunity_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->text('content')->nullable();


            $table->timestamps();

            $table->foreign('tenant_id')->on('tenants')->references('id');
            $table->foreign('opportunity_id')->on('opportunities')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('tag_id')->on('tags')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

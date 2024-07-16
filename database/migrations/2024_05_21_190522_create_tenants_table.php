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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->unsignedBigInteger('plan_id');
            $table->string('document')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('logo')->nullable();
            $table->date('expires_at')->nullable();

            $table->string('subscription_id', 255)->nullable();
            $table->integer('subscription_plan')->nullable();
            $table->boolean('subscription_active')->default(true);
            $table->boolean('subscription_suspended')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_id')->references('id')->on('plans');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};

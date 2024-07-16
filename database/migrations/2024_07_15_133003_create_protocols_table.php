<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tag_id');
            $table->integer('status')->default(1); //EM TRATAMENTO
            $table->string('number');
            $table->string('operator');
            $table->string('lines')->nullable();
            $table->text('description');
            $table->date('prompt');
            $table->boolean('expired')->default(0);
            $table->text('answer')->nullable();
            $table->date('closure')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants');

            $table->foreign('client_id')
                ->references('id')
                ->on('clients');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('tag_id')
                ->references('id')
                ->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocols');
    }
};

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
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_type');
            $table->unsignedBigInteger('operator');
            $table->unsignedBigInteger('status_id')->nullable(); // status pedido

            $table->date('forecast');
            $table->char('renew', 1)->default(0);
            $table->date('renew_date')->nullable(); //data renovação
            $table->string('identify', 45);
            $table->string('funnel', 45)->default('prospect');
            $table->date('activate')->nullable(); //data ativacao
            $table->string('type')->default('opportunity'); //Opportunity - Order - Wallet
            $table->char('send_order')->default(0);
            $table->date('activity_date')->nullable(); //data atividade
            $table->string('activity_status', 45)->default(0); //0 falso 1 lista atividade
            $table->double('total', 10, 2); //total oportunidade
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_type')->references('id')->on('order_types')->onDelete('cascade');
            $table->foreign('operator')->references('id')->on('operators')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};

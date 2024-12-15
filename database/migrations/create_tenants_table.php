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
            $table->string('id')->primary();
            $table->json('hosts');
            $table->string('database_connection')->nullable();
            $table->json('connection_config')->nullable();

            // Add your custom columns here

            $table->timestamps();
        });
    }
};

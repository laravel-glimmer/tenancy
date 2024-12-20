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
            $table->string('name');
            $table->json('hosts')->comment('Hostnames or subdomains that belongs to this tenant');
            $table->string('database_connection')->nullable()->comment('The database connection to use for this tenant');
            $table->json('connection_config')->nullable()->comment('The database configuration to override for this tenant');

            // Add your custom columns here

            $table->timestamps();
            $table->softDeletes();
        });
    }
};

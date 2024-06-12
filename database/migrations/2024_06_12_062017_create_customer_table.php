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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('email')->unique();
            $table->string('name');
            // storing balance in big integer in order to avoid undesirable behavior of
            // float numbers in math operations (https://0.30000000000000004.com/)
            $table->unsignedBigInteger('balance')->default(0);

            $table->string('password');

            $table->string('api_token')->unique()->nullable()->default(null);
            $table->timestamp('token_expires_at')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};

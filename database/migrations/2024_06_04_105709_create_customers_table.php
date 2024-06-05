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
            $table->string('customer_id');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('company');
            $table->string('city');
            $table->string('country');
            $table->string('phone_first');
            $table->string('phone_second');
            $table->string('email');
            $table->date('subscription_date');
            $table->string('website');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

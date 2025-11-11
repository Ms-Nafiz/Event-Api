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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            
            // রেজিস্ট্রেশন ডিটেইলস
            $table->string('registration_id')->unique(); 
            $table->string('name');
            
            // --- ইউনিক ও আবশ্যক ফিল্ড ---
            $table->string('mobile')->unique(); // আবশ্যক (required) ও ইউনিক
            $table->string('email')->unique();  // আবশ্যক (required) ও ইউনিক
            
            // রিলেশনশিপ
            // groups টেবিলের সাথে রিলেশনশিপ, nullable() ব্যবহার না করলে এটিও আবশ্যক হবে
            $table->foreignId('group_id')->constrained('groups')->comment('Foreign key to the groups table'); 
            
            // ইভেন্ট ডিটেইলস
            $table->integer('total_members')->default(1); // এই রেজিস্ট্রেশনের অধীনে মোট সদস্য সংখ্যা
            $table->string('transaction_id')->nullable();
            $table->enum('payment_status', ['Pending', 'Paid', 'Waived'])->default('Pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
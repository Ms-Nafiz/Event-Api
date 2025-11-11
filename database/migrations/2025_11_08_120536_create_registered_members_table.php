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
        Schema::create('registered_members', function (Blueprint $table) {
            $table->id();
        // foreignId: কোন মূল রেজিস্ট্রেশনের অধীনে এই সদস্য, তার আইডি
        $table->foreignId('registration_id')->constrained('event_registrations')->onDelete('cascade'); 
        
        $table->string('member_name');
        
        // লিঙ্গ (Gender)
        $table->enum('gender', ['Male', 'Female', 'Child'])->nullable(); 
        
        // টি-শার্ট সাইজ
        $table->string('t_shirt_size')->nullable(); 
        
        // বয়স (Age) - যদি শিশু হয়, বয়স উল্লেখ করা যেতে পারে
        $table->integer('age')->nullable(); 
        
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registered_members');
    }
};

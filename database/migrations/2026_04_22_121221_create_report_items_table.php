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
       Schema::create('report_items', function (Blueprint $table) {
    $table->id();

   
    $table->foreignId('report_id')->constrained()->cascadeOnDelete();

   
    $table->string('business_name');
    $table->string('location');     
    $table->string('specific_location')->nullable();

    $table->string('contact_name')->nullable();
    $table->string('phone')->nullable();

   
    $table->string('contact_method')->nullable(); 
    // visit call 

    $table->string('status')->nullable(); 
    // interested not_interested follow_up closed lost or win

    $table->string('interaction_result')->nullable();
    // busy no answer wrong number 

    $table->string('interest_level')->nullable();
    // low, medium, high

    $table->string('commitment')->nullable();
    // come tomorrow call me next week

    $table->string('next_action')->nullable();
    // call again, visit again, send demo

    $table->date('next_follow_up_date')->nullable();

   
    $table->text('remark')->nullable();

    $table->timestamps();
});    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_items');
    }
};

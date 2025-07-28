<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticketings', function (Blueprint $table) {
           $table->bigIncrements('ticketing_id');
            $table->string('subject');
            $table->text('description')->nullable();
           $table->enum('status', ['pending', 'in progress', 'solved'])->default('pending');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();
              $table->index('status');
            $table->index('created_at');
            $table->index('user_id');

               $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticketings');
    }
};


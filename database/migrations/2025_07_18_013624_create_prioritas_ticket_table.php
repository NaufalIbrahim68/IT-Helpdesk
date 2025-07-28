<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up()
{
    Schema::create('prioritas_ticket', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_user')->nullable(); // id dari tabel users
        $table->string('name');
        $table->string('npk')->nullable();
        $table->string('department')->nullable();
        $table->timestamp('added_at')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('prioritas_ticket');
    }
};

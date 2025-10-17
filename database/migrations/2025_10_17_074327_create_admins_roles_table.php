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
        Schema::create('admins_roles', function (Blueprint $table) {
            $table->id();
            $table->integer('subadmin_id'); // Идентификатор субадминистратора
            $table->string('module'); // Имя модуля (категории, продукты и т.д.)
            $table->tinyInteger('view_access'); // Доступ только для просмотра
            $table->tinyInteger('edit_access'); // Доступ для просмотра и редактирования
            $table->tinyInteger('full_access'); // Полный доступ (просмотр, редактирование, удаление)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins_roles');
    }
};

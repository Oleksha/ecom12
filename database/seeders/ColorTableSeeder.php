<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = ['Черный', 'Синий', 'Коричневый', 'Зеленый', 'Серый', 'Мульти', 'Оливковый', 'Оранжевый', 'Розовый', 'Фиолетовый', 'Красный', 'Белый', 'Желтый'];
        foreach ($colors as $colorName) {
            $color = new Color;
            $color->name = $colorName;
            $color->status = 1;
            $color->save();
        }
    }
}

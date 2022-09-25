<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(["id" => "LP", "name" => "Laptop"]);
        Category::create(["id" => "MO", "name" => "Monitor"]);
        Category::create(["id" => "PC", "name" => "PC"]);
        Category::create(["id" => "BM", "name" => "Bluetooth Mouse"]);
        Category::create(["id" => "HE", "name" => "Headset"]);
    }
}

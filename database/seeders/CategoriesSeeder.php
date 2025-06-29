<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Technology', 'backgroundColor' => '#3498db', 'textColor' => '#ffffff'],
            ['name' => 'Travel', 'backgroundColor' => '#2ecc71', 'textColor' => '#ffffff'],
            ['name' => 'Culinary', 'backgroundColor' => '#e74c3c', 'textColor' => '#ffffff'],
            ['name' => 'Fashion', 'backgroundColor' => '#9b59b6', 'textColor' => '#ffffff'],
            ['name' => 'Health and Fitness', 'backgroundColor' => '#27ae60', 'textColor' => '#ffffff'],
            ['name' => 'Science', 'backgroundColor' => '#3498db', 'textColor' => '#ffffff'],
            ['name' => 'Entertainment', 'backgroundColor' => '#e67e22', 'textColor' => '#ffffff'],
            ['name' => 'Lifestyle', 'backgroundColor' => '#f39c12', 'textColor' => '#ffffff'],
            ['name' => 'Business and Finance', 'backgroundColor' => '#34495e', 'textColor' => '#ffffff'],
            ['name' => 'Education', 'backgroundColor' => '#16a085', 'textColor' => '#ffffff'],
            ['name' => 'Sport', 'backgroundColor' => '#e74c3c', 'textColor' => '#ffffff'],
            ['name' => 'Music', 'backgroundColor' => '#2980b9', 'textColor' => '#ffffff'],
            ['name' => 'Arts and Design', 'backgroundColor' => '#8e44ad', 'textColor' => '#ffffff'],
            ['name' => 'DIY', 'backgroundColor' => '#d35400', 'textColor' => '#ffffff'],
            ['name' => 'Games', 'backgroundColor' => '#c0392b', 'textColor' => '#ffffff'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'backgroundColor' => $category['backgroundColor'],
                'textColor' => $category['textColor'],
            ]);
        }
    }
}

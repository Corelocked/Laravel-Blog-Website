<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,
            CreateWriterUserSeeder::class,
            CategoriesSeeder::class,
        ]);

        \App\Models\Post::factory(20)->create();

        $this->call([
            HighlightPostSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::truncate();

        // Buat Menu Utama
        $menu1 = Menu::create(['name' => 'Menu 1', 'parent_id' => null, 'order' => 1]);
        $menu2 = Menu::create(['name' => 'Menu 2', 'parent_id' => null, 'order' => 2]);
        $menu3 = Menu::create(['name' => 'Menu 3', 'parent_id' => null, 'order' => 3]);

        // Submenu dengan urutan
        Menu::create(['name' => 'Submenu 1.1', 'parent_id' => $menu1->id, 'order' => 1]);
        Menu::create(['name' => 'Submenu 1.2', 'parent_id' => $menu1->id, 'order' => 2]);


        // Output ke terminal
        $this->command->info('Menu dan Submenu berhasil di-seed!');
    }
}

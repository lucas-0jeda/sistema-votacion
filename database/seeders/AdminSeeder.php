<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new Admin();

        $admin->name = "Lucas";
        $admin->lastName = "Ojeda";
        $admin->email = "lucasojeda.developer@gmail.com";
        $admin->password = md5("admin");
        $admin->save();
    }
}

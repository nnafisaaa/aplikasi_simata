<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Unit;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ====================
        // Admin tetap sama
        // ====================
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'imei' => '000111222',
            ]
        );

        // ====================
        // Loop Kanit & TU per unit
        // ====================
        for ($i = 1; $i <= 9; $i++) {
            // Ambil unit berdasarkan id
            $unit = Unit::where('id', $i)->first();

            if ($unit) {
                // Kanit unit i
                User::updateOrCreate(
                    ['username' => "kanit_{$i}"],
                    [
                        'password' => Hash::make("kanit{$i}123"),
                        'role' => 'kanit',
                        'imei' => "22233344{$i}",
                        'unit_id' => $unit->id,
                    ]
                );

                // TU unit i
                User::updateOrCreate(
                    ['username' => "tu_{$i}"],
                    [
                        'password' => Hash::make("tu{$i}123"),
                        'role' => 'tu',
                        'imei' => "11122233{$i}",
                        'unit_id' => $unit->id,
                    ]
                );
            }
        }
    }
}

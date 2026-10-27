<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\StorageLocation;
use App\Models\User;

class StorageLocationsListSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure Warehouse exists
        $warehouse = Warehouse::first();
        if (!$warehouse) {
            $warehouse = Warehouse::create([
                'warehouse_name' => 'Main Warehouse',
                'location' => 'Main',
            ]);
        }

        // 2. Ensure Shop Branch exists
        $branch = Branch::first();
        if (!$branch) {
            $user = User::where('usertype', 'admin')->first() ?? User::first();
            $userId = $user ? $user->id : 1;
            $branch = Branch::create([
                'name' => 'Main Shop',
                'address' => 'Shop Floor / Main Branch',
                'number' => '0300-0000000',
                'user_id' => $userId,
            ]);
        }

        // 3. Define Warehouse Racks extracted from Excel
        $racks = [
            'A1'    => 'Aisle A',
            'A2'    => 'Aisle A',
            'A3'    => 'Aisle A',
            'A4'    => 'Aisle A',
            'A5'    => 'Aisle A',
            'A6'    => 'Aisle A',
            'A7'    => 'Aisle A',
            'A8'    => 'Aisle A',
            'A9'    => 'Aisle A',
            'A10'   => 'Aisle A',
            'A11'   => 'Aisle A',
            'A12'   => 'Aisle A',
            'A13'   => 'Aisle A',
            'A14'   => 'Aisle A',
            'A15'   => 'Aisle A',
            'A18'   => 'Aisle A',
            'A19'   => 'Aisle A',
            'A20'   => 'Aisle A',
            'A21'   => 'Aisle A',
            'A22'   => 'Aisle A',
            'A23'   => 'Aisle A',
            'A24'   => 'Aisle A',
            'A26'   => 'Aisle A',
            'A27'   => 'Aisle A',
            'A28'   => 'Aisle A',
            'A29'   => 'Aisle A',
            'A30'   => 'Aisle A',
            'A31'   => 'Aisle A',
            'A32'   => 'Aisle A',
            'A40'   => 'Aisle A',
            'A41'   => 'Aisle A',
            'B1'    => 'Aisle B',
            'B11'   => 'Aisle B',
            'BSD66' => 'Section BSD',
            'C1'    => 'Aisle C',
            'C3'    => 'Aisle C',
            'Room'  => 'Storage Room',
            'ABC'   => 'General Rack',
        ];

        foreach ($racks as $name => $zone) {
            StorageLocation::firstOrCreate(
                [
                    'type' => 'warehouse_rack',
                    'warehouse_id' => $warehouse->id,
                    'name' => $name,
                ],
                [
                    'code' => 'R-' . $name,
                    'zone_or_aisle' => $zone,
                    'description' => "Warehouse Rack {$name} ({$zone})",
                ]
            );
        }

        // 4. Define Shop Shelves extracted from Excel
        $shelves = [
            'SA0' => 'Section SA',
            'SA1' => 'Section SA',
            'SA2' => 'Section SA',
            'SA3' => 'Section SA',
            'SA4' => 'Section SA',
            'SA5' => 'Section SA',
            'SA6' => 'Section SA',
            'SB0' => 'Section SB',
            'SB4' => 'Section SB',
            'SB5' => 'Section SB',
            'SB6' => 'Section SB',
            'SC0' => 'Section SC',
            'SC3' => 'Section SC',
            'SD0' => 'Section SD',
            'SE0' => 'Section SE',
            'SE1' => 'Section SE',
            'SE4' => 'Section SE',
            'SF0' => 'Section SF',
            'SG6' => 'Section SG',
            'SJ0' => 'Section SJ',
            'SK0' => 'Section SK',
            'SM1' => 'Section SM',
            'SN1' => 'Section SN',
            'SN5' => 'Section SN',
            'SO3' => 'Section SO',
            'SP1' => 'Section SP',
            'SP2' => 'Section SP',
            'SP3' => 'Section SP',
            'SP4' => 'Section SP',
            'SP5' => 'Section SP',
            'SP6' => 'Section SP',
            'SP7' => 'Section SP',
            'SQ1' => 'Section SQ',
            'SQ2' => 'Section SQ',
            'SQ3' => 'Section SQ',
            'SQ4' => 'Section SQ',
            'SQ5' => 'Section SQ',
            'SQ6' => 'Section SQ',
            'SQ7' => 'Section SQ',
            'ST3' => 'Section ST',
            'ST4' => 'Section ST',
        ];

        foreach ($shelves as $name => $zone) {
            StorageLocation::firstOrCreate(
                [
                    'type' => 'shop_shelf',
                    'branch_id' => $branch->id,
                    'name' => $name,
                ],
                [
                    'code' => 'S-' . $name,
                    'zone_or_aisle' => $zone,
                    'description' => "Shop Shelf {$name} ({$zone})",
                ]
            );
        }
    }
}

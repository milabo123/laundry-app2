<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeOfService;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'service_name' => 'Cuci dan Gosok',
                'price'        => 5000,
                'description'  => 'Harga per kg',
            ],
            [
                'service_name' => 'Hanya Cuci',
                'price'        => 4500,
                'description'  => 'Harga per kg',
            ],
            [
                'service_name' => 'Hanya Gosok',
                'price'        => 5000,
                'description'  => 'Harga per kg',
            ],
            [
                'service_name' => 'Laundry Besar (Selimut/Karpet/Mantel/Sprei)',
                'price'        => 7000,
                'description'  => 'Harga per kg',
            ]
        ];

        foreach ($services as $svc) {
            TypeOfService::updateOrCreate(['service_name' => $svc['service_name']], $svc);
        }
    }
}

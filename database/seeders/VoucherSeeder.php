<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'KUKUS10',
                'name' => '10% Off',
                'description' => 'Get 10% off your next purchase.',
                'cost_in_coins' => 500,
                'discount_percent' => 10,
                'discount_amount' => 0,
                'type' => 'percent',
            ],
            [
                'code' => 'KUKUS20',
                'name' => '20% Off',
                'description' => 'Get 20% off your next purchase.',
                'cost_in_coins' => 900,
                'discount_percent' => 20,
                'discount_amount' => 0,
                'type' => 'percent',
            ],
            [
                'code' => 'FLAT10K',
                'name' => 'Rp 10.000 Off',
                'description' => 'Get Rp 10.000 off your next purchase.',
                'cost_in_coins' => 300,
                'discount_percent' => 0,
                'discount_amount' => 10000,
                'type' => 'fixed',
            ],
            [
                'code' => 'FLAT50K',
                'name' => 'Rp 50.000 Off',
                'description' => 'Get Rp 50.000 off your next purchase.',
                'cost_in_coins' => 1200,
                'discount_percent' => 0,
                'discount_amount' => 50000,
                'type' => 'fixed',
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::updateOrCreate(['code' => $voucher['code']], $voucher);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZiMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Area 1' => [
                'name' => 'Manajemen Perubahan',
                'subs' => [
                    '1.1' => 'Unit kerja telah membentuk tim untuk melakukan pembangunan Zona Integritas',
                    '1.10' => 'Dokumen Bukti Pimpinan berperan sebagai role model dalam pelaksanaan Pembangunan WBK/WBBM',
                    '1.11' => 'Dokumen Penetapan Agen Perubahan',
                ]
            ],
            'Area 2' => [
                'name' => 'Penataan Tatalaksana',
                'subs' => [
                    '2.1' => 'SOP telah diterapkan pada seluruh unit kerja',
                    '2.2' => 'E-Office telah diimplementasikan',
                ]
            ],
            'Area 3' => [
                'name' => 'Penataan Sistem Manajemen SDM Aparatur',
                'subs' => [
                    '3.1' => 'Perencanaan kebutuhan pegawai sesuai dengan kebutuhan organisasi',
                ]
            ],
            'Area 4' => [
                'name' => 'Penguatan Akuntabilitas',
                'subs' => [
                    '4.1' => 'Keterlibatan pimpinan dalam penyusunan perencanaan',
                ]
            ],
            'Area 5' => [
                'name' => 'Penguatan Pengawasan',
                'subs' => [
                    '5.1' => 'Pengendalian Gratifikasi',
                ]
            ],
            'Area 6' => [
                'name' => 'Peningkatan Kualitas Pelayanan Publik',
                'subs' => [
                    '6.1' => 'Standar Pelayanan',
                ]
            ],
        ];

        foreach ($data as $code => $info) {
            $area = \App\Models\ZiArea::create([
                'code' => $code,
                'name' => $info['name']
            ]);

            foreach ($info['subs'] as $subCode => $subName) {
                \App\Models\ZiComponent::create([
                    'zi_area_id' => $area->id,
                    'code' => $subCode,
                    'name' => $subName
                ]);
            }
        }
    }
}

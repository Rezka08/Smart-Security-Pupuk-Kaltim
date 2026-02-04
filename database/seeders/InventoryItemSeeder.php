<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Isi Pos
            [
                'item_name' => 'Kursi Petugas',
                'category' => 'Isi Pos',
                'description' => 'Kursi kerja untuk petugas jaga',
            ],
            [
                'item_name' => 'Meja Tulis',
                'category' => 'Isi Pos',
                'description' => 'Meja kerja petugas',
            ],
            [
                'item_name' => 'Handy Talky (HT)',
                'category' => 'Isi Pos',
                'description' => 'Alat komunikasi petugas',
            ],
            [
                'item_name' => 'Lampu Senter',
                'category' => 'Isi Pos',
                'description' => 'Senter untuk patroli malam',
            ],
            [
                'item_name' => 'Buku Log',
                'category' => 'Isi Pos',
                'description' => 'Buku catatan harian',
            ],
            
            // General
            [
                'item_name' => 'Kamera CCTV Zona 1',
                'category' => 'General',
                'description' => 'Kamera pengawas area zona 1',
            ],
            [
                'item_name' => 'Kamera CCTV Zona 2',
                'category' => 'General',
                'description' => 'Kamera pengawas area zona 2',
            ],
            [
                'item_name' => 'Kamera CCTV Zona 3',
                'category' => 'General',
                'description' => 'Kamera pengawas area zona 3',
            ],
            [
                'item_name' => 'Kamera CCTV Zona 4',
                'category' => 'General',
                'description' => 'Kamera pengawas area zona 4',
            ],
            [
                'item_name' => 'Pagar Keliling Area',
                'category' => 'General',
                'description' => 'Pagar pembatas area pabrik',
            ],
            [
                'item_name' => 'Lampu Penerangan Jalan',
                'category' => 'General',
                'description' => 'Lampu jalan area pabrik',
            ],
            [
                'item_name' => 'Pintu Gerbang Utama',
                'category' => 'General',
                'description' => 'Pintu masuk/keluar utama',
            ],
        ];

        foreach ($items as $item) {
            InventoryItem::create($item);
        }
    }
}
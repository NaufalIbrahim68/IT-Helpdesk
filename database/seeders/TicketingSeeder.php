<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticketing;
use Carbon\Carbon;

class TicketingSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hana'];
        $subjects = [
            'Komputer Tidak Menyala',
            'Jaringan Internet Lambat',
            'Monitor Berkedip',
            'Tidak Bisa Print',
            'Keyboard Rusak',
            'Laptop Overheat',
            'Blue Screen Windows',
            'Mouse Tidak Terdeteksi'
        ];
        $descriptions = [
            'Sudah ditekan power tapi tidak menyala.',
            'Internet sangat lambat saat meeting Zoom.',
            'Monitor sering mati sendiri.',
            'Printer tidak merespon perintah print.',
            'Beberapa tombol tidak berfungsi.',
            'Laptop cepat panas walau baru dinyalakan.',
            'Sering muncul blue screen saat buka aplikasi.',
            'Mouse tidak bergerak padahal tersambung.'
        ];
        $statuses = ['pending', 'open', 'solved'];

        for ($i = 0; $i < 30; $i++) {
            Ticketing::create([
                'name' => $names[array_rand($names)],
                'subject' => $subjects[array_rand($subjects)],
                'description' => $descriptions[array_rand($descriptions)],
                'status' => $statuses[array_rand($statuses)],
                'created_at' => Carbon::now()->subDays(rand(0, 10)),
            ]);
        }
    }
}

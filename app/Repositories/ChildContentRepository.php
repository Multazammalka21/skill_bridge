<?php

namespace App\Repositories;

use App\Models\Child;
use App\Models\Lesson;
use Carbon\Carbon;

class ChildContentRepository
{
    /**
     * Dapatkan modul/lesson yang sesuai dengan usia dan jenis disabilitas anak.
     */
    public function getModulesForChild(Child $child)
    {
        $usia = Carbon::parse($child->tanggal_lahir)->age;
        $tipeDunia = $child->jenis_disabilitas; // 'tunanetra' (audio) atau 'tunarungu' (visual)

        $tipe = $tipeDunia === 'tunanetra' ? 'audio' : 'visual';

        // Tentukan kategori usia berdasarkan umur anak
        $kategoriUsia = ($usia >= 5 && $usia <= 7) ? '5-7' : '8-10';

        // Filter berdasarkan tipe dunia, kategori usia, dan aktif
        $query = Lesson::active()
            ->forWorld($tipe)
            ->forAge($kategoriUsia)
            ->orderBy('urutan');

        return $query->get();
    }
}

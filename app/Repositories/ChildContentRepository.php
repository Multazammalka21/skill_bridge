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

        // Filter berdasarkan tipe dunia dan aktif
        $query = Lesson::active()->forWorld($tipe)->orderBy('urutan');

        // Jika ada filter usia di masa depan (opsional karena struktur db saat ini belum ada min_usia/max_usia, 
        // tapi repository pattern memungkinkan kita menambahkan filter kompleks ini di satu tempat).
        // $query->where('min_usia', '<=', $usia)->where('max_usia', '>=', $usia);

        return $query->get();
    }
}

<?php
use Carbon\Carbon;

function timeAgo($datetime)
{
    $time = Carbon::parse($datetime);
    $diff = $time->diff(Carbon::now());
    
    if ($diff->y > 0) {
        return $diff->y . ' tahun yang lalu';
    } elseif ($diff->m > 0) {
        return $diff->m . ' bulan yang lalu';
    } elseif ($diff->d > 0) {
        return $diff->d . ' hari yang lalu';
    } elseif ($diff->h > 0) {
        return $diff->h . ' jam yang lalu';
    } elseif ($diff->i > 0) {
        return $diff->i . ' menit yang lalu';
    } else {
        return 'baru saja';
    }
}
function formatRupiah($angka, $prefix = 'Rp ')
{
    $angka = $angka ?? 0;

    return $prefix . number_format($angka, 0, ',', '.');
}
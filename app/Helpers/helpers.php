<?php

if (!function_exists('format_rupiah_short')) {
    /**
     * Format angka Rupiah dengan format ringkas
     * Contoh: 2000000 -> 2M, 500000 -> 500jt, 1500 -> 1.5rb
     * 
     * @param float|int $amount
     * @return string
     */
    function format_rupiah_short($amount)
    {
        if ($amount >= 1000000000) {
            // Miliar (B = Billion / M untuk Miliar)
            $formatted = $amount / 1000000000;
            if ($formatted == floor($formatted)) {
                return number_format($formatted, 0) . 'M';
            }
            return number_format($formatted, 1) . 'M';
        } elseif ($amount >= 1000000) {
            // Juta
            $formatted = $amount / 1000000;
            if ($formatted == floor($formatted)) {
                return number_format($formatted, 0) . 'jt';
            }
            return number_format($formatted, 1) . 'jt';
        } elseif ($amount >= 1000) {
            // Ribu
            $formatted = $amount / 1000;
            if ($formatted == floor($formatted)) {
                return number_format($formatted, 0) . 'rb';
            }
            return number_format($formatted, 1) . 'rb';
        } else {
            // Dibawah 1000, tampilkan apa adanya
            return number_format($amount, 0);
        }
    }
}

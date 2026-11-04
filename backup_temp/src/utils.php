<?php

namespace App\Utils;

class Utils
{
    // --- Exercice A1 : capitalize ---
    public static function capitalize(?string $str): ?string
    {
        if ($str === null || $str === '') {
            return $str;
        }
        return ucfirst(strtolower($str));
    }

    // --- Exercice A1 : calculateAverage ---
    public static function calculateAverage(array $numbers): float
    {
        if (empty($numbers)) {
            return 0.0;
        }
        return round(array_sum($numbers) / count($numbers), 2);
    }

    // --- Exercice A1 : slugify ---
    public static function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    // --- Exercice A1 : clamp ---
    public static function clamp(float|int $value, float|int $min, float|int $max): float|int
    {
        return max($min, min($max, $value));
    }

    // --- Exercice A4 : sortStudents (TDD) ---
    public static function sortStudents(?array $students, string $sortBy = 'name', string $order = 'asc'): array
    {
        if (empty($students)) {
            return [];
        }

        $sorted = $students;
        usort($sorted, function ($a, $b) use ($sortBy, $order) {
            $valA = $a[$sortBy] ?? null;
            $valB = $b[$sortBy] ?? null;

            if ($valA === $valB) {
                return 0;
            }

            $comparison = ($valA < $valB) ? -1 : 1;
            return ($order === 'desc') ? -$comparison : $comparison;
        });

        return $sorted;
    }
}
<?php

namespace App\Components;

class Businame
{
    public static function generate($letters, int $length = 0)
    {
        $vowels = ['a', 'e', 'i', 'o', 'u'];

        $consonants = [
            'b', 'c', 'd', 'f', 'g', 'h',
            'j', 'k', 'l', 'm', 'n', 'p',
            'r', 's', 't', 'v', 'y', 'z'
        ];

        // $parseLetters = explode('|', $letters);
        $parseLetters = str_split($letters);

        if ($length === 0) {
            $length = count($parseLetters);
        }

        $letterIndex = 0;
        $finalWord = [];

        for ($i = 0; $i < $length; $i++) {
            switch ($parseLetters[$letterIndex]) {
                case '<':
                    $finalWord[] = $vowels[array_rand($vowels)];
                    break;
                case '>':
                    $finalWord[] = $consonants[array_rand($consonants)];
                    break;
                default:
                    $finalWord[] = $parseLetters[$letterIndex];
                    break;
            }

            if (count($parseLetters) - 1 > $letterIndex) {
                $letterIndex++;
            } else {
                $letterIndex = 0;
            }
        }

        return implode('', $finalWord);
    }
}

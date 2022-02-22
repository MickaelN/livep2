<?php
namespace Utils;

class Str{

    public static function getRandom(int $size = 60):string{
        $lowercaseLetter = [];
        $letter = 'a';
        for($i = 0; $i <= 25 ; $i++){
            $lowercaseLetter[$i] = $letter++;
        }

        $uppercaseLetter = array_map('strtoupper',$lowercaseLetter);
        
        $numbers = [];
        for($i = 0 ; $i <= 9; $i++){
            $numbers[$i] = $i;
        }
        
        $specialchars = ['.',':',';','!','@'];

        $finalArray = array_merge($lowercaseLetter,$uppercaseLetter,$specialchars,$numbers);

        $randomString = '';

        for($i = 0; $i < $size; $i++){
            shuffle($finalArray);
            $randomString .= $finalArray[array_rand($finalArray,1)];
        }
        return $randomString;
    }
}
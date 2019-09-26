<?php

namespace App\Service;

class Random
{
    public function randomPassword($choice = 'all', $size = 10)
    {
        $numeric = '0123456789';
        $alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        switch ($choice) {
            case 'numeric':
                $code = $numeric;
                break;
            case 'alpha':
                $code = $alpha;
                break;
            case 'all':
                $code = $numeric . $alpha;
                break;
        }

        $pass = array();
        $alphaLength = strlen($code) - 1;

        for ( $i=0 ; $i<$size ; $i++ ) {
            $n = rand(0, $alphaLength);
            $pass[] = $code[$n];
        }

        return implode($pass);
    }
}

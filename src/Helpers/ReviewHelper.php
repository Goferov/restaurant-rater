<?php

namespace App\Helpers;

class ReviewHelper implements ReviewHelperI{
    public function generateStars($rate) {
        $roundedRate = $rate ? round($rate) : 0;
        $stars = '';
        foreach (range(1, 5) as $i) {
            if ($i <= $roundedRate) {
                $stars .= '<i class="fa fa-star f-yellow"></i>'."\r\n";
            } else {
                $stars .= '<i class="fa fa-star f-lgray"></i>'."\r\n";
            }
        }
        return $stars;
    }
}
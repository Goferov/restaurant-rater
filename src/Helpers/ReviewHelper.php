<?php

namespace App\Helpers;

class ReviewHelper {
    public function generateStars($rate) {
        $roundedRate = round($rate);
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
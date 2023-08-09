<?php

namespace Taskforce\Helpers;

class RateHelper
{
    /**
     * Возвращает HTML-код со звездами, отображающими рейтинг.
     *
     * @param float $rate значение рейтинга.
     * @return string HTML-код со звездами.
     */
    public static function getStars(float $rate): string
    {
        $count = round($rate);
        $stars = '';

        for ($i = 0; $i < $count; $i++) {
            $stars .= "<span class=\"fill-star\">&nbsp;</span>";
        }

        for ($i = 0; $i < 5 - $count; $i++) {
            $stars .= "<span>&nbsp;</span>";
        }

        return $stars;
    }
}

<?php

/**
 * This file is part of the pekkis-pathfinder package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pekkis\CurrencyConverter\RateProvider;

use Httpful\Request;
use Money\Currency;

class FixerIoRateProvider implements RateProvider
{

    public function getRates(Currency $base)
    {
        $rates = (array) Request::get(
            sprintf('http://api.fixer.io/latest?base=%s', $base->getName())
        )->send()->body->rates;

        $ret = [];
        foreach ($rates as $key => $rate) {
            $ret[$key] = $rate;
        }

        return $ret;
    }


}

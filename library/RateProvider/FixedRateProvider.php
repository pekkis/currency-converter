<?php

/**
 * This file is part of the pekkis-pathfinder package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pekkis\CurrencyConverter\RateProvider;

use Money\Currency;

class FixedRateProvider implements RateProvider
{
    public function __construct($rates)
    {
        $this->rates = $rates;
    }

    public function getRates(Currency $base)
    {
        return $this->rates[$base->getName()];
    }
}

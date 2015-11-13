<?php

/**
 * This file is part of the pekkis-pathfinder package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pekkis\CurrencyConverter;

use Money\CurrencyPair;
use Money\Money;
use Money\Currency;
use Pekkis\CurrencyConverter\RateProvider\RateProvider;
use Stash\Driver\BlackHole;
use Stash\Pool;

class CurrencyConverter
{
    /**
     * @var RateProvider
     */
    private $rates;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @param RateProvider $rates
     * @param Pool $pool
     * @param string $keyPrefix
     */
    public function __construct(RateProvider $rates, Pool $pool = null, $ttl = 60)
    {
        if (!$pool) {
            $pool = new Pool(new BlackHole());
        }

        $this->rates = $rates;
        $this->pool = $pool;
        $this->ttl = $ttl;
    }

    public function convert(Money $money, $currency)
    {
        if (!$currency instanceof Currency) {
            $currency = new Currency($currency);
        }

        if ($money->getCurrency()->equals($currency)) {
            return $money;
        }

        $rates = $this->getRates($money->getCurrency());

        if (!isset($rates[$currency->getName()])) {
            throw new RuntimeException(
                sprintf(
                    'Could not get rate for currency %s',
                    $currency->getName()
                )
            );
        }

        $pair = new CurrencyPair(
            $money->getCurrency(),
            $currency,
            $rates[$currency->getName()]
        );

        return $pair->convert($money);
    }

    private function getRates(Currency $currency)
    {
        $rates = $this->pool->getItem($currency->getName());

        if ($rates->isMiss()) {

            $newRates = $this->rates->getRates($currency);

            $rates->set(
                $newRates,
                $this->ttl
            );
        }
        return $newRates;
    }
}

<?php

namespace Pekkis\CurrencyConverter\Tests;

use Money\Currency;
use Money\Money;
use Pekkis\CurrencyConverter\CurrencyConverter;
use Pekkis\CurrencyConverter\RateProvider\FixedRateProvider;
use Pekkis\CurrencyConverter\RateProvider\FixerIoRateProvider;
use Pekkis\CurrencyConverter\RuntimeException;

class CurrencyConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sameCurrencyReturnsSameMoney()
    {
        $converter = new CurrencyConverter(
            new FixerIoRateProvider()
        );

        $money = new Money(2500, new Currency('EUR'));

        $this->assertSame(
            $money,
            $converter->convert($money, 'EUR')
        );
    }

    /**
     * @test
     */
    public function convertsToSEK()
    {
        $converter = new CurrencyConverter(
            new FixedRateProvider([
                'EUR' => [
                    'SEK' => 11,
                ]
            ])
        );

        $money = new Money(1000, new Currency('EUR'));

        $sek = $converter->convert($money, 'SEK');

        $this->assertInstanceOf(Money::class, $sek);
        $this->assertEquals('SEK', $sek->getCurrency()->getName());
        $this->assertEquals(11000, $sek->getAmount());
    }

    /**
     * @test
     */
    public function failsIfNoRate()
    {
        $converter = new CurrencyConverter(
            new FixedRateProvider([
                'EUR' => [
                    'SEK' => 11,
                ]
            ])
        );

        $money = new Money(1000, new Currency('EUR'));

        $this->setExpectedException(RuntimeException::class);
        $converter->convert($money, 'NOK');
    }
}

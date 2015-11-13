# pekkis/currency-converter

A library for converting between currencies

## Use case

Didn't find a good library (utilizing money class / reliable math) for currency conversion. Needed one. Made one.

## Quickstart

```php
<?php

$converter = new CurrencyConverter(
    new FixerIoRateProvider()
);

$money = new Money(2500, new Currency('EUR'));
$sek = $converter->convert($money, 'SEK');

## There's more

Caching and different providers and such. Read code. Kood kood.

Pull requests are welcome.

# PHP-MP3
[![](https://img.shields.io/github/license/falahati/PHP-MP3.svg?style=flat-square)](https://github.com/falahati/PHP-MP3/blob/master/LICENSE)
[![](https://img.shields.io/github/commit-activity/y/falahati/PHP-MP3.svg?style=flat-square)](https://github.com/falahati/PHP-MP3/commits/master)
[![](https://img.shields.io/github/issues/falahati/PHP-MP3.svg?style=flat-square)](https://github.com/falahati/PHP-MP3/issues)

PHP-MP3 is a simple library for reading and manipulating MPEG audio (MP3).

This library is based on a similar project with the same name written by [thegallagher](https://github.com/thegallagher/PHP-MP3) which it-self was based on an script from [regin](https://web.archive.org/web/20120211192505/http://www.sourcerally.net/Scripts/20-PHP-MP3-Class).

## How to get
[![](https://img.shields.io/packagist/dt/falahati/php-mp3.svg?style=flat-square)](https://packagist.org/packages/falahati/php-mp3)
[![](https://img.shields.io/packagist/v/falahati/php-mp3.svg?style=flat-square)](https://packagist.org/packages/falahati/php-mp3)

You can install and use this library with composer:
```
composer require falahati/php-mp3:dev-master
```

## Features

* Correctly identifies MPEG Audio Version 1, 2 and 2.5
* Correctly identifies MPEG Audio Profile 1, 2 and 3
* Correctly extracts bitrate and sample rate information
* Correctly calculates MPEG Audio stream duration
* Frame address recovery allows the code to correctly parse corrupt data
* Trim (Cut) a MPEG Audio data and merge multiple MPEG audio streams
* Ability to strip MPEG Audio data from starting and ending ID3 (or similar) metadata information

## Donation
Donations assist development and are greatly appreciated; also always remember that [every coffee counts!](https://media.makeameme.org/created/one-simply-does-i9k8kx.jpg) :)

[![](https://img.shields.io/badge/fiat-PayPal-8a00a3.svg?style=flat-square)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=WR3KK2B6TYYQ4&item_name=Donation&currency_code=USD&source=url)
[![](https://img.shields.io/badge/crypto-CoinPayments-8a00a3.svg?style=flat-square)](https://www.coinpayments.net/index.php?cmd=_donate&reset=1&merchant=820707aded07845511b841f9c4c335cd&item_name=Donate&currency=USD&amountf=20.00000000&allow_amount=1&want_shipping=0&allow_extra=1)
[![](https://img.shields.io/badge/shetab-ZarinPal-8a00a3.svg?style=flat-square)](https://zarinp.al/@falahati)

**--OR--**

You can always donate your time by contributing to the project or by introducing it to others.

## Samples

Strip ID3 tags from a MP3 file:
```PHP
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->stripTags()->saveFile("new.mp3");
```

Cut a MP3 file to extract a 30sec preview starting at the 10th second:
```PHP
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->trim(10, 30)->saveFile("new.mp3");
```

Append memory stored MP3 data to the end of a MP3 file:
```PHP
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->append(\falahati\PHPMP3\MpegAudio::fromData(base64_decode("/**BASE64-DATA**/")))->saveFile("new.mp3");
```

Extracting MP3 file total duration:
```PHP
echo \falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->getTotalDuration();
```

## To Do List

* Add Unit Tests
* Ability to load and manipulate data directly from, and to a `resource`
* Ability to add simple ID3 metadata information to the MPEG Audio before saving

## License

PHP-MP3 library is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

PHP-MP3 library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with PHP-MP3 library. If not, see <http://www.gnu.org/licenses/>.

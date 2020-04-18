# PHP-MP3
[![](https://img.shields.io/github/license/falahati/PHP-MP3.svg?style=flat-square)](https://github.com/falahati/PHP-MP3/blob/master/LICENSE)
[![](https://img.shields.io/github/commit-activity/y/falahati/PHP-MP3.svg?style=flat-square)](https://github.com/falahati/PHP-MP3/commits/master)
[![](https://img.shields.io/github/issues/falahati/PHP-MP3.svg?style=flat-square)](https://github.com/falahati/PHP-MP3/issues)

PHP-MP3 is a simple library for reading and manipulating MPEG audio (MP3).

This library is based on a similar project with the same name written by [thegallagher](https://github.com/thegallagher/PHP-MP3) which it-self was based on an script from [regin](https://web.archive.org/web/20120211192505/http://www.sourcerally.net/Scripts/20-PHP-MP3-Class).

This is not an encoder or decoder and therefore can't change the MP3 properties like bitrate, sample size, and sample rate.
It's a MPEG Audio **parser** and therefore it should only be used to modify/create/read valid MP3 containers.

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

## Help me fund my own Death Star

[![](https://img.shields.io/badge/crypto-CoinPayments-8a00a3.svg?style=flat-square)](https://www.coinpayments.net/index.php?cmd=_donate&reset=1&merchant=820707aded07845511b841f9c4c335cd&item_name=Donate&currency=USD&amountf=20.00000000&allow_amount=1&want_shipping=0&allow_extra=1)
[![](https://img.shields.io/badge/shetab-ZarinPal-8a00a3.svg?style=flat-square)](https://zarinp.al/@falahati)
[![](https://img.shields.io/badge/usd-Paypal-8a00a3.svg?style=flat-square)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ramin.graphix@gmail.com&lc=US&item_name=Donate&no_note=0&cn=&curency_code=USD&bn=PP-DonationsBF:btn_donateCC_LG.gif:NonHosted)

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
Copyright (C) 2017-2020 Soroush Falahati

This project is licensed under the GNU Lesser General Public License ("LGPL") and therefore can be used in closed source or commercial projects. 
However, any commit or change to the main code must be public and there should be a read me file along with the DLL clarifying the license and its terms as part of your project as well as a hyperlink to this repository. [Read more about LGPL](LICENSE).
PHP-MP3
=========================

PHP-MP3 is a simple library for reading and manipulating MPEG audio (MP3).

This library is based on a similar project with the same name written by [thegallagher](https://github.com/thegallagher/PHP-MP3) which it-self was based on an script from [regin](https://web.archive.org/web/20120211192505/http://www.sourcerally.net/Scripts/20-PHP-MP3-Class).

Features
--------

* Correctly identifies MPEG Audio Version 1, 2 and 2.5
* Correctly identifies MPEG Audio Profile 1, 2 and 3
* Correctly extracts bitrate and sample rate information
* Correctly calculates MPEG Audio stream duration
* Frame address recovery allows the code to correctly parse corrupt data
* Trim (Cut) a MPEG Audio data and merge multiple MPEG audio streams
* Ability to strip MPEG Audio data from starting and ending ID3 (or similar) metadata information

To Do List
--------

* Add Unit Tests
* Ability to load and manipulate data from directly from, and to a `resource`
* Ability to add simple ID3 metadata information to the MPEG Audio before saving

License
--------
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
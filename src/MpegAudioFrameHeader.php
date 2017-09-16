<?php
/*
 *  This file is part of PHP-MP3 library.
 *
 *  PHP-MP3 library is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  PHP-MP3 library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.

 *  You should have received a copy of the GNU Lesser General Public License
 *  along with PHP-MP3 library. If not, see <http://www.gnu.org/licenses/>.
 */
namespace falahati\PHPMP3 {

	/**
	 * This class represents a MPEG audio frame's header
	 * @author		Soroush Falahati https://falahati.net
	 * @copyright	Soroush Falahati (C) 2017
	 * @license		LGPL v3 https://www.gnu.org/licenses/lgpl-3.0.en.html
	 * @link		https://github.com/falahati/PHP-MP3
	 */
	class MpegAudioFrameHeader
	{
		/**
		 * MPEG Audio Version 1
		 */
		const Version_10	= 1;

		/**
		 * MPEG Audio Version 2
		 */
		const Version_20	= 2;

		/**
		 * MPEG Audio Version 2.5
		 */
		const Version_25	= 2.5;

		/**
		 * MPEG Audio Profile 1
		 */
		const Profile_1		= 1;

		/**
		 * MPEG Audio Profile 2
		 */
		const Profile_2		= 2;

		/**
		 * MPEG Audio Profile 3
		 */
		const Profile_3		= 3;

		/**
		 * Holds the bit rate of the frame
		 * @var int
		 */

		private $bitRate = 0;

		/**
		 * Holds the sample rate of the frame
		 * @var int
		 */
		private $sampleRate = 0;

		/**
		 * Holds the MPEG audio version of the frame
		 * @var int
		 */
		private $version = -1;

		/**
		 * Holds the MPEG audio profile of the frame
		 * @var int
		 */
		private $profile = -1;

		/**
		 * Holds the estimated duration of this frame
		 * @var double
		 */
		private $duration = 0.0;

		/**
		 * Holds the frame's data offset in MPEG audio
		 * @var int
		 */
		private $offset = 0;

		/**
		 * Holds the frame's data length in MPEG audio
		 * @var int
		 */
		private $length = 0;

		/**
		 * Holds the frame's ending padding in bytes
		 * @var int
		 */
		private $padding = 0;

		/**
		 * Holds a list of all bytes with their equivalent binary representation
		 * @var string[]
		 */
		private static $binaryTable = [];

		/**
		 * Gets the frame's bit rate
		 * @return int
		 */
		public function getBitRate() {
			return $this->bitRate;
		}

		/**
		 * Gets the frame's sample rate
		 * @return int
		 */
		public function getSampleRate() {
			return $this->sampleRate;
		}

		/**
		 * Gets the frame's MPEG audio version number
		 * @return int
		 */
		public function getVersion() {
			return $this->version;
		}

		/**
		 * Gets the frame's MPEG audio profile number
		 * @return int
		 */
		public function getProfile() {
			return $this->profile;
		}

		/**
		 * Gets the frame's estimated duration
		 * @return int
		 */
		public function getDuration() {
			return $this->duration;
		}

		/**
		 * Gets the frame's data offset in MPEG audio
		 * @return int
		 */
		public function getOffset() {
			return $this->offset;
		}

		/**
		 * Gets the frame's data length in MPEG audio
		 * @return int
		 */
		public function getLength() {
			return $this->length;
		}

		/**
		 * Gets the frame's ending padding in bytes
		 * @return int
		 */
		public function getPadding() {
			return $this->padding;
		}

		/**
		 * Creates a new instance of this class, also fills the binary table for later use
		 */
		private function __construct() {
			if (!self::$binaryTable) {
				for ($i = 0; $i < 256; $i ++) {
					self::$binaryTable[chr($i)] = sprintf('%08b', $i);
				}
			}
		}

		/**
		 * Tries to parse and return a new MpegAudioFrameHeader object from the provided data, false on failure
		 * @param string $header
		 * @param int $offset
		 * @return bool|\falahati\PHPMP3\MpegAudioFrameHeader
		 */
		public static function tryParse($header, $offset) {
			$frame = new self();
			$frame->offset = $offset;

			$bytes  = array();
			for ($i = 0; $i < 4; $i ++) {
				$bytes[] = self::$binaryTable[$header[$i]];
			}


			// Check header marker
			if ($bytes[0] !== '11111111' || substr($bytes[1], 0, 3) !== '111') {
				return false;
			}


			// Get version
			switch (substr($bytes[1], 3, 2)) {
				case '01':
					// Reserved
					return false;
				case '00':
					$frame->version = self::Version_25;
					break;
				case '10':
					$frame->version = self::Version_20;
					break;
				case '11':
					$frame->version = self::Version_10;
					break;
			}


			// Get profile
			switch (substr($bytes[1], 5, 2)) {
				case '01':
					$frame->profile = self::Profile_3;
					break;
				case '00':
					// Reserved
					return false;
				case '10':
					$frame->profile = self::Profile_2;
					break;
				case '11':
					$frame->profile = self::Profile_1;
					break;
			}


			// Get bitrate
			$bitRates = array(
				'0000' => array(0, 0, 0, 0, 0),
				'0001' => array(32, 32, 32, 32, 8),
				'0010' => array(64, 48, 40, 48, 16),
				'0011' => array(96, 56, 48, 56, 24),
				'0100' => array(128, 64, 56, 64, 32),
				'0101' => array(160, 80, 64, 80, 40),
				'0110' => array(192, 96, 80, 96, 48),
				'0111' => array(224, 112, 96, 112, 56),
				'1000' => array(256, 128, 112, 128, 64),
				'1001' => array(288, 160, 128, 144, 80),
				'1010' => array(320, 192, 160, 160, 96),
				'1011' => array(352, 224, 192, 176, 112),
				'1100' => array(384, 256, 224, 192, 128),
				'1101' => array(416, 320, 256, 224, 144),
				'1110' => array(448, 384, 320, 256, 160),
				'1111' => array(-1, -1, -1, -1, -1)
			);
			$frame->bitRate = -1;
			$bitRateIndex  = substr($bytes[2], 0, 4);
			if ($frame->version == self::Version_10) {
				switch ($frame->profile) {
					case self::Profile_1:
						$frame->bitRate = $bitRates[$bitRateIndex][0];
						break;
					case self::Profile_2:
						$frame->bitRate = $bitRates[$bitRateIndex][1];
						break;
					case self::Profile_3:
						$frame->bitRate = $bitRates[$bitRateIndex][2];
						break;
				}
			} else {
				switch ($frame->profile) {
					case self::Profile_1:
						$frame->bitRate = $bitRates[$bitRateIndex][3];
						break;
					case self::Profile_2:
					case self::Profile_3:
						$frame->bitRate = $bitRates[$bitRateIndex][4];
						break;
				}
			}
			if ($frame->bitRate <= 0) {
				// Invalid value or bitrate needs calculation
				return false;
			}


			// Get sample rate
			$sampleRates = array(
				self::Version_10 => array(
					'00' => 44100,
					'01' => 48000,
					'10' => 32000,
					'11' => 0
				),
				self::Version_20 => array(
					'00' => 22050,
					'01' => 24000,
					'10' => 16000,
					'11' => 0
				),
				self::Version_25 => array(
					'00' => 11025,
					'01' => 12000,
					'10' => 8000,
					'11' => 0
				)
			);
			$sampleRateIndex = substr($bytes[2], 4, 2);
			$frame->sampleRate = $sampleRates[$frame->version][$sampleRateIndex];
			if ($frame->sampleRate <= 0) {
				// Invalid sample rate value
				return false;
			}


			// Get frame padding
			$frame->padding = substr($bytes[2], 6, 1) ? 1 : 0;


			// Calculate frame length
			if ($frame->profile == self::Profile_1) {
				$frame->length = (((12 * $frame->bitRate * 1000) / $frame->sampleRate) + $frame->padding) * 4;
			} else if ($frame->profile == self::Profile_2 || $frame->profile == self::Profile_3) {
				$frame->length = ((144 * $frame->bitRate * 1000) / $frame->sampleRate) + $frame->padding;
			}
			$frame->length = floor($frame->length);
			if ($frame->length <= 0) {
				// Invalid frame length
				return false;
			}


			// Calculate frame duration
			$frame->duration = $frame->length * 8 / ($frame->bitRate * 1000);

			// Return result
			return $frame;
		}
	}
}
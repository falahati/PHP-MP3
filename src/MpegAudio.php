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
	 * This class represents and is able to read and manipulate a MPEG audio
	 * @author		Soroush Falahati https://falahati.net
	 * @copyright	Soroush Falahati (C) 2017
	 * @license		LGPL v3 https://www.gnu.org/licenses/lgpl-3.0.en.html
	 * @link		https://github.com/falahati/PHP-MP3
	 */
	class MpegAudio
	{
		/**
		 * Holds MPEG data in memory
		 * @var string
		 */
		private $memory = "";

		/**
		 * Holds an integer value pointing in a specific location in the memory
		 * @var int
		 */
		private $memoryPointer = 0;

		/**
		 * Holds the length of the memory
		 * @var int
		 */
		private $memoryLength = 0;

		/**
		 * Holds MPEG resource stream
		 * @var resource
		 */
		private $resource = null;

		/**
		 * Holds an integer number representing the total number of MPEG audio frames
		 * @var int
		 */
		private $frames = -1;

		/**
		 * Holds a float number representing the total duration of the MPEG audio data
		 * @var double
		 */
		private $duration = 0.0;

		/**
		 * Holds an array of frame's starting offsets
		 * @var int[]|array
		 */
		private $frameOffsetTable = [];

		/**
		 * Holds an array of frame's starting time
		 * @var double[]|array
		 */
		private $frameTimingTable = [];

		/**
		 * Loads a MP3 file and returns a newly created MpegAudio object, or false on failure
		 * @param string $path
		 * @return bool|\falahati\PHPMP3\MpegAudio
		 */
		public static function fromFile($path) {
			$inMemory = true;
			if ($inMemory) {
				return self::fromData(file_get_contents($path));
			} else {
				//return self::fromResource(fopen($path, "cb"));
			}
		}

		/**
		 * Creates and returns a MpegAudio object and loads binary data, or false on failure
		 * @param string $data
		 * @return bool|\falahati\PHPMP3\MpegAudio
		 */
		public static function fromData($data) {
			if (!is_string($data)) {
				return false;
			}
			$audio = new MpegAudio();
			$audio->memory = $data;
			$audio->memoryLength = strlen($audio->memory);
			return $audio;
		}

		//public static function fromResource($resource) {
		//    if (!is_resource($resource)) {
		//        return false;
		//    }
		//    $audio = new MpegAudio();
		//    $audio->resource = $resource;
		//    return $audio;
		//}

		/**
		 * Reads a series of bytes from memory or resource
		 * @param int $length
		 * @param int $index
		 * @return string
		 */
		private function read($length = 0, $index = -1) {
			if ($this->resource === null) {
				if ($index < 0) {
					$index = $this->memoryPointer;
				}

				if ($length == 0) {
					$length = $this->memoryLength - $index;
				}

				$this->memoryPointer = min($this->memoryLength, $index + $length);
				if ($this->memoryPointer >= $this->memoryLength) {
					return false;
				}

				return substr($this->memory, $index, $length);
			} else {
				// TODO STREAM
			}
		}

		/**
		 * Writes a series of bytes to the memory or resource, replacing older content or appending to the end
		 * @param string $data
		 * @param int $index
		 * @return int
		 */
		private function write($data, $index = -1) {
			if ($this->resource === null) {
				$length = strlen($data);
				$this->slice($length, $index);
				return $this->insert($data, $index);
			} else {
				// TODO STREAM
			}
		}

		/**
		 * Inserts a series of bytes to the memory or resource, increasing size
		 * @param string $data
		 * @param int $index
		 * @return int
		 */
		private function insert($data, $index = -1) {
			if ($this->resource === null) {
				if ($index < 0) {
					$index = $this->memoryPointer;
				}
				$length = strlen($data);
				$this->memoryPointer = $index + $length;
				$this->memory = substr($this->memory, 0, $index) . $data . substr($this->memory, $index);
				$this->memoryLength += strlen($data);
				return $length;
			} else {
				// TODO STREAM
			}
		}

		/**
		 * Removing parts of the memory or resource, decreasing size
		 * @param int $length
		 * @param int $index
		 * @return int
		 */
		private function slice($length = 0, $index = -1) {
			if ($this->resource === null) {
				if ($index < 0) {
					$index = $this->memoryPointer;
				}
                if ($length == 0) {
                    $length = $this->memoryLength - $index;
                }
				$this->memoryPointer = $index;
				$length = max(min($this->memoryLength - $index, $length), 0);
				$this->memory = substr($this->memory, 0, $index) . substr($this->memory, $index + $length);
				$this->memoryLength -= $length;
				return $length;
			} else {
				// TODO STREAM
			}
		}

		/**
		 * Seeking pointer to a specific location, or returns the current pointer location
		 * @param int $index
		 * @return int|bool
		 */
		private function seek($index = -1) {
			if ($index < 0) {
				if ($this->resource === null) {
					return $this->memoryPointer;
				} else {
					// TODO STREAM
				}
			}
			if ($this->resource === null) {
				$this->memoryPointer = $index;
				return true;
			} else {
				// TODO STREAM
			}
		}

		/**
		 * Creates an empty MPEG audio class
		 */
		public function __construct() {
			$this->reset();
			$this->memory = "";
		}

		/**
		 * Resets all extracted data and marks them for recalculation
		 */
		private function reset() {
			$this->frames = -1;
			$this->frameTimingTable = [];
			$this->frameOffsetTable = [];
			$this->duration = 0.0;
		}

		/**
		 * Calculate and extract MPEG audio information
		 */
		private function analyze() {
			$offset = $this->getStart();
			$this->frames = 0;
			$this->frameOffsetTable = [];
			$this->frameTimingTable = [];
			$this->duration = 0.0;
			if ($offset !== false) {
				while(true) {
					$frameHeader = $this->readFrameHeader($offset);
					if ($frameHeader === false) {
						// Try recovery
						$offset = $this->getStart($offset);
						if ($offset !== false) {
							continue;
						}
						break;
					}
					$this->duration += $frameHeader->getDuration();
					$this->frameOffsetTable[$this->frames] = $frameHeader->getOffset();
					$this->frameTimingTable[$this->frames] = $this->duration;
					$this->frames++;
					$offset = $frameHeader->getOffset() + $frameHeader->getLength();
					unset($frameHeader);
				}
			}
		}

		/**
		 * Calculates the starting offset of the first frame after the specified offset
		 * @param int $offset
		 * @return bool|int
		 */
		private function getStart($offset = 0) {
			$offset--;
			while (true) {
				$offset++;
				$byte = $this->read(1, $offset);
				if ($byte === false) {
					return false;
				}
				if ($byte != chr(255)) {
					continue;
				}
				$frameHeader = $this->readFrameHeader($offset);
				if ($frameHeader === false) {
					continue;
				}
				$frameHeader = $this->readFrameHeader($frameHeader->getOffset() + $frameHeader->getLength());
				if ($frameHeader === false) {
					continue;
				}
				return $offset;
			}
		}

		/**
		 * Reads a frame's header and returns a MpegAudioFrameHeader object
		 * @param int $offset
		 * @return bool|\falahati\PHPMP3\MpegAudioFrameHeader
		 */
		private function readFrameHeader($offset) {
			$bytes = $this->read(4, $offset);
			return MpegAudioFrameHeader::tryParse($bytes, $offset);
		}

		/**
		 * Saves this MPEG audio to a file, returns this object
		 * @param string $path
		 * @return \falahati\PHPMP3\MpegAudio
		 */
		public function saveFile($path) {
			if ($this->resource === null) {
				file_put_contents($path, $this->memory);
				return $this;
			} else {
				fflush($this->resource);
				// TODO COPY STREAM
				return $this;
			}
		}

		/**
		 * Closes all resources and frees the memory, returns MPEG audio as binary string, or a boolean value indicating the operation success
		 * @return bool|string
		 */
		public function close() {
			if ($this->resource === null) {
				$data = $this->memory;
				$this->memory = "";
				$this->memoryLength = 0;
				$this->memoryPointer = 0;
				return $data;
			}
			if ($this->resource !== null && fclose($this->resource)) {
				$this->resource = null;
				return true;
			}
			return false;
		}

		/**
		 * Gets the number of frames in this MPEG audio
		 * @return int
		 */
		public function getFrameCounts() {
			if ($this->frames < 0) {
				$this->analyze();
			}
			return $this->frames;
		}

		/**
		 * Gets the total duration of this MPEG audio in seconds
		 * @return double
		 */
		public function getTotalDuration() {
			if ($this->getFrameCounts()) {
				return $this->duration;
			}
			return 0.0;
		}

		/**
		 * Gets a MpegAudioFrameHeader object reperesenting the header of an MPEG audio frame, or false or failure
		 * @param int $index
		 * @return bool|\falahati\PHPMP3\MpegAudioFrameHeader
		 */
		public function getFrameHeader($index) {
			if ($index >= 0 && $index < $this->getFrameCounts()) {
				return $this->readFrameHeader($this->frameOffsetTable[$index]);
			}
			return false;
		}

		/**
		 * Gets a frame's data (including header) as a binary string, or false or failure
		 * @param int $index
		 * @return bool|string
		 */
		public function getFrameData($index) {
			$frameHeader = $this->getFrameHeader($index);
			if ($frameHeader !== false) {
				return $this->read($frameHeader->getOffset(), $frameHeader->getLength());
			}
			return false;
		}

		/**
		 * Removes a set of frames from this MPEG audio, returns this object
		 * @param int $index
		 * @param int $count
		 * @return \falahati\PHPMP3\MpegAudio
		 */
		public function removeFrame($index, $count = 1) {
			if ($count < 0) {
				$index += $count;
				$count *= -1;
			}
			if ($index < 0 || $index >= $this->getFrameCounts()) {
				return $this;
			}
			$count = min($this->getFrameCounts() - $index, $count);
			if ($count == 0) {
				return $this;
			}
			$firstFrameHeader = $this->getFrameHeader($index);
			$lastFrameHeader = $this->getFrameHeader($index + ($count - 1));
			$this->slice(($lastFrameHeader->getOffset() + $lastFrameHeader->getLength()) - $firstFrameHeader->getOffset(), $firstFrameHeader->getOffset());
			$this->reset();
			return $this;
		}

		/**
		 * Appends a potion of a MPEG audio to this MPEG audio, returns this object
		 * @param \falahati\PHPMP3\MpegAudio $srcAudio
		 * @param int $index
		 * @param int $length
		 * @return \falahati\PHPMP3\MpegAudio
		 */
		public function append(\falahati\PHPMP3\MpegAudio $srcAudio, $index = 0, $length = -1) {
			if ($index < 0 || $index >= $srcAudio->getFrameCounts()) {
				return $this;
			}
			if ($length < 0) {
				$length = $srcAudio->getFrameCounts() - $index;
			}
			$length = min($srcAudio->getFrameCounts() - $index, $length);

			$srcFirstFrameHeader = $srcAudio->getFrameHeader($index);
			$srcLastFrameHeader = $srcAudio->getFrameHeader($index + ($length - 1));
			$data = $srcAudio->read(($srcLastFrameHeader->getOffset() + $srcLastFrameHeader->getLength()) - $srcFirstFrameHeader->getOffset(), $srcFirstFrameHeader->getOffset());
			if ($data) {
				$endOfStream = 0;
				if ($this->getFrameCounts() > 0) {
					$frameHeader = $this->getFrameHeader($this->getFrameCounts() - 1);
					if ($frameHeader !== false) {
						$endOfStream = $frameHeader->getOffset() + $frameHeader->getLength();
					}
				}
				$this->insert($data, $endOfStream);
				$this->analyze();
			}
			return $this;
		}

		/**
		 * Trims this MPEG audio by removing all frames except the parts that are selected by time in seconds, returns this object
		 * @param int $startTime
		 * @param int $duration
		 * @return \falahati\PHPMP3\MpegAudio
		 */
		public function trim($startTime, $duration = 0) {
			if ($startTime < 0) {
				$startTime = $this->getTotalDuration() + $startTime;
			}
			if ($duration <= 0) {
				$duration = $this->getTotalDuration() - $startTime;
			}
			$endTime = min($startTime + $duration, $this->getTotalDuration());
			$startIndex = 0;
			$endIndex = 0;
			foreach ($this->frameTimingTable as $frameIndex => $frameTiming) {
				if ($frameTiming <= $startTime) {
					$startIndex = $frameIndex;
				} else if ($frameTiming >= $endTime) {
					$endIndex = $frameIndex;
					break;
				}
			}
			$this->removeFrame($endIndex, $this->getFrameCounts() - $endIndex);
			$this->removeFrame(0, $startIndex);
			return $this;
		}

		/**
		 * Gets metadata stored at the begining of the MPEG audio as a binary string, or false on failure
		 * @return bool|string
		 */
		public function getBeginingTags() {
			$start = $this->getStart();
			if ($start === false) {
				return false;
			}
			return $this->read($start, 0);
		}

		/**
		 * Gets metadata stored at the end of the MPEG audio as a binary string, or false on failure
		 * @return bool|string
		 */
		public function getEndingTags() {
			$frames = $this->getFrameCounts();
			if ($frames === 0) {
				return false;
			}
			$frame = $this->getFrameHeader($frames - 1);
			if ($frame === false) {
				return false;
			}
			return $this->read(0, $frame->getOffset() + $frame->getLength());
		}

		/**
		 * Removes metadata stored at the begining and the end of the MPEG audio, returns this object
		 * @return \falahati\PHPMP3\MpegAudio
		 */
		public function stripTags() {
			$frames = $this->getFrameCounts();
			if ($frames > 0) {
				$frame = $this->getFrameHeader($frames - 1);
				if ($frame !== false) {
					$this->slice(0, $frame->getOffset() + $frame->getLength());
				}
			}
			$start = $this->getStart();
			if ($start !== false && $start > 0) {
				$this->slice($start, 0);
			}
			$this->reset();
			return $this;
		}
	}
}

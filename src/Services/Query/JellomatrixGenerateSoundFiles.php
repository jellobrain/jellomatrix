<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixGenerateSoundFiles
 *
 * @author eleven11
 */
class JellomatrixGenerateSoundFiles {
  
  /**
   * Returns the Sound Files for the Waves.
   * name: jellomatrix_generate_sound_files
   * @return = array()
   *
   **/
  public function getSoundFiles($note_assembly, $tone, $interval, $frequency, $print) {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    
    //var_dump(debug_backtrace());
    $prints_array = [];
    $print_array =[];
    if($print == 1) {
      $print_array['forward'] = $note_assembly['forward'];
      $print_array['backward'] = $note_assembly['backward'];
    }
    if($print == 2) {
      $prints_array['pairings'] = $note_assembly['pairings'];
      /*
       * JOINWAVS
       */
//      $print_array['forward'] = $note_assembly['forward'];
//      $print_array['backward'] = $note_assembly['backward'];
    }
    if($print == 3) {
      $prints_array['complete'] = $note_assembly['complete'];
      /*
       * JOINWAVS
       */
//      $print_array['forward'] = $note_assembly['forward'];
//      $print_array['backward'] = $note_assembly['backward'];
    }

    if (!empty($prints_array)) {
      foreach ($prints_array as $ke => $directions) {
        if (isset($ke) && $ke == 'pairings') {
          foreach ($directions as $direction => $collection) {
            foreach ($collection as $pos => $freqs) {
              if ($pos != 0) {
                foreach ($freqs as $position => $freaq) {
                  $print_array[$ke][$direction][$pos][$position][0][] = (int) $prints_array[$ke][$direction][0][$position];
                  $print_array[$ke][$direction][$pos][$position][0][] = (int) $freaq;
                }
              }
            }
          }
        }
        if (isset($ke) && $ke == 'complete') {
          foreach ($directions as $direction => $collection) {
            foreach ($collection as $pos => $freqs) {
              if ($pos == 0) {
                foreach ($freqs as $position => $freaq) {
                  $print_array[$ke][$direction][$pos][$position][0][] = (int) $prints_array[$ke][$direction][0][$position];
                }
              }
              if ($pos > 0) {
                foreach ($freqs as $position => $freaq) {
                  $print_array[$ke][$direction][0][$position][0][] = (int) $freaq;
                }
              }
            }
          }
        }
      }
    }
    if(!empty($print_array)) {
      foreach ($print_array as $ke => $directions) {

        //Calculate variable dependent fields
        if ($ke == 'pairings') {
          $channels = 2; //Stereo
        }
        elseif ($ke == 'complete') {
          $channels = 6; //Mono
        }
        elseif ($ke == 'forward' || $ke == 'backward') {
          $channels = 1; //Mono
        }

        #$channels = 1;

        if (isset($directions)) {
          foreach ($directions as $direction => $collection) {

            if ($channels == 1) {

              foreach ($collection as $freqs) {

                //Path to output file
                $filePath = DRUPAL_ROOT . '/sites/default/files/' . $ke . '_' . $tone . '_' . $interval . $direction . $frequency . '.wav';

                //Open a handle to our file in write mode, truncate the file if it exists
                $fileHandle = fopen($filePath, 'wb');
                sleep(3); 
                if (false === $fileHandle) {
                    throw new RuntimeException('Unable to open log file for writing');
                }

                $chunksize = 16;
                $bitDepth = 8; //8bit
                $sampleRate = 44100; //CD quality
                $blockAlign = ($channels * ($bitDepth / 8));
                $averageBytesPerSecond = $sampleRate * $blockAlign;

                $input = $freqs;
                /*
                 * Header chunk
                 * dwFileLength will be calculated at the end, based upon the length of the audio data
                 */
                $header = [
                  'sGroupID' => 'RIFF',
                  'dwFileLength' => 0,
                  'sRiffType' => 'WAVE'
                ];

                /*
                 * Format chunk
                 */
                $fmtChunk = [
                  'sGroupID' => 'fmt',
                  'dwChunkSize' => $chunksize,
                  'wFormatTag' => 1,
                  'wChannels' => $channels,
                  'dwSamplesPerSec' => $sampleRate,
                  'dwAvgBytesPerSec' => $averageBytesPerSecond,
                  'wBlockAlign' => $blockAlign,
                  'dwBitsPerSample' => $bitDepth
                ];

                /*
                 * Map all fields to pack flags
                 * WAV format uses little-endian byte order
                 */
                $fieldFormatMap = [
                  'sGroupID' => 'A4',
                  'dwFileLength' => 'V',
                  'sRiffType' => 'A4',
                  'dwChunkSize' => 'V',
                  'wFormatTag' => 'v',
                  'wChannels' => 'v',
                  'dwSamplesPerSec' => 'V',
                  'dwAvgBytesPerSec' => 'V',
                  'wBlockAlign' => 'v',
                  'dwBitsPerSample' => 'v' //Some resources say this is a uint but it's not - stay woke.
                ];
                /*
                 * Pack and write our values
                 * Keep track of how many bytes we write so we can update the dwFileLength in the header
                 */
                $dwFileLength = 0;
                foreach ($header as $currKey => $currValue) {
                  if (!array_key_exists($currKey, $fieldFormatMap)) {
                    $message = 'Unrecognized header key value ' . $currKey;
                    \Drupal::logger('jellomatrix')->error($message);
                    die('Unrecognized field ' . $currKey);
                  }

                  $currPackFlag = $fieldFormatMap[$currKey];
                  $currOutput = pack($currPackFlag, $currValue);
                  $dwFileLength += fwrite($fileHandle, $currOutput);
                }

                foreach ($fmtChunk as $currKey => $currValue) {
                  if (!array_key_exists($currKey, $fieldFormatMap)) {
                    $message = 'Unrecognized fmtChunk key value ' . $currKey;
                    \Drupal::logger('jellomatrix')->error($message);
                    die('Unrecognized field ' . $currKey);
                  }

                  $currPackFlag = $fieldFormatMap[$currKey];
                  $currOutput = pack($currPackFlag, $currValue);
                  $dwFileLength += fwrite($fileHandle, $currOutput);
                }
                /*
                 * Set up our data chunk
                 * As we write data, the dwChunkSize in this struct will be updated, be sure to pack and overwrite
                 * after audio data has been written
                 */
                $dataChunk = [
                  'sGroupID' => 'data',
                  'dwChunkSize' => 0
                ];

                //Write sGroupID
                $dwFileLength += fwrite($fileHandle, pack($fieldFormatMap['sGroupID'], $dataChunk['sGroupID']));

                //Save a reference to the position in the file of the dwChunkSize field so we can overwrite later
                $dataChunkSizePosition = $dwFileLength;

                //Write our empty dwChunkSize field
                $dwFileLength += fwrite($fileHandle, pack($fieldFormatMap['dwChunkSize'], $dataChunk['dwChunkSize']));
                
                /*
                 8-bit audio: -128 to 127 (because of 2’s complement)
                 */
                $maxAmplitude = 127;


                //Loop through input
                foreach ($input as $currNote) {
                  //dpm($currNote);
                  $currHz = (int)$currNote[0];

                  $currMillis = 1000;

                  /*
                   * Each "tick" should be 1 second divided by our sample rate. Since we're counting in milliseconds, use
                   * 1000/$sampleRate
                   */
                  $timeIncrement = 1000 / $sampleRate;

                  /*
                   * Define how much each tick should advance the sine function. 360deg/(sample rate/frequency)
                   */
                  if ($currHz == 0) {
                    $currHz = .00001;
                  }
                  if ($currHz >= 10000) {
                    $currHz = .00001;
                  }

                  $waveIncrement = $sampleRate / ($sampleRate / $currHz);

                  /*
                   * Run the sine function until we have written all the samples to fill the current note time
                   */
                  $elapsed = 0;

                  $x = 0;

                  while ($elapsed < $currMillis) {
                    /*
                     * The sine wave math
                     * $maxAmplitude*.95 lowers the output a bit so we're not right up at 0db
                     */

                    $currAmplitude = ($maxAmplitude) - number_format(sin(deg2rad($x)) * ($maxAmplitude * .95));

                    //Increment our position in the wave
                    $x += $waveIncrement;

                    //Write the sample and increment our byte counts
                    $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude));

                    $dataChunk['dwChunkSize'] += $currBytesWritten;
                    $dwFileLength += $currBytesWritten;


                    //Update the time counter
                    $elapsed += $timeIncrement;
                  }
                }


                /*
                 * Seek to our dwFileLength and overwrite it with our final value. Make sure to subtract 8 for the
                 * sGroupID and sRiffType fields in the header.
                 */
                fseek($fileHandle, 4);
                fwrite($fileHandle, pack($fieldFormatMap['dwFileLength'], ($dwFileLength - 8)));

                //Seek to our dwChunkSize and overwrite it with our final value
                fseek($fileHandle, $dataChunkSizePosition);
                fwrite($fileHandle, pack($fieldFormatMap['dwChunkSize'], $dataChunk['dwChunkSize']));
                sleep(3); 
                fclose($fileHandle);

              }
            }
            // BOOKMARK set this to one to test again
            /*if ($channels > 1) {

              //Path to output file
              $filePath = DRUPAL_ROOT . '/sites/default/files/' . $ke . '_' . $tone . '_' . $interval . $direction . $frequency . '.wav';

              //Open a handle to our file in write mode, truncate the file if it exists
              $fileHandle = fopen($filePath, 'wb');
              if (false === $fileHandle) {
                  throw new RuntimeException('Unable to open log file for writing');
              }

              /* JOINWAV
               $channels = 1; //Mono

              $chunksize = 16;
              $bitDepth = 8; //8bit
              $sampleRate = 44100; //CD quality
              $blockAlign = ($channels * ($bitDepth / 8));
              $averageBytesPerSecond = $sampleRate * $blockAlign;


              $input = $collection;

              if ($channels == 2) {
                /*
                 * Header chunk
                 * dwFileLength will be calculated at the end, based upon the length of the audio data
                 *
                $header = [
                  'sGroupID' => 'RIFF',
                  'dwFileLength' => 0,
                  'sRiffType' => 'WAVE'
                ];

                /*
                 * Format chunk
                 *
                $subChunkSize = 4;
                $subChunkIDSize = 4;
                $subChunk = 4;
                $chunksize = 4;
                $format = 4;
                $fmtChunk = [
                  'subChunkID1' => 'fmt',
                  'subChunk1' => $subChunk,
                  'subChunk1Size' => $subChunkSize,
                  'subChunk2' => $subChunk,
                  'subChunkID2' => 'fmt',
                  'subChunk2Size' => $subChunkSize,
                  'wFormatTag' => 1,
                  'wChannels' => $channels,
                  'dwSamplesPerSec' => $sampleRate,
                  'dwAvgBytesPerSec' => $averageBytesPerSecond,
                  'wBlockAlign' => $blockAlign,
                  'dwBitsPerSample' => $bitDepth,
                  'sGroupID' => 'fmt',
                  'dwChunkSize' => $chunksize + $format + ($subChunkSize * $channels) + ($subChunkIDSize * $channels) + ($subChunk * $channels)
                ];

                /*
                 * Map all fields to pack flags
                 * WAV format uses little-endian byte order
                 *
                $fieldFormatMap = [
                  'sGroupID' => 'A4',
                  'dwFileLength' => 'V',
                  'sRiffType' => 'A4',
                  'dwChunkSize' => 'V',
                  'wFormatTag' => 'v',
                  'wChannels' => 'v',
                  'dwSamplesPerSec' => 'V',
                  'dwAvgBytesPerSec' => 'V',
                  'wBlockAlign' => 'v',
                  'dwBitsPerSample' => 'v', //Some resources say this is a uint but it's not - stay woke.
                  'subChunk1Size' => 'V',
                  'subChunk2Size' => 'V',
                  'subChunk1' => 'V',
                  'subChunk2' => 'V',
                  'subChunkID1' => 'A4',
                  'subChunkID2' => 'A4'
                ];
              }
              if ($channels == 6) {
                /*
                 * Header chunk
                 * dwFileLength will be calculated at the end, based upon the length of the audio data
                 *
                $header = [
                  'sGroupID' => 'RIFF',
                  'dwFileLength' => 0,
                  'sRiffType' => 'WAVE'
                ];

                /*
                 * Format chunk
                 *
                $subChunkSize = 4;
                $subChunkIDSize = 4;
                $subChunk = 4;
                $chunksize = 4;
                $format = 4;
                $fmtChunk = [
                  'subChunkID1' => 'fmt',
                  'subChunk1' => $subChunk,
                  'subChunk1Size' => $subChunkSize,
                  'subChunk2' => $subChunk,
                  'subChunkID2' => 'fmt',
                  'subChunk2Size' => $subChunkSize,
                  'subChunkID3' => 'fmt',
                  'subChunk3' => $subChunk,
                  'subChunk3Size' => $subChunkSize,
                  'subChunk4' => $subChunk,
                  'subChunkID4' => 'fmt',
                  'subChunk4Size' => $subChunkSize,
                  'subChunkID5' => 'fmt',
                  'subChunk5' => $subChunk,
                  'subChunk5Size' => $subChunkSize,
                  'subChunk6' => $subChunk,
                  'subChunkID6' => 'fmt',
                  'subChunk6Size' => $subChunkSize,
                  'wFormatTag' => 1,
                  'wChannels' => $channels,
                  'dwSamplesPerSec' => $sampleRate,
                  'dwAvgBytesPerSec' => $averageBytesPerSecond,
                  'wBlockAlign' => $blockAlign,
                  'dwBitsPerSample' => $bitDepth,
                  'sGroupID' => 'fmt',
                  'dwChunkSize' => $chunksize + $format + ($subChunkSize * $channels) + ($subChunkIDSize * $channels) + ($subChunk * $channels)
                ];

                /*
                 * Map all fields to pack flags
                 * WAV format uses little-endian byte order
                 *
                $fieldFormatMap = [
                  'sGroupID' => 'A4',
                  'dwFileLength' => 'V',
                  'sRiffType' => 'A4',
                  'dwChunkSize' => 'V',
                  'wFormatTag' => 'v',
                  'wChannels' => 'v',
                  'dwSamplesPerSec' => 'V',
                  'dwAvgBytesPerSec' => 'V',
                  'wBlockAlign' => 'v',
                  'dwBitsPerSample' => 'v', //Some resources say this is a uint but it's not - stay woke.
                  'subChunk1Size' => 'V',
                  'subChunk2Size' => 'V',
                  'subChunk1' => 'V',
                  'subChunk2' => 'V',
                  'subChunkID1' => 'A4',
                  'subChunkID2' => 'A4',
                  'subChunk3Size' => 'V',
                  'subChunk4Size' => 'V',
                  'subChunk3' => 'V',
                  'subChunk4' => 'V',
                  'subChunkID3' => 'A4',
                  'subChunkID4' => 'A4',
                  'subChunk5Size' => 'V',
                  'subChunk6Size' => 'V',
                  'subChunk5' => 'V',
                  'subChunk6' => 'V',
                  'subChunkID5' => 'A4',
                  'subChunkID6' => 'A4'
                ];
              }

              /*
               * Pack and write our values
               * Keep track of how many bytes we write so we can update the dwFileLength in the header
               *
              $dwFileLength = 0;
              foreach ($header as $currKey => $currValue) {
                if (!array_key_exists($currKey, $fieldFormatMap)) {
                  $message = 'Unrecognized header key value ' . $currKey;
                  \Drupal::logger('jellomatrix')->error($message);
                  die('Unrecognized field ' . $currKey);
                }

                $currPackFlag = $fieldFormatMap[$currKey];
                $currOutput = pack($currPackFlag, $currValue);
                $dwFileLength += fwrite($fileHandle, $currOutput);
              }

              foreach ($fmtChunk as $currKey => $currValue) {
                if (!array_key_exists($currKey, $fieldFormatMap)) {
                  $message = 'Unrecognized fmtChunk key value ' . $currKey;
                  \Drupal::logger('jellomatrix')->error($message);
                  die('Unrecognized field ' . $currKey);
                }

                $currPackFlag = $fieldFormatMap[$currKey];
                $currOutput = pack($currPackFlag, $currValue);
                $dwFileLength += fwrite($fileHandle, $currOutput);
              }
              /*
               * Set up our data chunk
               * As we write data, the dwChunkSize in this struct will be updated, be sure to pack and overwrite
               * after audio data has been written
               *
              $dataChunk = [
                'sGroupID' => 'data',
                'dwChunkSize' => 0
              ];

              //Write sGroupID
              $dwFileLength += fwrite($fileHandle, pack($fieldFormatMap['sGroupID'], $dataChunk['sGroupID']));
              
              //Save a reference to the position in the file of the dwChunkSize field so we can overwrite later
              $dataChunkSizePosition = $dwFileLength;

              //Write our empty dwChunkSize field
              $dwFileLength += fwrite($fileHandle, pack($fieldFormatMap['dwChunkSize'], $dataChunk['dwChunkSize']));
              
              /*
               8-bit audio: -128 to 127 (because of 2’s complement)
               *
              $maxAmplitude = 127;

              //Loop through input
              foreach ($input as $array) {
                foreach ($array as $currSet) {
                  foreach ($currSet as $currNote) {

                    //dpm($currNote);
                    $currHz = (int)$currNote[0];

                    if ($channels == 2) {
                      $currHz2 = (int)$currNote[1];
                    } elseif ($channels == 6) {
                      $currHz2 = (int)$currNote[1];
                      $currHz3 = (int)$currNote[2];
                      $currHz4 = (int)$currNote[3];
                      $currHz5 = (int)$currNote[4];
                      $currHz6 = (int)$currNote[5];
                    }


                    $currMillis = 2000;

                    /*
                     * Each "tick" should be 1 second divided by our sample rate. Since we're counting in milliseconds, use
                     * 1000/$sampleRate
                     *
                    $timeIncrement = 1000 / $sampleRate;

                    /*
                     * Define how much each tick should advance the sine function. 360deg/(sample rate/frequency)
                     *
                    if ($currHz == 0) {
                      $currHz = .00001;
                    }

                    if (isset($currHz2) && $currHz2 == 0) {
                      $currHz2 = .00001;
                    }
                    if (isset($currHz3) && $currHz3 == 0) {
                      $currHz3 = .00001;
                    }
                    if (isset($currHz4) && $currHz4 == 0) {
                      $currHz4 = .00001;
                    }
                    if (isset($currHz5) && $currHz5 == 0) {
                      $currHz5 = .00001;
                    }
                    if (isset($currHz6) && $currHz6 == 0) {
                      $currHz6 = .00001;
                    }

                    $waveIncrement = $sampleRate / ($sampleRate / $currHz);

                    if ($channels == 2) {
                      $waveIncrement2 = $sampleRate / ($sampleRate / $currHz2);
                    }
                    if ($channels == 6) {
                      $waveIncrement2 = $sampleRate / ($sampleRate / $currHz2);
                      $waveIncrement3 = $sampleRate / ($sampleRate / $currHz3);
                      $waveIncrement4 = $sampleRate / ($sampleRate / $currHz4);
                      $waveIncrement5 = $sampleRate / ($sampleRate / $currHz5);
                      $waveIncrement6 = $sampleRate / ($sampleRate / $currHz6);
                    }

                    /*
                     * Run the sine function until we have written all the samples to fill the current note time
                     *
                    $elapsed = 0;

                    $x = 0;
                    $x2 = 0;
                    $x3 = 0;
                    $x4 = 0;
                    $x5 = 0;
                    $x6 = 0;

                    if ($channels == 2) {
                      while ($elapsed < $currMillis) {
                        /*
                         * The sine wave math
                         * $maxAmplitude*.95 lowers the output a bit so we're not right up at 0db
                         *

                        $currAmplitude = ($maxAmplitude) - number_format(sin(deg2rad($x)) * ($maxAmplitude * .95));
                        $currAmplitude2 = ($maxAmplitude) - number_format(sin(deg2rad($x2)) * ($maxAmplitude * .95));

                        //Increment our position in the wave
                        $x += $waveIncrement;
                        $x2 += $waveIncrement2;

                        //Write the sample and increment our byte counts
                        // BOOKMARK ERROR HERE
                        $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude));
                        $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude2));

                        $dataChunk['dwChunkSize'] += $currBytesWritten;

                        $dwFileLength += $currBytesWritten;


                        //Update the time counter
                        $elapsed += $timeIncrement;
                      }
                    }
                    if ($channels == 6) {
                      while ($elapsed < $currMillis) {
                        /*
                         * The sine wave math
                         * $maxAmplitude*.95 lowers the output a bit so we're not right up at 0db
                         *

                        $currAmplitude = ($maxAmplitude) - number_format(sin(deg2rad($x)) * ($maxAmplitude * .95));
                        $currAmplitude2 = ($maxAmplitude) - number_format(sin(deg2rad($x2)) * ($maxAmplitude * .95));
                        $currAmplitude3 = ($maxAmplitude) - number_format(sin(deg2rad($x3)) * ($maxAmplitude * .95));
                        $currAmplitude4 = ($maxAmplitude) - number_format(sin(deg2rad($x4)) * ($maxAmplitude * .95));
                        $currAmplitude5 = ($maxAmplitude) - number_format(sin(deg2rad($x5)) * ($maxAmplitude * .95));
                        $currAmplitude6 = ($maxAmplitude) - number_format(sin(deg2rad($x6)) * ($maxAmplitude * .95));

                        //Increment our position in the wave
                        $x += $waveIncrement;
                        $x2 += $waveIncrement2;
                        $x3 += $waveIncrement3;
                        $x4 += $waveIncrement4;
                        $x5 += $waveIncrement5;
                        $x6 += $waveIncrement6;

                        //Write the sample and increment our byte counts
                        // BOOKMARK ERROR HERE
                        $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude));
                        $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude2));
                        $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude3));
                        $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude4));
                        $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude5));
                        $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude6));

                        $dataChunk['dwChunkSize'] += $currBytesWritten;

                        $dwFileLength += $currBytesWritten;



                        //Update the time counter
                        $elapsed += $timeIncrement;
                      }
                    }
                  }

                  /*
                   * Seek to our dwFileLength and overwrite it with our final value. Make sure to subtract 8 for the
                   * sGroupID and sRiffType fields in the header.
                   *
                  fseek($fileHandle, 4);
                  fwrite($fileHandle, pack($fieldFormatMap['dwFileLength'], ($dwFileLength - 8)));

                  //Seek to our dwChunkSize and overwrite it with our final value
                  fseek($fileHandle, $dataChunkSizePosition);
                  fwrite($fileHandle, pack($fieldFormatMap['dwChunkSize'], $dataChunk['dwChunkSize']));
                  sleep(3); 
                  fclose($fileHandle);

                }
              }
            }*/
          }
        }
      }
    }
    if ($print == 4 || $print == 6) {
      $fileHandles = [];
      $rife = [];
      $rife[] = $frequency;
      $old_frequency = $frequency;
      for ($w = 0; $w <= 2; $w++) {
        if ($print == 4) {
         $new_frequency = (int) $old_frequency * 11;
        } else {
         $new_frequency = (int) $old_frequency * 12;
        }
        $rife[] = $new_frequency;
        $old_frequency = $new_frequency;
      }
      foreach ($rife as $eleventh_harmonic) {
        //Path to output file
        if ($print == 4) {
         $filePath = DRUPAL_ROOT . '/sites/default/files/rife_' . $eleventh_harmonic . '_base_' . $frequency . '_eleventh_harmonic.wav';
        } else {
         $filePath = DRUPAL_ROOT . '/sites/default/files/rife_' . $eleventh_harmonic . '_base_' . $frequency . '_twelvth_harmonic.wav';
        }
        
        //Open a handle to our file in write mode, truncate the file if it exists
        $fileHandle = fopen($filePath, 'wb');
        if (false === $fileHandle) {
            throw new RuntimeException('Unable to open log file for writing');
        }
        $channels = 1;
        $chunksize = 16;
        $bitDepth = 8; //8bit
        $sampleRate = 44100; //CD quality
        $blockAlign = ($channels * ($bitDepth / 8));
        $averageBytesPerSecond = $sampleRate * $blockAlign;

        $input = $eleventh_harmonic;
        /*
         * Header chunk
         * dwFileLength will be calculated at the end, based upon the length of the audio data
         */
        $header = [
          'sGroupID' => 'RIFF',
          'dwFileLength' => 0,
          'sRiffType' => 'WAVE'
        ];

        /*
         * Format chunk
         */
        $fmtChunk = [
          'sGroupID' => 'fmt',
          'dwChunkSize' => $chunksize,
          'wFormatTag' => 1,
          'wChannels' => $channels,
          'dwSamplesPerSec' => $sampleRate,
          'dwAvgBytesPerSec' => $averageBytesPerSecond,
          'wBlockAlign' => $blockAlign,
          'dwBitsPerSample' => $bitDepth
        ];

        /*
         * Map all fields to pack flags
         * WAV format uses little-endian byte order
         */
        $fieldFormatMap = [
          'sGroupID' => 'A4',
          'dwFileLength' => 'V',
          'sRiffType' => 'A4',
          'dwChunkSize' => 'V',
          'wFormatTag' => 'v',
          'wChannels' => 'v',
          'dwSamplesPerSec' => 'V',
          'dwAvgBytesPerSec' => 'V',
          'wBlockAlign' => 'v',
          'dwBitsPerSample' => 'v' //Some resources say this is a uint but it's not - stay woke.
        ];
        /*
         * Pack and write our values
         * Keep track of how many bytes we write so we can update the dwFileLength in the header
         */
        $dwFileLength = 0;
        foreach ($header as $currKey => $currValue) {
          if (!array_key_exists($currKey, $fieldFormatMap)) {
            $message = 'Unrecognized header key value ' . $currKey;
            \Drupal::logger('jellomatrix')->error($message);
            die('Unrecognized field ' . $currKey);
          }

          $currPackFlag = $fieldFormatMap[$currKey];
          $currOutput = pack($currPackFlag, $currValue);
          $dwFileLength += fwrite($fileHandle, $currOutput);
        }

        foreach ($fmtChunk as $currKey => $currValue) {
          if (!array_key_exists($currKey, $fieldFormatMap)) {
            $message = 'Unrecognized fmtChunk key value ' . $currKey;
            \Drupal::logger('jellomatrix')->error($message);
            die('Unrecognized field ' . $currKey);
          }

          $currPackFlag = $fieldFormatMap[$currKey];
          $currOutput = pack($currPackFlag, $currValue);
          $dwFileLength += fwrite($fileHandle, $currOutput);
        }
        /*
         * Set up our data chunk
         * As we write data, the dwChunkSize in this struct will be updated, be sure to pack and overwrite
         * after audio data has been written
         */
        $dataChunk = [
          'sGroupID' => 'data',
          'dwChunkSize' => 0
        ];

        //Write sGroupID
        $dwFileLength += fwrite($fileHandle, pack($fieldFormatMap['sGroupID'], $dataChunk['sGroupID']));
        
        //Save a reference to the position in the file of the dwChunkSize field so we can overwrite later
        $dataChunkSizePosition = $dwFileLength;

        //Write our empty dwChunkSize field
        $dwFileLength += fwrite($fileHandle, pack($fieldFormatMap['dwChunkSize'], $dataChunk['dwChunkSize']));
        
        /*
         8-bit audio: -128 to 127 (because of 2’s complement)
         */
        $maxAmplitude = 127;


        //Loop through input
        for ($z = 0; $z < 60; $z++) {
          //dpm($currNote);
          $currHz = (int)$input;

          $currMillis = 1000;

          /*
           * Each "tick" should be 1 second divided by our sample rate. Since we're counting in milliseconds, use
           * 1000/$sampleRate
           */
          $timeIncrement = 1000 / $sampleRate;

          /*
           * Define how much each tick should advance the sine function. 360deg/(sample rate/frequency)
           */
          if ($currHz == 0) {
            $currHz = .00001;
          }

          $waveIncrement = $sampleRate / ($sampleRate / $currHz);

          /*
           * Run the sine function until we have written all the samples to fill the current note time
           */
          $elapsed = 0;

          $x = 0;

          while ($elapsed < $currMillis) {
            /*
             * The sine wave math
             * $maxAmplitude*.95 lowers the output a bit so we're not right up at 0db
             */

            $currAmplitude = ($maxAmplitude) - number_format(sin(deg2rad($x)) * ($maxAmplitude * .95));

            //Increment our position in the wave
            $x += $waveIncrement;

            //Write the sample and increment our byte counts
            $currBytesWritten = fwrite($fileHandle, pack('c', $currAmplitude));

            $dataChunk['dwChunkSize'] += $currBytesWritten;
            $dwFileLength += $currBytesWritten;


            //Update the time counter
            $elapsed += $timeIncrement;
          }
        }


        /*
         * Seek to our dwFileLength and overwrite it with our final value. Make sure to subtract 8 for the
         * sGroupID and sRiffType fields in the header.
         */
        fseek($fileHandle, 4);
        fwrite($fileHandle, pack($fieldFormatMap['dwFileLength'], ($dwFileLength - 8)));

        //Seek to our dwChunkSize and overwrite it with our final value
        fseek($fileHandle, $dataChunkSizePosition);
        fwrite($fileHandle, pack($fieldFormatMap['dwChunkSize'], $dataChunk['dwChunkSize']));
        sleep(3); 
        fclose($fileHandle);

        $fileHandles[] = $fileHandle;
      }
    }
    if ($print == 5 || $print == 7) {
      $fileHandles = [];
      $rife = [];
      $rife[] = $frequency;
      $old_frequency = $frequency;
      for ($w = 0; $w <= 2; $w++) {
        if ($print == 5) {
          $new_frequency = (int) $old_frequency * 11;
        } else {
          $new_frequency = (int) $old_frequency * 12;
        }
        
        $rife[] = $new_frequency;
        $old_frequency = $new_frequency;
      }
      
      foreach ($rife as $eleventh) {
        if ($print == 5) {
          $fileHandles[] = DRUPAL_ROOT . '/sites/default/files/rife_' . $eleventh . '_base_' . $frequency . '_eleventh_harmonic.wav';
        } else {
          $fileHandles[] = DRUPAL_ROOT . '/sites/default/files/rife_' . $eleventh . '_base_' . $frequency . '_twelvth_harmonic.wav';
        }
      }
      
      
      $combined_wav_data = $this->joinWaves($fileHandles, $frequency);
      
      if ($print == 5) {
        $path = DRUPAL_ROOT . '/sites/default/files/rife_eleventh_complete_base_' . $frequency . '.wav';
      } else {
        $path = DRUPAL_ROOT . '/sites/default/files/rife_twelvth_complete_base_' . $frequency . '.wav';
      }
      
      $handle = fopen($path, "wb");
      if (false === $handle) {
          throw new RuntimeException('Unable to open log file for writing');
      }
      fwrite($handle, $combined_wav_data);
      sleep(3); 
      chmod($path, 0777);
      fclose($handle);
    }

    /*
     * JOINWAVS
     */
     if ($print == 2) {

      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'r' . $frequency . '.wav')) {
        $r_set = [];
        $r_set[] = DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'r' . $frequency . '.wav';
        $r_set[] = DRUPAL_ROOT . '/sites/default/files/backward'. $tone . '_' . $interval . 'rd' . $frequency . '.wav';
        $r = $this->joinWaves($r_set, $frequency);

        $path = DRUPAL_ROOT . '/sites/default/files/pair' . $tone . '_' . $interval . '_rset_' . $frequency . '.wav';
        $pathHandle = fopen($path, 'wb');
        //dpm($pathHandle);
        if (false === $pathHandle) {
            throw new RuntimeException('Unable to open log file for writing');
        }
        fwrite($pathHandle,$r);
        sleep(3); 
        chmod($path, 0777);
        fclose($pathHandle);
      }

      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'rl' . $frequency . '.wav')) {
        $rl_set = [];
        $rl_set[] = DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'rl' . $frequency . '.wav';
        $rl_set[] = DRUPAL_ROOT . '/sites/default/files/backward' . $tone . '_' . $interval . 'rld' . $frequency . '.wav';
        $rl = $this->joinWaves($rl_set, $frequency);

        $path = DRUPAL_ROOT . '/sites/default/files/pair' . $tone . '_' . $interval . '_rlset_' . $frequency . '.wav';
        $pathHandle = fopen($path, 'wb');
        if (false === $pathHandle) {
            throw new RuntimeException('Unable to open log file for writing');
        }
        fwrite($pathHandle,$rl);
        sleep(3); 
        chmod($path, 0777);
        fclose($pathHandle);
      }

      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav')) {
        $lr_set = [];
        $lr_set[] = DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav';
        $lr_set[] = DRUPAL_ROOT . '/sites/default/files/backward' . $tone . '_' . $interval . 'lrd' . $frequency . '.wav';
        $lr = $this->joinWaves($lr_set, $frequency);

        $path = DRUPAL_ROOT . '/sites/default/files/pair' . $tone . '_' . $interval . '_lrset_' . $frequency . '.wav';
        $pathHandle = fopen($path, 'wb');
        if (false === $pathHandle) {
            throw new RuntimeException('Unable to open log file for writing');
        }
        fwrite($pathHandle,$lr);
        sleep(3); 
        chmod($path, 0777);
        fclose($pathHandle);
      }
    }
    if ($print == 3) {
      $c_set = [];

      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav')) {
        $c_set[] = DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'r' . $frequency . '.wav';
      }
      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav')) {
        $c_set[] = DRUPAL_ROOT . '/sites/default/files/backward'. $tone . '_' . $interval . 'rd' . $frequency . '.wav';
      }
      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav')) {
        $c_set[] = DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'rl' . $frequency . '.wav';
      }
      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav')) {
        $c_set[] = DRUPAL_ROOT . '/sites/default/files/backward' . $tone . '_' . $interval . 'rld' . $frequency . '.wav';
      }
      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav')) {
        $c_set[] = DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav';
      }
      if (file_exists(DRUPAL_ROOT . '/sites/default/files/forward' . $tone . '_' . $interval . 'lr' . $frequency . '.wav')) {
        $c_set[] = DRUPAL_ROOT . '/sites/default/files/backward' . $tone . '_' . $interval . 'lrd' . $frequency . '.wav';
      }

      $c = $this->joinWaves($c_set, $frequency);

      $path = DRUPAL_ROOT . '/sites/default/files/complete' . $tone . '_' . $interval . '_cset_' . $frequency . '.wav';
      $pathHandle = fopen($path, 'wb');
      if (false === $pathHandle) {
          throw new RuntimeException('Unable to open log file for writing');
      }
      fwrite($pathHandle,$c);
      sleep(3); 
      chmod($path, 0777);
      fclose($pathHandle);
    }
    
    return [];

  }
  
  public function joinWaves($wavs, $frequency) {

    $fields = join('/', array('H8ChunkID', 'VChunkSize', 'H8Format',
      'H8Subchunk1ID', 'VSubchunk1Size',
      'vAudioFormat', 'vNumChannels', 'VSampleRate',
      'VByteRate', 'vBlockAlign', 'vBitsPerSample'));
    $data = '';
    foreach ($wavs as $wav) {
      $fp = fopen($wav, 'rb');
      if (false === $fp) {
          throw new RuntimeException('Unable to open log file for writing');
      }
      $header = fread($fp, 36);
      $info = unpack($fields, $header);

      if ($info['Subchunk1Size'] > 16) {
        $header .= fread($fp, ($info['Subchunk1Size'] - 16));
      }
      // read SubChunk2ID
      $header .= fread($fp, 4);
      // read Subchunk2Size
      $size = unpack('vsize', fread($fp, 4));
      $size = $size['size'];
      // read data
      $data .= fread($fp, $size);
      sleep(1);
    }
    return $header . pack('V', strlen($data)) . $data;
  }
}

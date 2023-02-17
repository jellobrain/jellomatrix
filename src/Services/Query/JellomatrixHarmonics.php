<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixHarmonics
 *
 * @author eleven11
 */
class JellomatrixHarmonics {
  
  /**
   * Returns the Harmonics Array.
   * name: jellomatrix_harmonics
   * @param $frequency
   * @return = array()
   *
   **/
  public function getHarmonics($frequency) {
    // Now the harmonics.
    $harmonics = array();
    $harmonics[] = '0:0:origin';
    $harmonics[] = '1:1:C';
    $harmonics[] = '1:2:C';
    $harmonics[] = '1:3:F';
    $harmonics[] = '1:4:C';
    $harmonics[] = '1:5:G#';
    $harmonics[] = '1:6:F';
    $harmonics[] = '1:7:D';
    $harmonics[] = '1:8:C';
    $harmonics[] = '1:9:Bb';
    $harmonics[] = '1:10:G#';
    $harmonics[] = '1:11:F#';
    $harmonics[] = '1:12:F';
    $harmonics[] = '1:13:E';
    $harmonics[] = '1:14:D';
    $harmonics[] = '1:15:C#';
    $harmonics[] = '1:16:C';
    $harmonics[] = '2:1:C';
    $harmonics[] = '2:2:C';
    $harmonics[] = '2:3:F';
    $harmonics[] = '2:4:C';
    $harmonics[] = '2:5:G#';
    $harmonics[] = '2:6:F';
    $harmonics[] = '2:7:D';
    $harmonics[] = '2:8:C';
    $harmonics[] = '2:9:Bb';
    $harmonics[] = '2:10:G#';
    $harmonics[] = '2:11:F#';
    $harmonics[] = '2:12:F';
    $harmonics[] = '2:13:E';
    $harmonics[] = '2:14:D';
    $harmonics[] = '2:15:C#';
    $harmonics[] = '2:16:C';
    $harmonics[] = '3:1:G';
    $harmonics[] = '3:2:G';
    $harmonics[] = '3:3:C';
    $harmonics[] = '3:4:G';
    $harmonics[] = '3:5:Eb';
    $harmonics[] = '3:6:C';
    $harmonics[] = '3:7:A';
    $harmonics[] = '3:8:G';
    $harmonics[] = '3:9:F';
    $harmonics[] = '3:10:Eb';
    $harmonics[] = '3:11:C#';
    $harmonics[] = '3:12:C';
    $harmonics[] = '3:13:B';
    $harmonics[] = '3:14:A';
    $harmonics[] = '3:15:G#';
    $harmonics[] = '3:16:G';
    $harmonics[] = '4:1:C';
    $harmonics[] = '4:2:C';
    $harmonics[] = '4:3:F';
    $harmonics[] = '4:4:C';
    $harmonics[] = '4:5:G#';
    $harmonics[] = '4:6:F';
    $harmonics[] = '4:7:D';
    $harmonics[] = '4:8:C';
    $harmonics[] = '4:9:Bb';
    $harmonics[] = '4:10:G#';
    $harmonics[] = '4:11:F#';
    $harmonics[] = '4:12:F';
    $harmonics[] = '4:13:E';
    $harmonics[] = '4:14:D';
    $harmonics[] = '4:15:C#';
    $harmonics[] = '4:16:C';
    $harmonics[] = '5:1:E';
    $harmonics[] = '5:2:E';
    $harmonics[] = '5:3:A';
    $harmonics[] = '5:4:E';
    $harmonics[] = '5:5:C';
    $harmonics[] = '5:6:A';
    $harmonics[] = '5:7:F#';
    $harmonics[] = '5:8:E';
    $harmonics[] = '5:9:D';
    $harmonics[] = '5:10:C';
    $harmonics[] = '5:11:Bb';
    $harmonics[] = '5:12:A';
    $harmonics[] = '5:13:G';
    $harmonics[] = '5:14:F#';
    $harmonics[] = '5:15:F';
    $harmonics[] = '5:16:E';
    $harmonics[] = '6:1:G';
    $harmonics[] = '6:2:G';
    $harmonics[] = '6:3:C';
    $harmonics[] = '6:4:G';
    $harmonics[] = '6:5:Eb';
    $harmonics[] = '6:6:C';
    $harmonics[] = '6:7:A';
    $harmonics[] = '6:8:G';
    $harmonics[] = '6:9:F';
    $harmonics[] = '6:10:Eb';
    $harmonics[] = '6:11:C#';
    $harmonics[] = '6:12:C';
    $harmonics[] = '6:13:B';
    $harmonics[] = '6:14:A';
    $harmonics[] = '6:15:G#';
    $harmonics[] = '6:16:G';
    $harmonics[] = '7:1:Bb';
    $harmonics[] = '7:2:Bb';
    $harmonics[] = '7:3:Eb';
    $harmonics[] = '7:4:Bb';
    $harmonics[] = '7:5:F#';
    $harmonics[] = '7:6:Eb';
    $harmonics[] = '7:7:C';
    $harmonics[] = '7:8:Bb';
    $harmonics[] = '7:9:G#';
    $harmonics[] = '7:10:F#';
    $harmonics[] = '7:11:E';
    $harmonics[] = '7:12:Eb';
    $harmonics[] = '7:13:C#';
    $harmonics[] = '7:14:C';
    $harmonics[] = '7:15:B';
    $harmonics[] = '7:16:Bb';
    $harmonics[] = '8:1:C';
    $harmonics[] = '8:2:C';
    $harmonics[] = '8:3:F';
    $harmonics[] = '8:4:C';
    $harmonics[] = '8:5:G#';
    $harmonics[] = '8:6:F';
    $harmonics[] = '8:7:D';
    $harmonics[] = '8:8:C';
    $harmonics[] = '8:9:Bb';
    $harmonics[] = '8:10:G#';
    $harmonics[] = '8:11:F#';
    $harmonics[] = '8:12:F';
    $harmonics[] = '8:13:E';
    $harmonics[] = '8:14:D';
    $harmonics[] = '8:15:C#';
    $harmonics[] = '8:16:C';
    $harmonics[] = '9:1:D';
    $harmonics[] = '9:2:D';
    $harmonics[] = '9:3:G';
    $harmonics[] = '9:4:D';
    $harmonics[] = '9:5:Eb';
    $harmonics[] = '9:6:G';
    $harmonics[] = '9:7:E';
    $harmonics[] = '9:8:D';
    $harmonics[] = '9:9:C';
    $harmonics[] = '9:10:Bb';
    $harmonics[] = '9:11:A';
    $harmonics[] = '9:12:G';
    $harmonics[] = '9:13:F#';
    $harmonics[] = '9:14:E';
    $harmonics[] = '9:15:Eb';
    $harmonics[] = '9:16:D';
    $harmonics[] = '10:1:E';
    $harmonics[] = '10:2:E';
    $harmonics[] = '10:3:A';
    $harmonics[] = '10:4:E';
    $harmonics[] = '10:5:C';
    $harmonics[] = '10:6:A';
    $harmonics[] = '10:7:F#';
    $harmonics[] = '10:8:E';
    $harmonics[] = '10:9:D';
    $harmonics[] = '10:10:C';
    $harmonics[] = '10:11:Bb';
    $harmonics[] = '10:12:A';
    $harmonics[] = '10:13:G';
    $harmonics[] = '10:14:F#';
    $harmonics[] = '10:15:F';
    $harmonics[] = '10:16:E';
    $harmonics[] = '11:1:F#';
    $harmonics[] = '11:2:F#';
    $harmonics[] = '11:3:B';
    $harmonics[] = '11:4:F#';
    $harmonics[] = '11:5:D';
    $harmonics[] = '11:6:B';
    $harmonics[] = '11:7:G#';
    $harmonics[] = '11:8:F#';
    $harmonics[] = '11:9:Eb';
    $harmonics[] = '11:10:D';
    $harmonics[] = '11:11:C';
    $harmonics[] = '11:12:B';
    $harmonics[] = '11:13:A';
    $harmonics[] = '11:14:G#';
    $harmonics[] = '11:15:G';
    $harmonics[] = '11:16:F#';
    $harmonics[] = '12:1:G';
    $harmonics[] = '12:2:G';
    $harmonics[] = '12:3:C';
    $harmonics[] = '12:4:G';
    $harmonics[] = '12:5:Eb';
    $harmonics[] = '12:6:C';
    $harmonics[] = '12:7:A';
    $harmonics[] = '12:8:G';
    $harmonics[] = '12:9:F';
    $harmonics[] = '12:10:Eb';
    $harmonics[] = '12:11:C#';
    $harmonics[] = '12:12:C';
    $harmonics[] = '12:13:C';
    $harmonics[] = '12:14:B';
    $harmonics[] = '12:15:A';
    $harmonics[] = '12:16:G#';
    $harmonics[] = '13:1:G#';
    $harmonics[] = '13:2:G#';
    $harmonics[] = '13:3:C#';
    $harmonics[] = '13:4:G#';
    $harmonics[] = '13:5:F';
    $harmonics[] = '13:6:C#';
    $harmonics[] = '13:7:B';
    $harmonics[] = '13:8:G#';
    $harmonics[] = '13:9:F#';
    $harmonics[] = '13:10:F';
    $harmonics[] = '13:11:Eb';
    $harmonics[] = '13:12:C#';
    $harmonics[] = '13:13:C';
    $harmonics[] = '13:14:B';
    $harmonics[] = '13:15:Bb';
    $harmonics[] = '13:16:G#';
    $harmonics[] = '14:1:Bb';
    $harmonics[] = '14:2:Bb';
    $harmonics[] = '14:3:Eb';
    $harmonics[] = '14:4:Bb';
    $harmonics[] = '14:5:F#';
    $harmonics[] = '14:6:Eb';
    $harmonics[] = '14:7:C';
    $harmonics[] = '14:8:Bb';
    $harmonics[] = '14:9:G#';
    $harmonics[] = '14:10:F#';
    $harmonics[] = '14:11:E';
    $harmonics[] = '14:12:Eb';
    $harmonics[] = '14:13:C#';
    $harmonics[] = '14:14:C';
    $harmonics[] = '14:15:B';
    $harmonics[] = '14:16:Bb';
    $harmonics[] = '15:1:B';
    $harmonics[] = '15:2:B';
    $harmonics[] = '15:3:E';
    $harmonics[] = '15:4:B';
    $harmonics[] = '15:5:G';
    $harmonics[] = '15:6:E';
    $harmonics[] = '15:7:C#';
    $harmonics[] = '15:8:B';
    $harmonics[] = '15:9:A';
    $harmonics[] = '15:10:G';
    $harmonics[] = '15:11:F';
    $harmonics[] = '15:12:E';
    $harmonics[] = '15:13:D';
    $harmonics[] = '15:14:C#';
    $harmonics[] = '15:15:C';
    $harmonics[] = '15:16:B';
    $harmonics[] = '16:1:C';
    $harmonics[] = '16:2:C';
    $harmonics[] = '16:3:F';
    $harmonics[] = '16:4:C';
    $harmonics[] = '16:5:G#';
    $harmonics[] = '16:6:F';
    $harmonics[] = '16:7:D';
    $harmonics[] = '16:8:C';
    $harmonics[] = '16:9:Bb';
    $harmonics[] = '16:10:G#';
    $harmonics[] = '16:11:F#';
    $harmonics[] = '16:12:F';
    $harmonics[] = '16:13:E';
    $harmonics[] = '16:14:D';
    $harmonics[] = '16:15:C#';
    $harmonics[] = '16:16:C';
    $harmonics[] = '0:1:zero';
    $harmonics[] = '0:2:zero';
    $harmonics[] = '0:3:zero';
    $harmonics[] = '0:4:zero';
    $harmonics[] = '0:5:zero';
    $harmonics[] = '0:6:zero';
    $harmonics[] = '0:7:zero';
    $harmonics[] = '0:8:zero';
    $harmonics[] = '0:9:zero';
    $harmonics[] = '0:10:zero';
    $harmonics[] = '0:11:zero';
    $harmonics[] = '0:12:zero';
    $harmonics[] = '0:13:zero';
    $harmonics[] = '0:14:zero';
    $harmonics[] = '0:15:zero';
    $harmonics[] = '0:16:zero';
    $harmonics[] = '1:0:infinity';
    $harmonics[] = '2:0:infinity';
    $harmonics[] = '3:0:infinity';
    $harmonics[] = '4:0:infinity';
    $harmonics[] = '5:0:infinity';
    $harmonics[] = '6:0:infinity';
    $harmonics[] = '7:0:infinity';
    $harmonics[] = '8:0:infinity';
    $harmonics[] = '9:0:infinity';
    $harmonics[] = '10:0:infinity';
    $harmonics[] = '11:0:infinity';
    $harmonics[] = '12:0:infinity';
    $harmonics[] = '13:0:infinity';
    $harmonics[] = '14:0:infinity';
    $harmonics[] = '15:0:infinity';
    $harmonics[] = '16:0:infinity';

    $explode = [];
    foreach ($harmonics as $key => $harmonic) {
      $explode = explode(':', $harmonic);
      if ($explode[1] >= 1 && $explode[0] != 0) {
        $freq = ($explode[0]/$explode[1])*$frequency;
      }
      if ($explode[1] == 0 && $explode[0] != 0) {
        $freq = 10000000;
      }
      if ($explode[1] != 0 && $explode[0] == 0) {
        $freq = .0000001;
      }
      if (isset($freq)) {
        $harmonics[$key] .= ':' . $freq;
      }
      else {
        $harmonics[$key] .= ':10000000';
      }
    }
    //dpm($harmonics);

    return $harmonics;
  }
}

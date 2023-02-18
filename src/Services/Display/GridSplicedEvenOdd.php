<?php

namespace Drupal\jellomatrix\Services\Display;

/**
 * Description of JellomatrixGridSplicedEvenOdd
 *
 * @author eleven11
 */
class GridSplicedEvenOdd {
  
  /**
   * Returns the SplicedEvenOdd for the Waves.
   * name: grid_spliced_even_odd
   * @return = string
   *
   **/
  public function getGridSplicedEvenOdd($spliced_matrix, $tone, $interval) {
    // Place block output here //
    $output = '';

    // And then we create the spliced matrix grid.
    $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>HIGHLIGHTING EVEN+ODD: The Spliced Matrix</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
    for ($i = 1; $i <= $interval; $i++) {
      $output .= '<tr>';
      $count = 1;
      foreach ($spliced_matrix as $spliced_row) {
        foreach ($spliced_row as $item) {
          if ($item['row'] == $i) {
            # BOOKMARK: STATIC SERVICE
            $service = \Drupal::service('jellomatrix.jellomatrix_primes');
            $prime = $service->getPrimes($tone);
            #$prime = jellomatrix_primes($tone);
            if (($item['column'])%2 == 0) {
              $item['color'] = 'green-text';
            }
            if (($item['column'])%2 != 0) {
              $item['color'] = 'red-text';
            }
            if (($item['tone'])%2 == 0) {
              $item['background'] = 'white';
              $item['opacity'] = '.' . $item['tone'];
            }
            if (($item['tone'])%2 != 0) {
              $item['background'] = 'highlight';
              $item['opacity'] = '.' . $item['tone'];
            }
            $output .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' . $item['color'] . ' tdgrid  ' . $item['background'] . '">' . $item['tone'] . '</td>';
            $count++;
          }
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table>';
    $output .= '<div class="hr begintext"><p>Interstingly enough, the sections which seem to hold information about the vortec/ies they reflect seem to fall most often in the middle of the sine waves created by what appear to be very different "environments" or "gradients" between higher frequency oscillations of even and odd numbers (you might need to squint your eyes to see them), They are the waves defined by the more or less frequent oscillatory patterns taken as a whole.  More about this in the "Rows" calculations in the "Increments" section below.</p><hr class="hr"></div></div>';

    
    return $output;
  }
}

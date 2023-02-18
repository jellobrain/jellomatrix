<?php

namespace Drupal\jellomatrix\Services\Display;

/**
 * Description of JellomatrixGridSplicedPrimes
 *
 * @author eleven11
 */
class GridSplicedPrimes {
  
  /**
   * Returns the SplicedPrimes for the Waves.
   * name: grid_spliced_primes
   * @return = string
   *
   **/
  public function getGridSplicedPrimes($spliced_matrix, $primes, $tone, $interval) {
    // Place block output here //
    $output = '';
    
    // And then we create the spliced matrix grid.
    $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>HIGHLIGHTING PRIMES: The Spliced Matrix</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
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
            if (in_array($item['tone'], $primes)) {
              $item['background'] = 'highlight';
              $item['opacity'] = '.' . $item['tone'];
            }
            if (!in_array($item['tone'], $primes)) {
              $item['background'] = 'white';
              $item['opacity'] = '.' . $item['tone'];
            }
            $output .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' .$item['color'] . ' tdgrid ' .$item['background'] . '">' . $item['tone'] . '</td>';
            $count++;
          }
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table><div><hr class="hr"></div></div>';
    
    return $output;
  }
}

<?php

namespace Drupal\jellomatrix\Services\Display;

/**
 * Description of JellomatrixGridSplicedMatrix
 *
 * @author eleven11
 */
class GridSplicedMatrix {
  
  /**
   * Returns the SplicedMatrix for the Waves.
   * name: grid_spliced_matrix
   * @return = string
   *
   **/
  public function getGridSplicedMatrix($spliced_matrix, $primes, $tone, $interval) {
    // Place block output here //
    $output = '';

    // And then we create the spliced matrix grid.
    $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>The Basic Orientation of the Spliced Matrix</h3>';
    $output .= '<p>';
    $output .= 'Why splice the initial matrix?  This started out as a hunch, but also following the work of Jose Arguilles who ';
    $output .= 'inspired this up to a point.  But also the work of Mark Rothko and Randy Powell with their ABHA torus to which ';
    $output .= 'the matrix forms here bare some relation but which diverge from what Randy and Mark are doing in important ways. ';
    $output .= 'In my mind, splicing the matrix creates an architecture that reminded me of a battery.  I do not think this analogy ';
    $output .= 'is off-base. When we combine this notion while also looking for the patterns in the \'scales\' found in the original matrix, ';
    $output .= 'we see emergent patterns and pathways.  The next progression of images takes you through a categorization of some of those patterns.';
    $output .= '</p>';
    
    $output .= '<table class=" table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';

    $request = \Drupal::request();
    $current_path = $request->getPathInfo();
    $path_args = explode('/', $current_path);
    if (isset($path_args[4])) {
      $doubleflip = $path_args[4];
    }

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
            if ($item['column'] == 1 && $item['row'] == 1) {
              $item['background'] = 'green';
              $item['opacity'] = '.' . $item['tone'];
            }
            if (isset($doubleflip) && $doubleflip == 'doubleflip') {
              if ($item['column'] == 2*$tone && $item['row'] == $interval) {
                $item['background'] = 'green';
                $item['opacity'] = '.' . $item['tone'];
              }
            }
            else {
              if ($item['column'] == 2 && $item['row'] == $interval) {
                $item['background'] = 'green';
                $item['opacity'] = '.' . $item['tone'];
              }
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

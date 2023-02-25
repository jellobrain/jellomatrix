<?php

namespace Drupal\jellomatrix\Services\Display;

/**
 * Description of JellomatrixGridPrimeMatrix
 *
 * @author eleven11
 */
class GridPrimeMatrix {
  
  /**
   * Returns the PrimeMatrix for the Waves.
   * name: grid_prime_matrix
   * @return = string
   *
   **/
  public function getGridPrimeMatrix($scale_increments, $prime_matrix, $primes, $tone, $interval, $scaled, $scales) {
    // Place block output here //
    $output = '';
    
    $output .= '<div class="begintext"><p><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">Jellomatrix</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://www.jellobrain.com" property="cc:attributionName" rel="cc:attributionURL">Ana Willem</a> is licensed since 2007 under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="https://www.jellobrain.com" rel="dct:source">https://www.jellobrain.com</a>.</p></div><hr class="hr">';

    $output .= '<div class="begintext">';

    if (isset($scale_increments)) {
      $output .= '<div class="floatright"><h3>You have scales!</h3></div>';
    }
    else {
      $output .= '<div class="floatright"><h3>Not scale active. Try again!</h3></div>';
    }
    $output .= '</div><hr class="hr"><br></div>';
    $output .= '<div class="begingrid"><h3>The Original Matrix</h3><div class="endtext"><br></div>';
    $output .= '<table class="table begingrid" cols="' . $tone . '" rows="' . $interval . '">';
    $totalcount = $tone*$interval;
    foreach($prime_matrix as $prime_row) {
      $output .= '<tr>';
      $count = 0;
      foreach($prime_row as $item) {
        if ($item['tone']%2 != 0) {
          $color = 'white';
        }
        if ($item['tone']%2 == 0) {
          $color = 'subhighlight';
        }
        foreach ($primes as $prime) {
          if ($item['tone'] == $prime) {
            $color = 'white';
              if ($item['tone']%2 == 0) {
                $color = 'highlight';
              }
          }
        }

        /*dpm($scales);*/
        if ($item['tone'] == $scales['h'][$count]) {
          $output .= '<td class="tdgrid ' . $color . ' blue-text">' . $item['tone'] . '</td>';
        }
        elseif (isset($scales['f'][$count]) && $item['tone'] == $scales['f'][$count]) {
          $output .= '<td class="tdgrid ' . $color . ' groen-text">' . $item['tone'] . '</td>';
        }
        elseif (isset($scales['b'][$count]) && $item['tone'] == $scales['b'][$count]) {
          $output .= '<td class="tdgrid ' . $color . ' salmon-text">' . $item['tone'] . '</td>';
        }
        else {
          $output .= '<td class="tdgrid ' . $color . ' red-text">' . $item['tone'] . '</td>';
        }
        $count++;
      }
      $output .= '</tr>';
    }
    $output .= '</table></div><p><br/></p><div class="endtext"><br></div>';

    $output .= '<div class="begintext"><p><h3>Scale Pattern:</h3></p>';
    $output .= '<p>Whether you look at each row individually, or look at each diagonal row (in forward or backward \'slash\' ';
    $output .= 'directions) you will notice that the order of numbers is consistent on every row (or each direction of diagonal rows) ';
    $output .= 'and that only the starting number differs from row to row.  I refer to this as a \'scale\'.  If the scale were ';
    $output .= 'to be played in a circle consisting of the numbers of the first \'tone\' value, the shape formed would be the ';
    $output .= 'same regardless of which number you start with.';
    $output .= '</p>';
    $output .= '<p><img src="/sites/default/files/h_circle.png?t='. time().'" />';
    $output .= '&nbsp;<img src="/sites/default/files/f_circle.png?t='. time().'" />';
    $output .= '&nbsp;<img src="/sites/default/files/b_circle.png?t='. time().'" /></p><div class="endtext"><br></div></div>';
    $output .= '<p><strong>' . $scaled . '...</strong></p><hr class="hr"><p><br/></p><div class="endtext"><br></div>';
    $output .= '<div class="begintext"><p><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">Jellomatrix</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://www.jellobrain.com" property="cc:attributionName" rel="cc:attributionURL">Ana Willem</a> is licensed since 2007 under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="https://www.jellobrain.com" rel="dct:source">https://www.jellobrain.com</a>.</p></div><hr class="hr">';

    
    return $output;
  }
}

<?php

namespace Drupal\jellomatrix\Services\Display;

/**
 * Description of JellomatrixGridSplicedScalePatterns
 *
 * @author eleven11
 */
class GridSplicedScalePatterns {
  
  /**
   * Returns the SplicedScalePatterns for the Waves.
   * name: grid_spliced_scale_patterns
   * @return = string
   *
   **/
  public function getGridSplicedScalePatterns($scale_increments, $scaled, $primes, $tone, $interval) {
    // Place block output here //
    $output = '';
    
    if (isset($scaled)) {
      $output .= '<div class="begintext"><p><h3>Scale Pattern:</h3></p>';
      //$output .= '<p>Whether you look at each row individually, or look at each diagonal row (in forward or backward \'slash\' ';
      //$output .= 'directions) you will notice that the order of numbers is consistent on every row (or each direction of diagonal rows) ';
      //$output .= 'and that only the starting number differs from row to row.  I refer to this as a \'scale\'.  If the scale were ';
      //$output .= 'to be played in a circle consisting of the numbers of the first \'tone\' value, the shape formed would be the ';
      //$output .= 'same regardless of which number you start with.';
      //$output .= '</p>';
      //$output .= '<p><img src="/sites/default/files/h_circle.png?t='. time().'" />';
      //$output .= '&nbsp;<img src="/sites/default/files/f_circle.png?t='. time().'" />';
      //$output .= '&nbsp;<img src="/sites/default/files/b_circle.png?t='. time().'" /></p><div class="endtext"><br></div>';
      //$output .= '<div class="begintext"><p><h3>Experimental Pattern:</h3></p><p><img src="/sites/default/files/circle_grid.png?t='. time().'" /></p><div class="endtext"><br></div>';
      //$output .= '<p><strong>' . $scaled . '...</strong></p><div class="endtext"><br></div>';
      $output .= '<p><strong>This tool is meant as a proof of concept and not as a complete set of waveforms that are possible (although I am working on it!).</strong></p><div class="endtext"><br></div>';
      $output .= '<p><strong>RED</strong> = Start of wave.</p>';
      $output .= '<p><strong>EVEN Waves</strong></p>';
      if (isset($scale_increments)) {
        foreach ($scale_increments as $i=>$increment) {
          $explode = explode(':', $increment);
          $t = $explode[0];
          $jump = $explode[1];
          $direction = $explode[2];
          $scale_direction = $explode[3];
          $color = $explode[4];
          if ($jump %2 == 0) {
            $output .= '<p><strong>Starting ' . $t . ':</strong> scale direction = ' . $scale_direction . ', rhythm = ' . $jump . ', initial vertical = ' . $direction . ', color = ' . $color . '.</p>';
          }
          if ($jump %2 != 0) {
            $odd_waves = 1;
          }
        }
      }
      if (isset($odd_waves)){
        $output .= '<p><strong>ODD Waves</strong></p>';
      }
      unset($odd_waves);
      if (isset($scale_increments)) {
        foreach ($scale_increments as $i=>$increment) {
          $explode = explode(':', $increment);
          $t = $explode[0];
          $jump = $explode[1];
          $direction = $explode[2];
          $scale_direction = $explode[3];
          $color = $explode[4];
          if ($jump %2 != 0) {
            $output .= '<p><strong>Starting ' . $t . ':</strong> scale direction = ' . $scale_direction . ', rhythm = ' . $jump . ', initial vertical = ' . $direction . ', color = ' . $color . '.</p>';
          }
        }
      }
    }
    $output .= '<p></p><br></div>';
    
    return $output;
  }
}

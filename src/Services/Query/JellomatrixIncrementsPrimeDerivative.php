<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixIncrementsPrimeDerivative
 *
 * @author eleven11
 */
class JellomatrixIncrementsPrimeDerivative {
  
  /**
   * Returns the Prime Derivative Increments.
   * name: jellomatrix_increments_prime_derivative
   * @param $prime_matrix
   * @param $tone
   * @return = array()
   *
   **/
  public function getIncrementsPrimeDerivative($prime_matrix, $tone) {
    // Now we find the increments for the DERIVATIVES.
    $increments_prime = array();
    // Same row, forward direction.
    foreach ($prime_matrix as $row=>$prime_row) {
      foreach ($prime_row as $column=>$item) {
        $neighbor_tone = 0;
        $item_tone = $item['tone'];
        $c = $item['column'];
        $c1 = $c+1;
        $r = $item['row'];
        $r1 = $r;

        if (isset($prime_matrix[$r1][$c1]['tone'])) {
          $neighbor_tone = $prime_matrix[$r1][$c1]['tone'];
          if ($item_tone >= $neighbor_tone) {
            $increments_prime['row']['forward'][$row][$column] = $item_tone-$neighbor_tone;
          }
          if ($neighbor_tone > $item_tone) {
            $increments_prime['row']['forward'][$row][$column] = $tone+$item_tone - $neighbor_tone;
          }
        }
      }
    }
    if (isset($increments['row']['forward'])) {
      foreach ($increments_prime['row']['forward'] as $row=>$prime_row) {
        foreach ($prime_row as $column=>$item) {
          $neighbor_tone = 0;
          $item_tone = $item;
          $c = $column;
          $c1 = $column+1;
          $r = $row;
          $r1 = $r;

          if (isset($increments_prime['row']['forward'][$r1][$c1])) {
            $neighbor_tone = $increments_prime['row']['forward'][$r1][$c1];
            if ($item_tone >= $neighbor_tone) {
              $increments_prime['row']['derivative'][$row][$column] = $item_tone-$neighbor_tone;
            }
            if ($neighbor_tone > $item_tone) {
              $increments_prime['row']['derivative'][$row][$column] = $tone+$item_tone - $neighbor_tone;
            }
          }
        }
      }
    }
    if (isset($increments['row']['derivative'])) {
      foreach ($increments_prime['row']['derivative'] as $row=>$prime_row) {
        foreach ($prime_row as $column=>$item) {
          $neighbor_tone = 0;
          $item_tone = $item;
          $c = $column;
          $c1 = $column+1;
          $r = $row;
          $r1 = $r;

          if (isset($increments_prime['row']['derivative'][$r1][$c1])) {
            $neighbor_tone = $increments_prime['row']['derivative'][$r1][$c1];
            if ($item_tone >= $neighbor_tone) {
              $increments_prime['row']['derivative_2'][$row][$column] = $item_tone-$neighbor_tone;
            }
            if ($neighbor_tone > $item_tone) {
              $increments_prime['row']['derivative_2'][$row][$column] = $tone+$item_tone - $neighbor_tone;
            }
          }
        }
      }
    }
    return $increments_prime;
  }
}

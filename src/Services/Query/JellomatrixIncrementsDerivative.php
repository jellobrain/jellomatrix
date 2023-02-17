<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixIncrementsDerivative
 *
 * @author eleven11
 */
class JellomatrixIncrementsDerivative {
  
  /**
   * Returns the Derivative Increments.
   * name: jellomatrix_increments_derivative
   * @param $spliced_matrix
   * @param $tone
   * @return = array()
   *
   **/
  public function getIncrementsDerivative($spliced_matrix, $tone) {
    // Now we find the increments for the DERIVATIVES.
    $increments = array();
    // Same row, forward direction.
    foreach ($spliced_matrix as $row=>$spliced_row) {
      foreach ($spliced_row as $column=>$item) {
        $neighbor_tone = 0;
        $item_tone = $item['tone'];
        $c = $item['column'];
        $c1 = $c+1;
        $r = $item['row'];
        $r1 = $r;

        if (isset($spliced_matrix[$r1][$c1]['tone'])) {
          $neighbor_tone = $spliced_matrix[$r1][$c1]['tone'];
          if ($item_tone >= $neighbor_tone) {
            $increments['row']['forward'][$row][$column] = $item_tone-$neighbor_tone;
          }
          if ($neighbor_tone > $item_tone) {
            $increments['row']['forward'][$row][$column] = $tone+$item_tone - $neighbor_tone;
          }
        }
      }
    }
    if (isset($increments['row']['forward'])) {
      foreach ($increments['row']['forward'] as $row=>$spliced_row) {
        foreach ($spliced_row as $column=>$item) {
          $neighbor_tone = 0;
          $item_tone = $item;
          $c = $column;
          $c1 = $column+1;
          $r = $row;
          $r1 = $r;

          if (isset($increments['row']['forward'][$r1][$c1])) {
            $neighbor_tone = $increments['row']['forward'][$r1][$c1];
            if ($item_tone >= $neighbor_tone) {
              $increments['row']['derivative'][$row][$column] = $item_tone-$neighbor_tone;
            }
            if ($neighbor_tone > $item_tone) {
              $increments['row']['derivative'][$row][$column] = $tone+$item_tone - $neighbor_tone;
            }
          }
        }
      }
    }
    if (isset($increments['row']['derivative'])) {
      foreach ($increments['row']['derivative'] as $row=>$spliced_row) {
        foreach ($spliced_row as $column=>$item) {
          $neighbor_tone = 0;
          $item_tone = $item;
          $c = $column;
          $c1 = $column+1;
          $r = $row;
          $r1 = $r;

          if (isset($increments['row']['derivative'][$r1][$c1])) {
            $neighbor_tone = $increments['row']['derivative'][$r1][$c1];
            if ($item_tone >= $neighbor_tone) {
              $increments['row']['derivative_2'][$row][$column] = $item_tone-$neighbor_tone;
            }
            if ($neighbor_tone > $item_tone) {
              $increments['row']['derivative_2'][$row][$column] = $tone+$item_tone - $neighbor_tone;
            }
          }
        }
      }
    }
    return $increments;
   
  }
}

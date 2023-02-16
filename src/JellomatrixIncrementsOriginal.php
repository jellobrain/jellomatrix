<?php

namespace Drupal\jellomatrix;

/**
 * Description of JellomatrixIncrementsOriginal
 *
 * @author eleven11
 */
class JellomatrixIncrementsOriginal {
  
  /**
   * Returns the Original Increments.
   * name: jellomatrix_increments_original
   * @param $spliced_matrix
   * @param $tone
   * @return = array()
   *
   **/
  public function getIncrementsOriginal($spliced_matrix, $tone) {
    // And now we want to start calculating the sums of each of the rows
    // using their grid_x and grid_y values and using a counting system
    // based on the $tone value.  Oh yeah, WITHOUT a database...
    $increment_original = array();
    $count = 1;
    foreach ($spliced_matrix as $row) {
      foreach ($row as $item) {
        $tt = 0;
        $it = $item['tone'];
        $c = $item['grid_x'];
        $c1 = $c+1;
        $r = $item['grid_y'];
        $r1 = $r;
        if (isset($spliced_matrix[$r1][$c1]['tone'])) {
          $tt = $spliced_matrix[$r1][$c1]['tone'];
        }
        if ($tt>0 && $it>0) {
          if ($tt >= $it) {
            $increment_original['row']['forward'][$count][] = $tt-$it;
          }
          elseif ($it > $tt) {
            $increment_original['row']['forward'][$count][] = $tone+$tt-$it;
          }
        }
      }
      $count++;
    }
    $count = 1;
    foreach ($spliced_matrix as $row) {
      foreach ($row as $item) {
        $tt = 0;
        $it = $item['tone'];
        $c = $item['grid_x'];
        $c1 = $c-1;
        $r = $item['grid_y'];
        $r1 = $r;
        if (isset($spliced_matrix[$r1][$c1]['tone'])) {
          $tt = $spliced_matrix[$r1][$c1]['tone'];
        }
        if ($tt>0 && $it>0) {
          if ($it <= $tt && isset($tt) && isset($tt)) {
            $increment_original['row']['backward'][$count][] = $tt-$it;
          }
          elseif ($tt < $it) {
            $increment_original['row']['backward'][$count][] = $tone+$tt-$it;
          }
        }
      }
      $count++;
    }

    // And now we want to start calculating the sums of each of the diagonals
    // using their grid_x and grid_y values and using a counting system
    // based on the $tone value.  Oh yeah, WITHOUT a database...
    $count = 1;
    foreach ($spliced_matrix as $row) {
      foreach ($row as $item) {
        $tt = 0;
        $it = $item['tone'];
        $c = $item['grid_x'];
        $c1 = $c+1;
        $r = $item['grid_y'];
        $r1 = $r;
        $r1 = $r+1;
        if (isset($spliced_matrix[$r1][$c1]['tone'])) {
          $tt = $spliced_matrix[$r1][$c1]['tone'];
        }
        if ($tt>0 && $it>0) {
          if ($tt >= $it) {
            $increment_original['lrdiag']['forward'][$count][] = $tt - $it;
          }
          elseif ($it > $tt) {
            $increment_original['lrdiag']['forward'][$count][] = $tone+$tt-$it;
          }
        }
      }
      $count++;
    }
    $count = 1;
    foreach ($spliced_matrix as $row) {
      foreach ($row as $item) {
        $tt = 0;
        $it = $item['tone'];
        $c = $item['grid_x'];
        $c1 = $c-1;
        $r = $item['grid_y'];
        $r1 = $r-1;
        if (isset($spliced_matrix[$r1][$c1]['tone'])) {
          $tt = $spliced_matrix[$r1][$c1]['tone'];
        }
        if ($tt>0 && $it>0) {
          if ($it <= $tt && isset($tt)) {
            $increment_original['lrdiag']['backward'][$count][] = $tt-$it;
          }
          elseif ($tt < $it) {
            $increment_original['lrdiag']['backward'][$count][] = $tone+$tt-$it;
          }
        }
      }
      $count++;
    }
    $count = 1;
    foreach ($spliced_matrix as $row) {
      foreach ($row as $item) {
        $tt = 0;
        $it = $item['tone'];
        $c = $item['grid_x'];
        $c1 = $c+1;
        $r = $item['grid_y'];
        $r1 = $r-1;
        if (isset($spliced_matrix[$r1][$c1]['tone'])) {
          $tt = $spliced_matrix[$r1][$c1]['tone'];
        }
        if ($tt>0 && $it>0) {
          if ($tt >= $it) {
            $increment_original['rldiag']['forward'][$count][] = $tt-$it;
          }
          elseif ($it > $tt) {
            $increment_original['rldiag']['forward'][$count][] = $tone+$tt-$it;
          }
        }
      }
      $count++;
    }
    $count = 1;
    foreach ($spliced_matrix as $row) {
      $c = 1;
      foreach ($row as $item) {
        $tt = 0;
        $it = $item['tone'];
        $c = $item['grid_x'];
        $c1 = $c-1;
        $r = $item['grid_y'];
        $r1 = $r+1;
        if (isset($spliced_matrix[$r1][$c1]['tone'])) {
          $tt = $spliced_matrix[$r1][$c1]['tone'];
        }
        if ($tt>0 && $it>0) {
          if ($tt >= $it) {
            $increment_original['rldiag']['backward'][$count][] = $tt-$it;
          }
          elseif ($tt < $it) {
            $increment_original['rldiag']['backward'][$count][] = $tone+$tt-$it;
          }
        }
      }
      $count++;
    }
    return $increment_original;
  }
}

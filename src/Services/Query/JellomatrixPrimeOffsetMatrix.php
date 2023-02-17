<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixPrimeOffsetMatrix
 *
 * @author eleven11
 */
class JellomatrixPrimeOffsetMatrix {
  
  /**
   * Returns the Response Offset Matrix.
   * name: jellomatrix_prime_offset_matrix
   * @param $tone
   * @param $interval
   * @param $offset
   * @return = array()
   *
   **/
  public function getPrimeOffsetMatrix($tone, $interval, $offset) {
    $matrix_count = 1;
    $prime_bt = array();

    // Make sure the line above took.
    if ($interval >= $tone) {
      $balance = $interval - $tone;
      $row = 0;
      for ($i = 1; $i <= $interval; $i++) {
        $row++;
        $record = 'first';
        $column = 0;
        for ($t = 1; $t <= $tone; $t++) {
          $column++;
          if ($record == 'first') {
            if ($i <= $tone) {
              $prime_bt[$i][$t]['tone'] = $i;
            }
            else {
              $prime_bt[$i][$t]['tone'] = $i-$tone;
              $sm_ct = 0;
              while ($prime_bt[$i][$t]['tone'] > $tone) {
                $prime_bt[$i][$t]['tone'] = $prime_bt[$i][$t]['tone']-$tone;
                $sm_ct++;
              }
            }
            $prime_bt[$i][$t]['column'] = $column;
            $prime_bt[$i][$t]['row'] = $row;
            $prime_bt[$i][$t]['grid_x'] = $column;
            $prime_bt[$i][$t]['grid_y'] = $row;
            $prime_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
            $prime_bt[$i][$t]['interval'] = floor($prime_bt[$i][$t]['count']/$tone*$interval);
            $prime_bt[$i][$t]['color'] = '#333';
            $prime_bt[$i][$t]['opacity'] = 1;
            $prime_bt[$i][$t]['padding'] = 3;
            $prime_bt[$i][$t]['background'] = '#fafafa';
            $record = $prime_bt[$i][$t]['tone'];
          }
          else {
            if (2*$record <= $interval) {
              while ($record >= $tone) {
                $record = $record-$tone;
              }
            }
            $new_record = $record + $balance;
            while ($new_record > $tone) {
              $new_record = $new_record - $tone;
            }

            $record = $new_record;


            $prime_bt[$i][$t]['tone'] = $record;
            $prime_bt[$i][$t]['column'] = $column;
            $prime_bt[$i][$t]['row'] = $row;
            $prime_bt[$i][$t]['grid_x'] = $column;
            $prime_bt[$i][$t]['grid_y'] = $row;
            $prime_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
            $prime_bt[$i][$t]['interval'] = floor($prime_bt[$i][$t]['count']/$tone*$interval);
            $prime_bt[$i][$t]['color'] = '#333';
            $prime_bt[$i][$t]['opacity'] = 1;
            $prime_bt[$i][$t]['padding'] = 3;
            $prime_bt[$i][$t]['background'] = '#fafafa';
          }
          $matrix_count++;
        }
      }
    }

    if ($offset < 0) {
      for ($i = 1; $i <= $offset; $i++) {
        array_shift($prime_bt);
      }
    }
    elseif ($offset > 0) {
      for ($i = 1; $i <= $offset; $i++) {
        array_pop($prime_bt);
      }
    }

    $old_arr = $prime_bt;
    $prime_bt = array();
    $i = 1;
    foreach($old_arr as $old_val) {
        $prime_bt[$i]  = $old_val;
        $i++;
    }
    foreach($prime_bt as $r=>$row) {
      foreach ($row as $i=>$item) {
        $prime_bt[$r][$i]['row'] = $r;
        $prime_bt[$r][$i]['grid_y'] = $r;
      }
    }

    return $prime_bt;
  }
}

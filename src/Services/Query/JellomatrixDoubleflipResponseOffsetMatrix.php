<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixDoubleflipResponseOffsetMatrix
 *
 * @author eleven11
 */
class JellomatrixDoubleflipResponseOffsetMatrix {
  
  /**
   * Returns the Doubleflip Response Offset Matrix.
   * name: jellomatrix_doubleflip_response_offset_matrix
   * @param $tone
   * @param $interval
   * @param $offset
   * @return = array()
   *
   **/
  public function getDoubleflipResponseOffsetMatrix($tone, $interval, $offset) {
    $matrix_count = 1;
    $response_bt = array();

    // Make sure the line above took.
    if ($interval >= $tone) {
      $balance = $interval - $tone;
      $row = 0;
      for ($i = 1; $i <= $interval; $i++) {
        $row++;
        $column = 0;
        $record = 'first';
        for ($t = 1; $t <= $tone; $t++) {
          $column++;
          if ($record == 'first') {
            if ($i == 1 ) {
              $response_bt[$i][$t]['tone'] = $tone;
            }
            else/*if ($i > 1 && $tone >= $i )*/ {
              $response_bt[$i][$t]['tone'] = $tone - ($i-1);
            }
            while ($response_bt[$i][$t]['tone'] <= 0) {
              $response_bt[$i][$t]['tone'] = $response_bt[$i][$t]['tone']+$tone;
            }
            $response_bt[$i][$t]['column'] = $column;
            $response_bt[$i][$t]['row'] = $row;
            $response_bt[$i][$t]['grid_x'] = $column;
            $response_bt[$i][$t]['grid_y'] = $row;
            $response_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
            $response_bt[$i][$t]['interval'] = floor($response_bt[$i][$t]['count']/$tone*$interval);
            $response_bt[$i][$t]['color'] = '#333';
            $response_bt[$i][$t]['opacity'] = 1;
            $response_bt[$i][$t]['padding'] = 3;
            $response_bt[$i][$t]['background'] = '#fafafa';
            $record = $response_bt[$i][$t]['tone'];
            $matrix_count++;
          }
          else {
            while ($record <= 0) {
              $record = $record+$tone;
            }

            $new_record = $record - $balance;
            while ($new_record <= 0) {
              $new_record = $new_record + $tone;
            }
            $record = $new_record;

            $response_bt[$i][$t]['tone'] = $record;
            $response_bt[$i][$t]['column'] = $column;
            $response_bt[$i][$t]['row'] = $row;
            $response_bt[$i][$t]['grid_x'] = $column;
            $response_bt[$i][$t]['grid_y'] = $row;
            $response_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
            $response_bt[$i][$t]['interval'] = floor($response_bt[$i][$t]['count']/$tone*$interval);
            $response_bt[$i][$t]['color'] = '#333';
            $response_bt[$i][$t]['opacity'] = 1;
            $response_bt[$i][$t]['padding'] = 3;
            $response_bt[$i][$t]['background'] = '#fafafa';
            $matrix_count++;
          }
        }
      }
    }


    if ($offset < 0) {
      for ($i = 1; $i <= $offset; $i++) {
        array_pop($response_bt);
      }
    }
    elseif ($offset > 0) {
      for ($i = 1; $i <= $offset; $i++) {
        array_shift($response_bt);
      }
    }

    $old_arr = $response_bt;
    $response_bt = array();
    $i = 1;
    foreach($old_arr as $old_val) {
        $response_bt[$i]  = $old_val;
        $i++;
    }

    foreach($response_bt as $r=>$row) {
      foreach ($row as $i=>$item) {
        $response_bt[$r][$i]['row'] = $r;
        $response_bt[$r][$i]['grid_y'] = $r;
      }
    }

    return $response_bt;
  }
  public function getABHADoubleflipResponseOffsetMatrix($tone, $interval, $offset) {
    $matrix_count = 1;
    $response_bt = array();

    // Make sure the line above took.
    if ($interval >= $tone) {
      $balance = $interval - $tone;
      $row = 0;
      for ($i = 1; $i <= $interval; $i++) {
        $row++;
        $column = 0;
        $record = 'first';
        for ($t = 1; $t <= $tone; $t++) {
          $column++;
          if ($record == 'first') {
            if ($i == 1 ) {
              $response_bt[$i][$t]['tone'] = $tone;
            }
            else/*if ($i > 1 && $tone >= $i )*/ {
              $response_bt[$i][$t]['tone'] = $tone - ($i-1);
            }
            while ($response_bt[$i][$t]['tone'] <= 0) {
              $response_bt[$i][$t]['tone'] = $response_bt[$i][$t]['tone']+$tone;
            }
            $response_bt[$i][$t]['column'] = $column;
            $response_bt[$i][$t]['row'] = $row;
            $response_bt[$i][$t]['grid_x'] = $column;
            $response_bt[$i][$t]['grid_y'] = $row;
            $response_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
            $response_bt[$i][$t]['interval'] = floor($response_bt[$i][$t]['count']/$tone*$interval);
            $response_bt[$i][$t]['color'] = '#333';
            $response_bt[$i][$t]['opacity'] = 1;
            $response_bt[$i][$t]['padding'] = 3;
            $response_bt[$i][$t]['background'] = '#fafafa';
            $record = $response_bt[$i][$t]['tone'];
            $matrix_count++;
          }
          else {
            while ($record <= 0) {
              $record = $record+$tone;
            }

            $new_record = $record - $balance;
            while ($new_record <= 0) {
              $new_record = $new_record + $tone;
            }
            $record = $new_record;

            $response_bt[$i][$t]['tone'] = $record;
            $response_bt[$i][$t]['column'] = $column;
            $response_bt[$i][$t]['row'] = $row;
            $response_bt[$i][$t]['grid_x'] = $column;
            $response_bt[$i][$t]['grid_y'] = $row;
            $response_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
            $response_bt[$i][$t]['interval'] = floor($response_bt[$i][$t]['count']/$tone*$interval);
            $response_bt[$i][$t]['color'] = '#333';
            $response_bt[$i][$t]['opacity'] = 1;
            $response_bt[$i][$t]['padding'] = 3;
            $response_bt[$i][$t]['background'] = '#fafafa';
            $matrix_count++;
          }
        }
      }
    }


    if ($offset < 0) {
      for ($i = 1; $i <= $offset; $i++) {
        array_pop($response_bt);
      }
    }
    elseif ($offset > 0) {
      for ($i = 1; $i <= $offset; $i++) {
        array_shift($response_bt);
      }
    }

    $old_arr = $response_bt;
    $response_bt = array();
    $i = 1;
    foreach($old_arr as $old_val) {
        $response_bt[$i]  = $old_val;
        $i++;
    }

    foreach($response_bt as $r=>$row) {
      foreach ($row as $i=>$item) {
        $response_bt[$r][$i]['row'] = $r;
        $response_bt[$r][$i]['grid_y'] = $r;
      }
    }

    return $response_bt;
  }
}

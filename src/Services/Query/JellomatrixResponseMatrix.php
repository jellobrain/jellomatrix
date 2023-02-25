<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixResponseMatrix
 *
 * @author eleven11
 */
class JellomatrixResponseMatrix {
  
  /**
   * Returns the Response Matrix.
   * name: jellomatrix_response_matrix
   * @param $tone
   * @param $interval
   * @return = array()
   *
   **/
  public function getResponseMatrix($tone, $interval) {
    $matrix_count = 1;
    $response_bt = array();

    // Make sure the line above took.
    if ($interval >= $tone) {
      $balance = $interval - $tone;
      $row = 0;
      for ($i = 1; $i <= $interval; $i++) {
        $row++;
        $record = 'first';
        $column = 0;
        for ($t = $tone; $t >= 1; $t--) {
          $column++;
          if ($record == 'first') {
            if ($i == 1 ) {
              $response_bt[$i][$t]['tone'] = $t;
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
          }
        }
        $matrix_count++;
      }
    }

    return $response_bt;
  }
  public function getABHAResponseMatrix($tone, $interval) {
    $matrix_count = 1;
    $response_bt = array();

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
            if ($i == 1 ) {
              $response_bt[$i][$t]['tone'] = $t;
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
          }
        }
        $matrix_count++;
      }
    }

    return $response_bt;
  }
}

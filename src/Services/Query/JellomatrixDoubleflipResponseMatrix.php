<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixDoubleflipResponseMatrix
 *
 * @author eleven11
 */
class JellomatrixDoubleflipResponseMatrix {
  
  /**
   * Returns the Doubleflip Response Matrix.
   * name: jellomatrix_doubleflip_response_matrix
   * @param $tone
   * @param $interval
   * @return = array()
   *
   **/
  public function getDoubleflipResponseMatrix($tone, $interval) {
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
            $response_bt[$i][$t]['count'] = (($column-1)*$interval)+$row; //BOOKMARK
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

    return $response_bt;
  }
  public function getABHADoubleflipResponseMatrix($tone, $interval) {
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
            $response_bt[$i][$t]['count'] = (($column-1)*$interval)+$row; //BOOKMARK
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

    return $response_bt;
  }
}

<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixDoubleflipSplicedMatrix
 *
 * @author eleven11
 */
class JellomatrixDoubleflipSplicedMatrix {
  /**
   * Returns the Doubleflip Spliced Matrix.
   * name: jellomatrix_doubleflip_spliced_matrix
   * @param $prime_matrix
   * @param $response_matrix
   * @param $tone
   * @param $interval
   * @return array = array()
   *
   * @internal param $ = $prime_matrix, $prime_reversed
   */
  public function getDoubleflipSplicedMatrix($prime_matrix, $response_matrix, $tone, $interval) {
    $spliced_bt = array();
    for ($i = 1; $i <= $interval; $i++) {
      $count = 1;
      for ($t = 1; $t <= $tone; $t++) {
        if ($prime_matrix[$i][$t]['count'] == 1) {
          $prime_matrix[$i][$t]['spliced_count'] = $count;
          $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
          $prime_splice_record = $prime_matrix[$i][$t]['spliced_count'];
          $count++;
          $prime_matrix[$i][$t]['spliced_count'] = $count;
          $prime_matrix[$i][$t]['column'] = $prime_matrix[$i][$t]['column']+1;
          $prime_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column']+1;
          $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
          $response_splice_record = $prime_matrix[$i][$t]['spliced_count'];
          $count++;
        }
        else {
          if (isset($response_splice_record)) {
            if (isset($prime_splice_record)) {
              $prime_matrix[$i][$t]['spliced_count'] = $count;
              $prime_matrix[$i][$t]['column'] = ($prime_matrix[$i][$t]['column'] * 2) - 1;
              $prime_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column'];
              $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
              $count++;
              $prime_matrix[$i][$t]['spliced_count'] = $count;
              $prime_matrix[$i][$t]['column'] = $prime_matrix[$i][$t]['column'] + 1;
              $prime_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column']+1;
              $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
              $response_splice_record = $prime_matrix[$i][$t]['spliced_count'];
              $count++;
            }
          }
        }
      }
    }

    return $spliced_bt;
  }
  
  public function getABHADoubleflipSplicedMatrix($response_matrix, $prime_matrix, $tone, $interval) {
    $spliced_bt = array();
    for ($i = 1; $i <= $interval; $i++) {
      $count = 1;
      for ($t = 1; $t <= $tone; $t++) {
        if ($response_matrix[$i][$t]['count'] == 1) {
          $response_matrix[$i][$t]['spliced_count'] = $count;
          $spliced_bt[$i][$count] = $response_matrix[$i][$t];
          $prime_splice_record = $response_matrix[$i][$t]['spliced_count'];
          $count++;
          $prime_matrix[$i][$t]['spliced_count'] = $count;
          $prime_matrix[$i][$t]['column'] = $response_matrix[$i][$t]['column']+1;
          $prime_matrix[$i][$t]['grid_x'] = $response_matrix[$i][$t]['column']+1;
          $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
          $response_splice_record = $prime_matrix[$i][$t]['spliced_count'];
          $count++;
        }
        else {
          if (isset($response_splice_record)) {
            if (isset($prime_splice_record)) {
              $response_matrix[$i][$t]['spliced_count'] = $count;
              $response_matrix[$i][$t]['column'] = ($response_matrix[$i][$t]['column'] * 2) - 1;
              $response_matrix[$i][$t]['grid_x'] = $response_matrix[$i][$t]['column'];
              $spliced_bt[$i][$count] = $response_matrix[$i][$t];
              $count++;
              $prime_matrix[$i][$t]['spliced_count'] = $count;
              $prime_matrix[$i][$t]['column'] = $response_matrix[$i][$t]['column'] + 1;
              $prime_matrix[$i][$t]['grid_x'] = $response_matrix[$i][$t]['column']+1;
              $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
              $response_splice_record = $prime_matrix[$i][$t]['spliced_count'];
              $count++;
            }
          }
        }
      }
    }

    return $spliced_bt;
  }
}

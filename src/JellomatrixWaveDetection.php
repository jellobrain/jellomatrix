<?php

namespace Drupal\jellomatrix;

/**
 * Description of JellomatrixWaveDetection
 *
 * @author eleven11
 */
class JellomatrixWaveDetection {
  
  /**
   * Returns the Wave Detection.
   * name: jellomatrix_wave_detection
   * @param $spliced_matrix
   * @param $spliced_matrix_reversed
   * @param $tone
   * @param $interval
   * @param $scale
   * @param $dir
   * @return = array()
   *
   **/
  public function getWaveDetection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale) {
    //dpm($tone);
    $color_array = [];
    $color_array = [
      0 => 'powderblue',
      1 => 'orange',
      2 => 'plum',
      3 => 'seagreen',
      4 => 'darkorange',
      5 => 'steelblue',
      6 => 'dodgerblue',
      7 => 'indianred',
      8 => 'darkolivegreen',
      9 => 'olive',
      10 => 'coral',
      11 => 'pink',
      12 => 'darkgreen',
      13 => 'lightpink',
      14 => 'lightblue',
      15 => 'steelblue',
      16 => 'gold',
      17 => 'tomato',
      18 => 'greenyellow',
      19 => 'olivedrab',
      20 => 'lavenderblush',
      21 => 'gold',
      22 => 'cadetblue',
      23 => 'cornflowerblue',
      24 => 'goldenrod',
      25 => 'rosybrown',
      26 => 'cornsilk',
      27 => 'mediumslateblue',
      28 => 'yellowgreen',
      29 => 'lavendar',
      30 => 'thistle',
      31 => 'darkkhaki',
      32 => 'purple',
      33 => 'lightgoldenrod',
      34 => 'mediumseagreen',
      35 => 'chocolate',
      36 => 'skyblue',
      37 => 'mediumorchid',
      38 => 'lightsteelblue',
      39 => 'deeppink',
      40 => 'plum',
      41 => 'khaki',
      42 => 'slateblue',
      43 => 'lightseagreen',
      44 => 'darkorange',
      45 => 'turquoise',
      46 => 'royalblue',
      47 => 'seashell',
      48 => 'mistyrose',
      50 => 'cadetblue',
      51 => 'teal',
      52 => 'gold',
      53 => 'darksalmon',
      54 => 'darkseagreen',
      55 => 'lightsalmon',
      56 => 'darkmagenta',
      57 => 'deepskyblue',
      58 => 'navy',
      59 => 'goldenrod',
      60 => 'lighskyblue',
      61 => 'salmon',
      62 => 'orangered',
      63 => 'brown',
      64 => 'azure',
      65 => 'pink',
      66 => 'firebrick',
      67 => 'maroon',
      68 => 'rebeccapurple',
      69 => 'khaki',
      70 => 'darkolivegreen',
      71 => 'pink',
      72 => 'indigo',
      73 => 'seagreen',
      74 => 'pink',
      75 => 'darkorange',
      76 => 'dodgerblue',
      77 => 'coral',
      78 => 'paleturquiose',
      79 => 'darkgreen',
      80 => 'lightpink',
      81 => 'orange',
      82 => 'lightrblue',
      83 => 'steelblue',
      84 => 'tomato',
      85 => 'powderblue',
      86 => 'olivedrab',
      87 => 'lavenderblush',
      88 => 'gold',
      89 => 'cadetblue',
      90 => 'cornflowerblue',
      91 => 'goldenrod',
      92 => 'rosybrown',
      93 => 'cornsilk',
      94 => 'mediumslateblue',
      95 => 'yellowgreen',
      96 => 'lavendar',
      97 => 'thistle',
      98 => 'darkkhaki',
      99 => 'purple',
      100 => 'lightgoldenrod',
      101 => 'mediumseagreen',
      102 => 'chocolate',
      103 => 'skyblue',
      104 => 'mediumorchid',
      105 => 'lightsteelblue',
      106 => 'deeppink',
      107 => 'plum',
      108 => 'khaki',
      109 => 'slateblue',
      110 => 'papayawhip',
      111 => 'darkorange',
      112 => 'turquoise',
      113 => 'royalblue',
      114 => 'mistyrose',
      115 => 'cadetblue',
      116 => 'teal',
      117 => 'gold',
      118 => 'darksalmon',
      119 => 'darkseagreen',
      120 => 'lightsalmon',
      121 => 'darkmagenta',
      122 => 'deepskyblue',
      123 => 'navy',
      124 => 'goldenrod',
      125 => 'lighskyblue',
      126 => 'salmon',
      127 => 'orangered',
      128 => 'brown',
      129 => 'azure',
      130 => 'pink',
      131 => 'firebrick',
      132 => 'maroon',
      133 => 'rebeccapurple',
      134 => 'khaki',
      135 => 'darkolivegreen',
      136 => 'pink',
      137 => 'indianred',
      138 => 'greenyellow',
    ];

    // Gets rid of empty arrays.
    $color_array = array_reverse($color_array);
    //dpm($scales);
    //foreach ($scales as $d => $scale) {
      //if ($dir == 'h') {  // TODO

        //dpm('dir ' . $dir);
        $wave_boundary_count = 1;

        foreach ($spliced_matrix as $key => $spliced_row) {
          foreach ($spliced_row as $k => $item) {
            if (isset($item['wave_limit'])) {
              if ($item['wave_limit'] == 'active' && $item['column'] <= 1) {
                $wave_boundary_count++;
                $spliced_matrix[$key][$k]['wave_limit_processed'] = 1;
                $current_position = $spliced_matrix[$key][$k];
                if ($wave_boundary_count % 2 == 0) {
                  $spliced_matrix[$key][$k]['wave_vertical'] = 'top';
                } else {
                  $spliced_matrix[$key][$k]['wave_vertical'] = 'bottom';
                }

                // test
                if (isset($spliced_matrix[$key][$k]['wave'])) {
                  dpm('beep');
                  //unset($spliced_matrix[$key][$k]['wave']);
                }

                $vertical_directions = array('down', 'up');

                foreach ($vertical_directions as $v_dir) {
                  //dpm('v ' . $v_dir . ' | p ' . $p);
                  for ($i = 1; $i <= $tone; $i++) {
                    //dpm('i ' . $i);
                    ////dpm('jump = ' . $i);
                    ///
                    // $k is the column value.

                    // $p is the pole value (where numbers duplicate is it the first or the second?). My question is
                    // which 'p' value (1 or 2) is at the front or back...This variable name or value should be rethought...

                    // $i is the value of the $tone but ends up representing here the possible number of columns between
                    // occurrences of the wave which cannot exceed the value of the tone (this might be arbitrary as a
                    // designation and might wanted to get tested later as a TODO.
                    // 2=right value, 1=left value
                    //dpm($current_position['pole_shift']);
                    if (isset($current_position['pole_shift']) && $current_position['pole_shift'] == 2) {
                      $pole = 0;
                    }
                    if (isset($current_position['pole_shift']) && $current_position['pole_shift'] == 1) {
                      $pole = 1;
                    }

                    // BOOKMARK issue with offset
                    //dpm($tone);
                    $twotone = 2*$tone;
                    //dpm('2tone: ' . $twotone);

                    //dpm($current_position);
                    if (isset($current_position['pole_shift'])) {

                      $k_i = $k + $i;
                      $k_i_p = $k + $i + $pole;
                      $twok_i_p = $k + (2 * $i) + $pole;
                      $threek_i_p = $k + (3 * $i) + $pole;
                      $k_i_pp = $k + $i + 1 + $pole;
                      $twok_i_pp = $k + (2 * $i) + 1 + $pole;
                      $threek_i_pp = $k + (3 * $i) + 1 + $pole;
                      $k_i_pnp = $k + $i - 1 + $pole;
                      $twok_i_pnp = $k + (2 * $i) - 1 + $pole;
                      $threek_i_pnp = $k + (3 * $i) - 1 + $pole;

                      ////dpm('vertical direction = ' . $v_dir);
                      if ($current_position['pole_shift'] == '2') {
                        //dpm('poleshiftright');
                      }
                      if ($current_position['pole_shift'] == '1') {
                        //dpm('poleshiftleft');
                      }

                      $twoktone = $twok_i_p - $twotone;
                      $threektone = $twok_i_p + $i - $twotone;
                      //$threektone = $threek_i_p - $twotone;
                      $twokptone = $twok_i_pp - $twotone;
                      $threekptone = $twok_i_pp + $i - $twotone;
                      //$threekptone = $threek_i_pp - $twotone;
                      if ($twoktone > $twotone) {
                        //dpm($twoktone);
                      }
                      if ($twokptone > $twotone) {
                        //dpm($twokptone);
                      }
                      if ($threektone > $twotone) {
                        //dpm($threektone);
                      }
                      if ($threekptone > $twotone) {
                        //dpm($threekptone);
                      }
                      //dpm($key);
                      //dpm($k);
                      $nadjacent = 0;
                      $stop = 0;
                      $next_position = [];
                      $next_next_position = [];
                      $next_next_next_position = [];
                      $next_adjacent_position = [];
                      $next_next_adjacent_position = [];
                      $next_next_next_adjacent_position = [];

                      if (!empty($spliced_matrix[$key][$k + 1]) && $spliced_matrix[$key][$k + 1]['tone'] == $spliced_matrix[$key][$k]['tone'] && $spliced_matrix[$key][$k + 1]['row'] == $spliced_matrix[$key][$k]['row']) {
                        $next_position = $spliced_matrix[$key][$k + 1];
                        $next_adjacent_position = $spliced_matrix[$key][$k + 2];
                        $nadjacent++;

                      }

                      if (!empty($spliced_matrix[$key + 1][$k_i_p]['tone']) && $spliced_matrix[$key][$k + 1]['tone'] != $spliced_matrix[$key][$k]['tone'] && $v_dir == 'down') {
                        $next_position = $spliced_matrix[$key + 1][$k_i_p];
                        if (!empty($spliced_matrix[$key + 1][$k_i_p + 1])) {
                          $next_adjacent_position = $spliced_matrix[$key + 1][$k_i_p + 1];
                        }
                      }
                      if (!empty($spliced_matrix[$key - 1][$k_i_p]['tone']) && $spliced_matrix[$key][$k + 1]['tone'] != $spliced_matrix[$key][$k]['tone'] && $v_dir == 'up') {
                        $next_position = $spliced_matrix[$key - 1][$k_i_p];
                        if (!empty($spliced_matrix[$key - 1][$k_i_p + 1])) {
                          $next_adjacent_position = $spliced_matrix[$key - 1][$k_i_p + 1];
                        }
                      }

                      if (!empty($next_adjacent_position['tone']) && $next_adjacent_position['tone'] == $next_position['tone'] && isset($spliced_matrix[$key][$twok_i_p + 1])) {
                        $next_next_position = $next_adjacent_position;
                        $next_next_next_position = $spliced_matrix[$key][$twok_i_p + 1];
                        $nadjacent++;
                        $stop = 1;
                      }

                      //dpm('NEXTPOSITION:' . $v_dir);
                      //dpm($next_position);
                      if (!empty($next_position) && $stop == 0) {
                        //dpm($i . '/' . $v_dir . '/tone/' . $next_adjacent_position['tone'] . '/sp/' . $next_adjacent_position['scale_position'] . '/col/' . $next_adjacent_position['column'] . '/row/' . $next_adjacent_position['row']);
                        //dpm($i . '/' . $v_dir . '/tone/' . $spliced_matrix[$key][$twok_i_p + 1]['tone'] . '/sp/' . $spliced_matrix[$key][$twok_i_p + 1]['scale_position'] . '/col/' . $spliced_matrix[$key][$twok_i_p + 1]['column'] . '/row/' . $spliced_matrix[$key][$twok_i_p + 1]['row']);
                        if (!empty($next_adjacent_position['tone']) && $next_position['tone'] == $next_adjacent_position['tone'] && isset($spliced_matrix[$key + 1][$twok_i_p + 1])) {
                          $next_next_position = $spliced_matrix[$key + 1][$twok_i_p + 1];
                          $nadjacent++;

                        } else {
                          if ($nadjacent == 0) {
                            //dpm('2AY');
                            if (!empty($spliced_matrix[$key + 2][$twok_i_p]['tone']) && $v_dir == 'down') {
                              //dpm('2EY');
                              $next_next_position = $spliced_matrix[$key + 2][$twok_i_p];
                              if (!empty($spliced_matrix[$key + 2][$twok_i_p + 1]['tone']) && $spliced_matrix[$key + 2][$twok_i_pp]['tone'] != NULL) {
                                //dpm('2OY');
                                //dpm($spliced_matrix[$key + 2][$twok_i_pp]);
                                $next_next_adjacent_position = $spliced_matrix[$key + 2][$twok_i_p + 1];
                              } else {
                                $column = $twok_i_p + 1;
                                //dpm('2EEYY: ' . $column);
                                if ($column > $twotone) {
                                  $twokk_i_p = $column - $twotone;
                                }
                                if (!empty($spliced_matrix[$key + 2][$twokk_i_p]['tone'])) {
                                  $next_next_adjacent_position = $spliced_matrix[$key + 2][$twokk_i_p];
                                }
                              }
                            } elseif ($twoktone > 0 && !empty($spliced_matrix[$key + 2][$twoktone]['tone']) && $v_dir == 'down') {
                              //dpm('2BY');
                              $next_next_position = $spliced_matrix[$key + 2][$twoktone];
                              //dpm($spliced_matrix[$key + 2][$twoktone]);
                              if (!empty($spliced_matrix[$key + 2][$twokptone]['tone'])) {
                                //dpm('2FY');
                                $next_next_adjacent_position = $spliced_matrix[$key + 2][$twokptone];
                                //dpm($spliced_matrix[$key + 2][$twokptone]);
                              }
                            }

                            //dpm('2UAY');
                            if (!empty($spliced_matrix[$key - 2][$twok_i_p]['tone']) && $v_dir == 'up') {
                              $next_next_position = $spliced_matrix[$key - 2][$twok_i_p];
                              //dpm('2UEY');
                              if (!empty($spliced_matrix[$key - 2][$twok_i_p + 1]['tone']) && $spliced_matrix[$key - 2][$twok_i_pp]['tone'] != NULL) {
                                //dpm('2UOY');
                                //dpm($spliced_matrix[$key - 2][$twok_i_pp]);
                                $next_next_adjacent_position = $spliced_matrix[$key - 2][$twok_i_p + 1];
                              } else {
                                $column = $twok_i_p + 1;
                                //dpm('2UEEYY: ' . $column);
                                if ($column > $twotone) {
                                  $twokk_i_p = $column - $twotone;
                                }
                                if (!empty($spliced_matrix[$key - 2][$twokk_i_p]['tone'])) {
                                  $next_next_adjacent_position = $spliced_matrix[$key - 2][$twokk_i_p];
                                }
                              }
                            } elseif ($twoktone > 0 && !empty($spliced_matrix[$key - 2][$twoktone]['tone']) && $v_dir == 'up') {
                              //dpm('2UBY');
                              $next_next_position = $spliced_matrix[$key - 2][$twoktone];
                              //dpm($spliced_matrix[$key - 2][$twoktone]);
                              if (!empty($spliced_matrix[$key - 2][$twokptone]['tone'])) {
                                //dpm('2UFY');
                                $next_next_adjacent_position = $spliced_matrix[$key - 2][$twokptone];
                                //dpm($spliced_matrix[$key - 2][$twokptone]);
                              }
                            }
                          }
                          else {
                            if ($nadjacent >= 1 && $stop == 0) {
                              //dpm('2AY');
                              if (!empty($spliced_matrix[$key + 1][$k_i_p]['tone']) && $v_dir == 'down') {
                                //dpm('2EY');
                                $next_next_position = $spliced_matrix[$key + 1][$k_i_p];
                                if (!empty($spliced_matrix[$key + 1][$k_i_p + 1]['tone']) && $spliced_matrix[$key + 1][$k_i_p + 1]['tone'] != NULL) {
                                  //dpm('2OY');
                                  //dpm($spliced_matrix[$key + 1][$twok_i_pp]);
                                  $next_next_adjacent_position = $spliced_matrix[$key + 1][$k_i_p + 1];
                                } else {
                                  $column = $k_i_p + 1;
                                  //dpm('2EEYY: ' . $column);
                                  if ($column > $tone) {
                                    $kk_i_p = $column - $tone;
                                  }
                                  if (!empty($spliced_matrix[$key + 1][$kk_i_p]['tone'])) {
                                    $next_next_adjacent_position = $spliced_matrix[$key + 1][$kk_i_p];
                                  }
                                }
                              } elseif ($twoktone > 0 && !empty($spliced_matrix[$key + 1][$twoktone]['tone']) && $v_dir == 'down') {
                                //dpm('2BY');
                                $next_next_position = $spliced_matrix[$key + 1][$twoktone];
                                //dpm($spliced_matrix[$key + 1][$ktone]);
                                if (!empty($spliced_matrix[$key + 2][$twoktone + 1]['tone'])) {
                                  //('2FY');
                                  $next_next_adjacent_position = $spliced_matrix[$key + 1][$twoktone + 1];
                                  //dpm($spliced_matrix[$key + 1][$kptone]);
                                }
                              }

                              //dpm('2UAY');
                              if (!empty($spliced_matrix[$key - 1][$k_i_p]['tone']) && $v_dir == 'up') {
                                $next_next_position = $spliced_matrix[$key - 1][$k_i_p];
                                //dpm('2UEY');
                                if (!empty($spliced_matrix[$key - 1][$k_i_p + 1]['tone']) && $spliced_matrix[$key - 1][$k_i_p + 1]['tone'] != NULL) {
                                  //dpm('2UOY');
                                  //dpm($spliced_matrix[$key - 1][$twok_i_p]);
                                  $next_next_adjacent_position = $spliced_matrix[$key - 1][$k_i_p + 1];
                                } else {
                                  $column = $k_i_p + 1;
                                  //dpm('2UEEYY: ' . $column);
                                  if ($column > $tone) {
                                    $kk_i_p = $column - $tone;
                                  }
                                  if (!empty($spliced_matrix[$key - 1][$kk_i_p]['tone'])) {
                                    $next_next_adjacent_position = $spliced_matrix[$key - 1][$kk_i_p];
                                  }
                                }
                              } elseif ($tone > 0 && !empty($spliced_matrix[$key - 1][$tone]['tone']) && $v_dir == 'up') {
                                //dpm('2UBY');
                                $next_next_position = $spliced_matrix[$key - 1][$tone];
                                //dpm($spliced_matrix[$key - 1][$ktone]);
                                if (!empty($spliced_matrix[$key - 1][$tone + 1]['tone'])) {
                                  //dpm('2UFY');
                                  $next_next_adjacent_position = $spliced_matrix[$key - 1][$tone + 1];
                                  //dpm($spliced_matrix[$key - 1][$kptone]);
                                }
                              }
                            }
                          }
                        }
                      }

                      if ($nadjacent == 0 && !empty($next_position['tone']) && !empty($next_next_position['tone']) && $stop == 0) {

                        if (!empty($next_next_position['tone']) && !empty($next_next_adjacent_position['tone']) && $next_next_position['tone'] == $next_next_adjacent_position['tone']) {
                          $next_next_next_position = $next_next_adjacent_position;
                          $nadjacent++;
                        } else {
                          //dpm('3AY');
                          if (!empty($spliced_matrix[$key + 3][$threek_i_p]['tone']) && $v_dir == 'down') {
                            //dpm('3EY');
                            $next_next_next_position = $spliced_matrix[$key + 3][$threek_i_p];
                            if (!empty($spliced_matrix[$key + 3][$threek_i_pp]['tone']) && $spliced_matrix[$key + 3][$threek_i_pp]['tone'] != NULL) {
                              //dpm('3OY');
                              //dpm($spliced_matrix[$key + 3][$threek_i_pp]);
                              $next_next_next_adjacent_position = $spliced_matrix[$key + 3][$threek_i_pp];
                            } else {
                              $column = $threek_i_pp;
                              //dpm('3EEYY: ' . $column);
                              if ($column > $twotone) {
                                $threekk_i_p = $column - $twotone;
                              }
                              if (!empty($spliced_matrix[$key + 3][$threekk_i_p]['tone'])) {
                                $next_next_next_adjacent_position = $spliced_matrix[$key + 3][$threekk_i_p];
                              }
                            }
                          } elseif ($threektone > 0 && !empty($spliced_matrix[$key + 3][$threektone]['tone']) && $v_dir == 'down') {
                            //dpm('3BY');
                            $next_next_next_position = $spliced_matrix[$key + 3][$threektone];
                            //dpm($spliced_matrix[$key + 3][$threektone]);
                            if (!empty($spliced_matrix[$key + 3][$threekptone]['tone'])) {
                              //dpm('3FY');
                              $next_next_next_adjacent_position = $spliced_matrix[$key + 3][$threekptone];
                              //dpm($spliced_matrix[$key + 3][$threekptone]);
                            }
                          }
                          //dpm('3UAY');
                          if (!empty($spliced_matrix[$key - 3][$threek_i_p]['tone']) && $v_dir == 'up') {
                            //dpm('3UEY');
                            $next_next_next_position = $spliced_matrix[$key - 3][$threek_i_p];
                            if (!empty($spliced_matrix[$key - 3][$threek_i_pp]['tone']) && $spliced_matrix[$key - 3][$threek_i_pp]['tone'] != NULL) {
                              //dpm('3UOY');
                              //dpm($spliced_matrix[$key - 3][$threek_i_pp]);
                              $next_next_next_adjacent_position = $spliced_matrix[$key - 3][$threek_i_pp];
                            } else {
                              $column = $threek_i_pp;
                              //dpm('3UEEYY: ' . $column);
                              if ($column > $twotone) {
                                $threekk_i_p = $column - $twotone;
                              }
                              if (!empty($spliced_matrix[$key - 3][$threekk_i_p]['tone'])) {
                                $next_next_next_adjacent_position = $spliced_matrix[$key - 3][$threekk_i_p];
                              }
                            }
                          } elseif ($threektone > 0 && !empty($spliced_matrix[$key - 3][$threektone]['tone']) && $v_dir == 'up') {
                            //dpm('3BUY');
                            $next_next_next_position = $spliced_matrix[$key - 3][$threektone];
                            //dpm($spliced_matrix[$key - 3][$threektone]);
                            if (!empty($spliced_matrix[$key - 3][$threekptone]['tone'])) {
                              //dpm('3FUY');
                              $next_next_next_adjacent_position = $spliced_matrix[$key - 3][$threekptone];
                              //dpm($spliced_matrix[$key - 3][$threekptone]);
                            }
                          }
                        }
                      }
                      else {

                        if ($nadjacent >= 1 && !empty($next_position['tone']) && !empty($next_next_position['tone']) && $stop == 0) {
                          if (!empty($next_next_position['tone']) && !empty($next_next_adjacent_position['tone']) && $next_next_position['tone'] == $next_next_adjacent_position['tone']) {
                            $next_next_next_position = $next_next_adjacent_position;
                            $nadjacent++;
                          } else {
                            //dpm('3AY');
                            if (!empty($spliced_matrix[$key + 2][$twok_i_p]['tone']) && $v_dir == 'down') {
                              //dpm('3EY');
                              $next_next_next_position = $spliced_matrix[$key + 2][$twok_i_p];
                              if (!empty($spliced_matrix[$key + 2][$twok_i_p + 1]['tone']) && $spliced_matrix[$key + 2][$twok_i_p + 1]['tone'] != NULL) {
                                //dpm('3OY');
                                //dpm($spliced_matrix[$key + 3][$twok_i_pp]);
                                $next_next_next_adjacent_position = $spliced_matrix[$key + 2][$twok_i_p + 1];
                              } else {
                                $column = $twok_i_p + 1;
                                //dpm('3EEYY: ' . $column);
                                if ($column > $twotone) {
                                  $twokk_i_p = $column - $twotone;
                                }
                                if (!empty($spliced_matrix[$key + 2][$twokk_i_p]['tone'])) {
                                  $next_next_next_adjacent_position = $spliced_matrix[$key + 2][$twokk_i_p];
                                }
                              }
                            } elseif ($twoktone - 1 > 0 && !empty($spliced_matrix[$key + 2][$twoktone]['tone']) && $v_dir == 'down') {
                              //dpm('3BY');
                              $next_next_next_position = $spliced_matrix[$key + 2][$twoktone];
                              //dpm($spliced_matrix[$key + 2][$twoktone]);
                              if (!empty($spliced_matrix[$key + 2][$twoktone + 1]['tone'])) {
                                //dpm('3FY');
                                $next_next_next_adjacent_position = $spliced_matrix[$key + 2][$twoktone + 1];
                                //dpm($spliced_matrix[$key + 2][$twokptone]);
                              }
                            }
                            //dpm('3UAY');
                            if (!empty($spliced_matrix[$key - 2][$twok_i_p]['tone']) && $v_dir == 'up') {
                              //dpm('3UEY');
                              $next_next_next_position = $spliced_matrix[$key - 2][$twok_i_p];
                              if (!empty($spliced_matrix[$key - 2][$twok_i_p + 1]['tone']) && $spliced_matrix[$key - 2][$twok_i_p + 1]['tone'] != NULL) {
                                //dpm('3UOY');
                                //dpm($spliced_matrix[$key - 2][$twok_i_pp]);
                                $next_next_next_adjacent_position = $spliced_matrix[$key - 2][$twok_i_p + 1];
                              } else {
                                $column = $twok_i_p + 1;
                                //dpm('3UEEYY: ' . $column);
                                if ($column > $twotone) {
                                  $twokk_i_p = $column - $twotone;
                                }
                                if (!empty($spliced_matrix[$key - 2][$twokk_i_p]['tone'])) {
                                  $next_next_next_adjacent_position = $spliced_matrix[$key - 2][$twokk_i_p];
                                }
                              }
                            } elseif ($twoktone > 0 && !empty($spliced_matrix[$key - 2][$twoktone]['tone']) && $v_dir == 'up') {
                              //dpm('3BUY');
                              $next_next_next_position = $spliced_matrix[$key - 2][$twoktone];
                              //dpm($spliced_matrix[$key - 2][$twoktone]);
                              if (!empty($spliced_matrix[$key - 2][$twoktone + 1]['tone'])) {
                                //dpm('3FUY');
                                $next_next_next_adjacent_position = $spliced_matrix[$key - 2][$twoktone + 1];
                                //dpm($spliced_matrix[$key - 2][$twokptone]);
                              }
                            }
                          }
                        }
                      }
                    }

                    if (!empty($next_position)) {
                      //dpm($v_dir . ' jump: ' . $i . ' position tone: ' . $current_position['tone'] . '/' .
                      //$next_position['tone'] . '/' . $next_next_position['tone'] . '/' .
                      //$next_next_next_position['tone']);



                      $j = 1;
                      $calc_scale = array();
                      //dpm($scale);
                      $scale_new = [];
                      $scale_new = $scale;
                      for ($zip = 0; $zip < 3; $zip++) {
                        if (!isset($scale[4*$tone])) {
                          foreach ($scale as $s => $svalue) {
                            $scale_new[] =  $svalue;
                          }
                        }
                      }
                      $scale = $scale_new;
                      unset($scale_new);

                      //dpm($scale);

                      /*$check = $tone;
                      if(isset($current_position['scale']) && $current_position['scale'] == $check) {
                        if (isset($next_position['scale']) && $next_position['scale'] != $check - 1) {
                          $current_position['scale'] = 0;
                        }
                        if(isset($next_position['scale']) && isset($next_next_position['scale']) && $next_position['scale'] == $check  && $next_next_position['scale'] != $check - 1 && $next_next_position['scale'] != $check) {
                          $current_position['scale'] = 0;
                          $next_position['scale'] = 0;
                        }
                      }*/

                                //  YAY Test works
                      //dpm('<strong>TONE:</strong> jump ' . $i . ' direction ' . $v_dir . ' <strong>current ' . $current_position['tone'] . ' next ' . $next_position['tone'] . '</strong> nextA ' . $next_adjacent_position['tone'] . ' <strong>nextnext ' . $next_next_position['tone'] .  '</strong> nextnextAA ' . $next_next_adjacent_position['tone'] . ' <strong>nextnextnext ' . $next_next_next_position['tone'] .  '</strong> nextnextnextAAA ' . $next_next_next_adjacent_position['tone']);
                      //dpm('<strong>SP:</strong> jump ' . $i . ' direction ' . $v_dir . ' <strong>current ' . $current_position['scale_position'] . ' next ' . $next_position['scale_position'] . '</strong> nextA ' . $next_adjacent_position['scale_position'] . ' <strong>nextnext ' . $next_next_position['scale_position'] .  '</strong> nextnextAA ' . $next_next_adjacent_position['scale_position'] . ' <strong>nextnextnext ' . $next_next_next_position['scale_position'] .  '</strong> nextnextnextAAA ' . $next_next_next_adjacent_position['scale_position']);

                      //dpm($spliced_matrix[$key][$k]['pole_shift']);
                      //dpm('jumpColumns' . $v_dir . '(' . $i . ') ' . $k . ', ' . $k_i_p . ', ' . $twok_i_p . ', ' . $threek_i_p);
                      //if (isset($next_next_next_position['scale'])) {
                          //dpm('jumpColumns' . $v_dir . '(' . $i . ') ' . $current_position['scale'] . ', ' . $next_position['scale'] . ', ' . $next_next_position['scale'] . ', ' . $next_next_next_position['scale']);
                      //}

                      //dpm('jumpp(' . $i . ') ' . $k . ', ' . $k_i_pp . ', ' . $twok_i_pp . ', ' . $threek_i_pp);
                      //dpm('jumpnp(' . $i . ') ' . $k . ', ' . $k_i_pnp . ', ' . $twok_i_pnp . ', ' . $threek_i_pnp);
                      //dpm('jumpk(' . $i . ') ' . $twokk_i_p . ', ' . $threekk_i_p);

                      //dpm( 'scale: ' . $scale[0] . ', ' . $scale[1] . ', ' . $scale[2] . ', ' . $scale[3] . ', ' . $scale[4] . ', ' . $scale[5] . ', ' . $scale[6] . ', ' . $scale[7] . ', ' . $scale[8] . ', ' . $scale[9] . ', ' . $scale[10] . ', ' . $scale[11]);
                      /*if (!empty($next_position['scale_position']) && !empty($next_next_position['scale_position']) && !empty($next_next_next_position['scale_position'])) {
                          dpm($i . 'current (row/col/position/tone): ' . $current_position['row'] . '/' . $current_position['column'] . '/<strong>' .
                            $current_position['scale_position'] . '</strong>/' . $current_position['tone'] .'<br/>next (row/col/position/tone): ' . $next_position['row'] . '/' . $next_position['column'] . '/<strong>' .
                            $next_position['scale_position'] . '</strong>/' . $next_position['tone'] . ' - next_adjacent (row/col/position/tone): ' . $next_adjacent_position['row'] . '/' .
                            $next_adjacent_position['column'] . '/<strong>' . $next_adjacent_position['scale_position'] . '</strong>/' . $next_adjacent_position['tone'] . '<br/>nextnext (row/col/position/tone): ' .
                            $next_next_position['row'] . '/' . $next_next_position['column'] . '/<strong>' .
                            $next_next_position['scale_position'] . '</strong>/' . $next_next_position['tone'] . ' - nextnext_adjacent (row/col/position/tone): ' . $next_next_adjacent_position['row'] . '/' .
                            $next_next_adjacent_position['column'] . '/<strong>' . $next_next_adjacent_position['scale_position'] . '</strong>/' . $next_next_adjacent_position['tone'] . '<br/>nextnextnext (row/col/position/tone): ' .
                            $next_next_next_position['row'] . '/' . $next_next_next_position['column'] . '/<strong>' .
                            $next_next_next_position['scale_position'] . '</strong>/' . $next_next_next_position['tone'] . ' - nextnextnext_adjacent (row/col/position/tone): ' . $next_next_next_adjacent_position['row'] . '/' .
                            $next_next_next_adjacent_position['column'] . '/<strong>' . $next_next_next_adjacent_position['scale_position']. '</strong>/' . $next_next_next_adjacent_position['tone']);
                          //dpm($next_next_position);
                          //dpm( 'scale: ' . $scale[0] . ', ' . $scale[1] . ', ' . $scale[2] . ', ' . $scale[3]);
                      }*/


                      $jount = 1;
                      $scale_record = [];
                      foreach ($scale as $s => $svalue) {
                        if (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s]) && $next_position['tone'] == $scale[$s]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s + 1]) && $next_next_position['tone'] == $scale[$s + 1]) {
                              if (isset($next_next_next_position['tone']) && isset($scale[$s + 2]) && $next_next_next_position['tone'] == $scale[$s + 2]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }
                        //BOOKMARK
                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s]) && $next_position['tone'] == $scale[$s]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s + 1]) && $next_next_position['tone'] == $scale[$s + 1]) {
                              if (isset($next_next_adjacent_position['tone']) && isset($scale[$s + 2]) && $next_next_adjacent_position['tone'] == $scale[$s + 1]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];  //my change from next*3
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }

                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s]) && $next_position['tone'] == $scale[$s + 1]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s + 1]) && $next_next_position['tone'] == $scale[$s + 2]) {
                              if (isset($next_next_next_position['tone']) && isset($scale[$s + 2]) && $next_next_next_position['tone'] == $scale[$s + 3]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 1;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 3;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }

                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s + 1]) && $next_position['tone'] == $scale[$s + 1]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s + 2]) && $next_next_position['tone'] == $scale[$s + 2]) {
                              if (isset($next_next_next_position['tone']) && isset($scale[$s + 2]) && $next_next_next_position['tone'] == $scale[$s + 2]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 1;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];  //changed on the other one.
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }

                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone'])  && isset($scale[$s + 1]) && $next_position['tone'] == $scale[$s + 1]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s + 1]) && $next_next_position['tone'] == $scale[$s + 1]) {
                              if (isset($next_next_adjacent_position['tone']) && isset($scale[$s + 2]) && $next_next_adjacent_position['tone'] == $scale[$s + 2]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 1;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s + 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];  // my change from next*3
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }
                        //BOOKMARK
                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s + 1]) && $next_position['tone'] == $scale[$s + 1]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s + 1]) && $next_next_position['tone'] == $scale[$s + 1]) {
                                if (isset($next_next_next_position['tone']) && isset($scale[$s + 2]) && $next_next_adjacent_position['tone'] != $scale[$s + 2]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;  // this was a negative value for all of these but they were not consisten with the if statement above.
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];
                                $jount++;
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }
                      }

                      $jount = 1;
                      $scale_record = [];
                      foreach ($scale as $s => $svalue) {
                        if (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s]) && $next_position['tone'] == $scale[$s]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s - 1]) && $next_next_position['tone'] == $scale[$s - 1]) {
                              if (isset($next_next_next_position['tone']) && isset($scale[$s - 2]) && $next_next_next_position['tone'] == $scale[$s - 2]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }

                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s]) && $next_position['tone'] == $scale[$s]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s - 1]) && $next_next_position['tone'] == $scale[$s - 1]) {
                              if (isset($next_next_next_position['tone']) && isset($scale[$s - 1]) && $next_next_adjacent_position['tone'] == $scale[$s - 1]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }

                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s - 1]) && $next_position['tone'] == $scale[$s - 1]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s-2]) && $next_next_position['tone'] == $scale[$s - 2]) {
                              if (isset($next_next_next_position['tone']) && isset($scale[$s - 3]) && $next_next_next_position['tone'] == $scale[$s - 3]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 3;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }

                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s - 1]) && $next_position['tone'] == $scale[$s - 1]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s-2]) && $next_next_position['tone'] == $scale[$s - 2]) {
                              if (isset($next_next_next_position['tone'])  && isset($scale[$s-2]) && $next_next_next_position['tone'] == $scale[$s - 2]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone']; // my change
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }

                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s - 1]) && $next_position['tone'] == $scale[$s - 1]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s - 1]) && $next_next_position['tone'] == $scale[$s - 1]) {
                              if (isset($next_next_next_position['tone']) && isset($scale[$s-2]) && $next_next_adjacent_position['tone'] == $scale[$s - 2]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];  //my change
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }

                        elseif (isset($current_position['tone']) && !in_array($current_position['tone'], $scale_record) && $current_position['tone'] == $svalue && $s >= $interval && $s <= 2 * $interval) {
                          if (isset($next_position['tone']) && isset($scale[$s - 1]) && $next_position['tone'] == $scale[$s - 1]) {
                            if (isset($next_next_position['tone']) && isset($scale[$s - 1]) && $next_next_position['tone'] == $scale[$s - 1]) {
                              if (isset($next_next_next_position['tone']) && isset($scale[$s-2]) && $next_next_adjacent_position['tone'] != $scale[$s - 2]) {
                                $scale_record[] = $current_position['tone'];
                                $calc_scale[$j][$jount]['scale'] = $s;
                                $calc_scale[$j][$jount]['tone'] = $current_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 1;
                                $calc_scale[$j][$jount]['tone'] = $next_next_position['tone'];
                                $jount++;
                                $calc_scale[$j][$jount]['scale'] = $s - 2;
                                $calc_scale[$j][$jount]['tone'] = $next_next_next_position['tone'];
                                $j++;
                                $jount = 1;
                              }
                            }
                          }
                        }
                      }


                      if (isset($calc_scale[1][1])) {
                        //dpm($calc_scale);
                        //dpm($i);
                      }

                      //not sure if this is still relevant...
                      foreach ($calc_scale as $cyber => $sca) {
                        foreach ($sca as $y => $scalevar) {
                          if ($scalevar['scale'] == 0) {
                            if (isset($calc_scale[1][$y - 1]['scale']) && $calc_scale[1][$y - 1]['scale'] == $tone - 1) {
                              if (isset($calc_scale[1][$y - 2]['scale']) && $calc_scale[1][$y - 2]['scale'] == $tone - 2 || isset($calc_scale[1][$y + 1]['scale']) && $calc_scale[1][$y + 1]['scale'] == 1) {
                                $calc_scale[1][$y]['scale'] = $tone;
                                if (isset($calc_scale[1][$y + 1])) {
                                  $calc_scale[1][$y + 1]['scale'] = $tone + 1;
                                }
                                if (isset($calc_scale[1][$y + 2])) {
                                  $calc_scale[1][$y + 2]['scale'] = $tone + 2;
                                }
                              }
                            }
                            if (isset($calc_scale[1][$y + 1]) && $calc_scale[1][$y + 1]['scale'] == $tone - 1) {
                              if (isset($calc_scale[1][$y + 2]['scale']) && $calc_scale[1][$y + 2]['scale'] == $tone - 2 || isset($calc_scale[1][$y - 1]['scale']) && $calc_scale[1][$y - 1]['scale'] == 1) {
                                $calc_scale[1][$y]['scale'] = $tone;
                                if (isset($calc_scale[1][$y - 1])) {
                                  $calc_scale[1][$y - 1]['scale'] = $tone + 1;
                                }
                                if (isset($calc_scale[1][$y - 2])) {
                                  $calc_scale[1][$y - 2]['scale'] = $tone + 2;
                                }
                              }
                            }
                          }
                        }
                      }

                      //unset($next_position);
                      //unset($next_adjacent_position);
                      //unset($next_next_position);
                      //unset($next_next_adjacent_position);
                      //unset($next_next_next_position);
                      //unset($next_next_next_adjacent_position);
                      //unset($nadjacent);
                      //unset($nnadjacent);


                      //if ($item['tone'] == 7) {
                        //dpm($v_dir . ' poleshift 1 (tone/scale): ' . $calc_scale[1][1]['tone'] . '/' . $calc_scale[1][2]['tone'] . '/' . $calc_scale[1][3]['tone'] . '/' . $calc_scale[1][4]['tone'] . ' | ' . $calc_scale[1][1]['scale'] . '/' . $calc_scale[1][2]['scale'] . '/' . $calc_scale[1][3]['scale'] . '/' . $calc_scale[1][4]['scale']);
                      //}
                      //  Okay, for the issue of unidirectional waves appearing, this is where they might get culled.  I amn switching the directions and testing to see what happens.
                      // The most basic use cases first.
                      if (isset($calc_scale[1][1]) && isset($calc_scale[1][2]) && isset($calc_scale[1][3])) {
                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale'] - 1) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale'] - 1) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale'] - 1) {
                              $h_dir = 'forward'; //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }
                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale'] - 1) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale'] - 1) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale']) {
                              $h_dir = 'forward'; //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }
                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale']) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale'] - 1) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale'] - 1) {
                              $h_dir = 'forward';  //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }
                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale']) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale'] - 1) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale']) {
                              $h_dir = 'forward';  //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }
                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale'] - 1) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale']) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale'] - 1) {
                              $h_dir = 'forward';  //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }

                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale'] + 1) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale'] + 1) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale'] + 1) {
                              $h_dir = 'reversed';  //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }
                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale'] + 1) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale'] + 1) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale']) {
                              $h_dir = 'reversed';  //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }
                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale']) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale'] + 1) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale'] + 1) {
                              $h_dir = 'reversed';  //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }

                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale']) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale'] + 1) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale']) {
                              $h_dir = 'reversed';  //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }

                        if ($calc_scale[1][1]['scale'] == $calc_scale[1][2]['scale'] + 1) {
                          if ($calc_scale[1][2]['scale'] == $calc_scale[1][3]['scale']) {
                            if ($calc_scale[1][3]['scale'] == $calc_scale[1][4]['scale'] + 1) {
                              $h_dir = 'reversed';  //TODO: changed to opposite
                              $tempcolor = array_pop($color_array);
                              $scale_increments[] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                              $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1][1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                            }
                          }
                        }
                      }
                      // TODO test
                      //unset($calc_scale);
                    }
                  }
                }
              }
            }
          }
        }




        $wave_heights = array();

        // Find wave height;
        $wavecount = 0;
        $wave_height = 0;
        unset($first);

        foreach ($spliced_matrix as $key => $spliced_row) {
          foreach ($spliced_row as $k => $item) {

            if ($spliced_matrix[$key][$k]['column'] == 1) {

              if (isset($spliced_matrix[$key][$k]['wave_limit_processed']) &&
                $spliced_matrix[$key][$k]['wave_limit_processed'] == 1 && $spliced_matrix[$key][$k]['column'] == 1) {
                $save_key = 0;
                $save_k = 0;
                $save_tone = 0;

                if ($spliced_matrix[$key][$k]['tone'] == $spliced_matrix[$key][$k+1]['tone'] &&
                  $spliced_matrix[$key][$k+2]['tone'] == $spliced_matrix[$key][$k+3]['tone'] ||
                  $spliced_matrix[$key][$k+1]['tone'] == $spliced_matrix[$key][$k+2]['tone'] &&
                  $spliced_matrix[$key][$k+3]['tone'] == $spliced_matrix[$key][$k+4]['tone']) {
                  //dpm('WAVELIMIT');
                  $spliced_matrix[$key][$k]['wave_limit'] == 1;
                }

                if (isset($scale_increments[$i]) /*&& $wave == $increment*/) {
                  if (!isset($first) && isset($spliced_matrix[$key][$k]['wave_limit'])) {
                    if (isset($scale_increments)) {
                      foreach ($scale_increments as $i => $increment) {
                        if (isset($scale_increments[$i]) && isset($spliced_matrix[$key][$k]['wave'][$i]) && $spliced_matrix[$key][$k]['wave'][$i] == $increment
                        && !isset($first)) {

                          $wave_height == 1;
                          $wavecount == 1;

                          ////dpm($increment);
                          $explode = explode(':', $increment);
                          $jump = $explode[1];
                          $direction = $explode[2];

                          $spliced_matrix[$key][$k][$item['tone']][$direction][$jump]['wave_height'] = $wave_height;
                          $wave_heights[$item['tone']][$direction][$jump]['wave_height'] = $wave_height;
                          $save_key = $key;
                          $save_k = $k;
                          $save_tone = $item['tone'];
                          $midwave = 1;
                          $first = 'first';
                          //('first');
                        }
                      }
                    }
                  }
                  elseif (isset($first) && isset($spliced_matrix[$key][$k]['wave_limit'])) {
                    if (isset($scale_increments)) {
                      foreach ($scale_increments as $i => $increment) {
                        if (isset($scale_increments[$i]) && isset($spliced_matrix[$key][$k]['wave'][$i]) && $spliced_matrix[$key][$k]['wave'][$i] == $increment) {
                          $wave_height++;

                          ////dpm($increment);
                          $explode = explode(':', $increment);
                          $jump = $explode[1];
                          $direction = $explode[2];

                          $spliced_matrix[$key][$k][$item['tone']][$direction][$jump]['wave_height'] = $wave_height;
                          $spliced_matrix[$save_key][$save_k][$save_tone][$direction][$jump]['wave_height'] =
                            $wave_height;
                          $wave_heights[$item['tone']][$direction][$jump]['wave_height'] = $wave_height;
                          $wavecount++;
                          $wave_height = 1;
                          $spliced_matrix[$key][$k][$item['tone']][$direction][$jump]['wave_height'] = 1;
                          $wave_heights[$item['tone']][$direction][$jump]['wave_height'] = 1;
                          $save_key = $key;
                          $save_k = $k;
                          $save_tone = $item['tone'];
                          $midwave = 1;
                          //dpm('beginningend');
                        }
                      }
                    }
                  }
                  elseif (!isset($spliced_matrix[$key][$k]['wave_limit'])) {
                    //dpm(' middddddddle ');
                    $wave_height++;
                    $midwave = 0;
                  }
                }
              }
            }
          }
        }

        //dpm($wave_heights);

        if (!empty($spliced_matrix)) {
          foreach ($spliced_matrix as $key => $spliced_row) {
            foreach ($spliced_row as $k => $item) {
              if (isset($spliced_matrix[$key][$k]['wave_limit_processed']) && $spliced_matrix[$key][$k]['wave_limit_processed'] == 1 && $spliced_matrix[$key][$k]['column'] == 1) {
                if (isset($scale_increments)) {
                  foreach ($scale_increments as $i => $increment) {
                    ////dpm($increment);
                    $explode = explode(':', $increment);
                    $jump = $explode[1];
                    $direction = $explode[2];
                    $scale_direction = $explode[3];
                    $color = $explode[4];
                    //$colors = jellomatrix_get_colors();
                    $cad= 0;

                    if (isset($spliced_matrix[$key][$k]['wave'])/* odd waveform && $jump %2 == 0*/) {

                      foreach ($spliced_matrix[$key][$k]['wave'] as $w => $wave) {
                        if (isset($scale_increments[$i]) && $wave == $increment) {


                          if ($direction == 'down' && !empty($spliced_matrix[$key][$k][$item['tone']][$direction][$jump]['wave_height'])) {
                            $wave_height = $spliced_matrix[$key][$k][$item['tone']][$direction][$jump]['wave_height'];
                          }
                          if ($direction == 'up' && !empty($spliced_matrix[$key][$k][$item['tone']][$direction][$jump]['wave_height'])) {
                            $wave_height = $spliced_matrix[$key][$k][$item['tone']][$direction][$jump]['wave_height'];
                          }
                          //dpm($wave_height);


                          if ($direction == 'down' && $cad== 0) {
                            if ($item['pole_shift'] == '1') {
                              $current_position_key = $key;
                              $current_position_k = $k + 1;
                              $spliced_matrix[$key][$k]['yellow'] = 'red';
                              $spliced_matrix[$key][$k]['rhythm'][$jump] = $jump;

                              $spliced_matrix[$key][$k]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                              $spliced_matrix[$key][$k + 1]['yellow'] = $color;
                              $spliced_matrix[$key][$k + 1]['color_text'] = $color;
                              $spliced_matrix[$key][$k + 1]['rhythm'][$jump] = $jump;

                              $spliced_matrix[$key][$k + 1]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                              $spliced_matrix_reversed[$key][$k]['yellow'] = 'red';
                              $spliced_matrix_reversed[$key][$k]['rhythm'][$jump] = $jump;

                              $spliced_matrix_reversed[$key][$k]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                              $spliced_matrix_reversed[$key][$k + 1]['yellow'] = $color;
                              $spliced_matrix_reversed[$key][$k + 1]['color_text'] = $color;
                              $spliced_matrix_reversed[$key][$k + 1]['rhythm'][$jump] = $jump;

                              $spliced_matrix_reversed[$key][$k + 1]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));
                              $cad++;
                            }
                            if ($item['pole_shift'] == '2') {
                              $current_position_key = $key;
                              $current_position_k = $k;
                              $spliced_matrix[$key][$k]['yellow'] = 'red';
                              $spliced_matrix[$key][$k]['rhythm'][$jump] = $jump;

                              $spliced_matrix[$key][$k]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                              $spliced_matrix_reversed[$key][$k]['yellow'] = 'red';
                              $spliced_matrix_reversed[$key][$k]['rhythm'][$jump] = $jump;

                              $spliced_matrix_reversed[$key][$k]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                            }
                            if (isset($current_position_k) && isset($current_position_key)) {
                              if ($scale_direction == 'forward') {
                                $til = $tone * 5;
                                $z = 1;
                                while ($z <= $til) {
                                  if (isset($spliced_matrix[$current_position_key][$current_position_k + $jump]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] != $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                                    if ($direction == 'down' && isset($spliced_matrix[$current_position_key + 1][$current_position_k + $jump])) {
                                      $current_position_key = $current_position_key + 1;
                                      $current_position_k = $current_position_k + $jump;
                                      if (isset($current_position_k) && isset($current_position_key)) {
                                        $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $cad++;
                                      }
                                    } elseif ($direction == 'up' && isset($spliced_matrix[$current_position_key - 1][$current_position_k + $jump])) {
                                      $current_position_key = $current_position_key - 1;
                                      $current_position_k = $current_position_k + $jump;
                                      if (isset($current_position_k) && isset($current_position_key)) {
                                        $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $cad++;
                                      }
                                    } else {
                                      break;
                                    }
                                  } elseif (isset($spliced_matrix[$current_position_key][$current_position_k + 1]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] == $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                                    $current_position_k = $current_position_k + 1;
                                    $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                    $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                    $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                    $cad++;
                                    if ($direction == 'down') {
                                      $direction = 'up';
                                    } else {
                                      $direction = 'down';
                                    }
                                  } else {
                                    break;
                                  }
                                  $z++;
                                }
                              }
                              if ($scale_direction == 'reversed') {
                                $til = $tone * 5;
                                $z = 1;
                                while ($z <= $til) {
                                  if (isset($spliced_matrix[$current_position_key][$current_position_k + $jump]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] != $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                                    if ($direction == 'down' && isset($spliced_matrix[$current_position_key + 1][$current_position_k + $jump])) {
                                      $current_position_key = $current_position_key + 1;
                                      $current_position_k = $current_position_k + $jump;
                                      if (isset($current_position_k) && isset($current_position_key)) {
                                        $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $cad++;
                                      }
                                    } elseif ($direction == 'up' && isset($spliced_matrix[$current_position_key - 1][$current_position_k + $jump])) {
                                      $current_position_key = $current_position_key - 1;
                                      $current_position_k = $current_position_k + $jump;
                                      if (isset($current_position_k) && isset($current_position_key)) {
                                        $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $cad++;
                                      }
                                    } else {
                                      break;
                                    }
                                  } elseif (isset($spliced_matrix[$current_position_key][$current_position_k + 1]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] == $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                                    $current_position_k = $current_position_k + 1;
                                    $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                    $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                    $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                    $cad++;
                                    if ($direction == 'down') {
                                      $direction = 'up';
                                    } else {
                                      $direction = 'down';
                                    }
                                  } else {
                                    break;
                                  }
                                  $z++;
                                }
                              }
                            }
                            $cad++;
                          }
                          unset($current_position_k);
                          unset($current_position_key);
                          if ($direction == 'up' && $cad== 0) {
                            if ($item['pole_shift'] == '1') {
                              $current_position_key = $key;
                              $current_position_k = $k + 1;
                              $spliced_matrix[$key][$k]['yellow'] = 'red';
                              $spliced_matrix[$key][$k]['rhythm'][$jump] = $jump;

                              $spliced_matrix[$key][$k]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                              $spliced_matrix[$key][$k + 1]['yellow'] = $color;
                              $spliced_matrix[$key][$k + 1]['color_text'] = $color;
                              $spliced_matrix[$key][$k + 1]['rhythm'][$jump] = $jump;

                              $spliced_matrix[$key][$k + 1]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                              $spliced_matrix_reversed[$key][$k]['yellow'] = 'red';
                              $spliced_matrix_reversed[$key][$k]['rhythm'][$jump] = $jump;

                              $spliced_matrix_reversed[$key][$k]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                              $spliced_matrix_reversed[$key][$k + 1]['yellow'] = $color;
                              $spliced_matrix_reversed[$key][$k + 1]['color_text'] = $color;
                              $spliced_matrix_reversed[$key][$k + 1]['rhythm'][$jump] = $jump;

                              $spliced_matrix_reversed[$key][$k + 1]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));
                              $cad++;
                            }
                            if ($item['pole_shift'] == '2') {
                              $current_position_key = $key;
                              $current_position_k = $k;
                              $spliced_matrix[$key][$k]['yellow'] = 'red';
                              $spliced_matrix[$key][$k]['rhythm'][$jump] = $jump;

                              $spliced_matrix[$key][$k]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));

                              $spliced_matrix_reversed[$key][$k]['yellow'] = 'red';
                              $spliced_matrix_reversed[$key][$k]['rhythm'][$jump] = $jump;

                              $spliced_matrix_reversed[$key][$k]['wavelength_even'][$jump] = 1 + ($jump * ($wave_height - 1));


                            }
                            if (isset($current_position_k) && isset($current_position_key)) {
                              if ($scale_direction == 'forward') {
                                $til = $tone * 5;
                                $z = 1;
                                while ($z <= $til) {
                                  if (isset($spliced_matrix[$current_position_key][$current_position_k + $jump]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] != $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                                    if ($direction == 'down' && isset($spliced_matrix[$current_position_key + 1][$current_position_k + $jump])) {
                                      $current_position_key = $current_position_key + 1;
                                      $current_position_k = $current_position_k + $jump;
                                      if (isset($current_position_k) && isset($current_position_key)) {
                                        $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $cad++;
                                      }
                                    } elseif ($direction == 'up' && isset($spliced_matrix[$current_position_key - 1][$current_position_k + $jump])) {
                                      $current_position_key = $current_position_key - 1;
                                      $current_position_k = $current_position_k + $jump;
                                      if (isset($current_position_k) && isset($current_position_key)) {
                                        $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $cad++;
                                      }
                                    } else {
                                      break;
                                    }
                                  } elseif (isset($spliced_matrix[$current_position_key][$current_position_k + 1]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] == $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                                    $current_position_k = $current_position_k + 1;
                                    $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                    $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                    $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                    $cad++;
                                    if ($direction == 'down') {
                                      $direction = 'up';
                                    } else {
                                      $direction = 'down';
                                    }
                                  } else {
                                    break;
                                  }
                                  $z++;
                                }
                              }
                              if ($scale_direction == 'reversed') {
                                $til = $tone * 5;
                                $z = 1;
                                while ($z <= $til) {
                                  if (isset($spliced_matrix[$current_position_key][$current_position_k + $jump]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] != $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                                    if ($direction == 'down' && isset($spliced_matrix[$current_position_key + 1][$current_position_k + $jump])) {
                                      $current_position_key = $current_position_key + 1;
                                      $current_position_k = $current_position_k + $jump;
                                      if (isset($current_position_k) && isset($current_position_key)) {
                                        $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $cad++;
                                      }
                                    } elseif ($direction == 'up' && isset($spliced_matrix[$current_position_key - 1][$current_position_k + $jump])) {
                                      $current_position_key = $current_position_key - 1;
                                      $current_position_k = $current_position_k + $jump;
                                      if (isset($current_position_k) && isset($current_position_key)) {
                                        $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                        $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                        $cad++;
                                      }
                                    } else {
                                      break;
                                    }
                                  } elseif (isset($spliced_matrix[$current_position_key][$current_position_k + 1]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] == $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                                    $current_position_k = $current_position_k + 1;
                                    $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                    $spliced_matrix[$current_position_key][$current_position_k]['color_text'] = $color;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['color_text'] = $color;
                                    $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                    $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                    $cad++;
                                    if ($direction == 'down') {
                                      $direction = 'up';
                                    } else {
                                      $direction = 'down';
                                    }
                                  } else {
                                    break;
                                  }
                                  $z++;
                                }
                              }
                            }
                            $cad++; // TODO
                          }
                        }
                      }
                    }
                    //unset($jump);
                    //unset($direction);
                    //unset($scale_direction);
                    //unset($colors);
                    //unset($c);
                    //unset($explode);
                    //unset($current_position_key);
                    //unset($current_position_k);
                  }
                }
              }
            }
          }
        }

        foreach ($spliced_matrix as $key => $spliced_row) {
          foreach ($spliced_row as $k => $item) {
            if (isset($spliced_matrix[$key][$k]['wavelength_even'])) {
              foreach ($spliced_matrix[$key][$k]['wavelength_even'] as $jump => $value) {
                $wave_interactions[$spliced_matrix[$key][$k]['tone']][$jump]['wavelength'] = $value;
              }
            }
            if (isset($spliced_matrix[$key][$k]['wavelength_odd'])) {
              foreach ($spliced_matrix[$key][$k]['wavelength_odd'] as $jump => $value) {
                $wave_interactions[$spliced_matrix[$key][$k]['tone']][$jump]['wavelength'] = $value;
              }
            }
          }
        }

        //dpm($wave_interactions);

        /*if (isset($wave_interactions)) {

          $phi = 1 / 1.618;
          $wavelength_calculation = '<div class="endtable"></div><div class="begintext"><div class="wavelength-calculation"><p>';

          $wavelengths = array();
          foreach ($wave_interactions as $tone => $jumps) {
            //dpm($tone);
            //dpm($jumps);
            $ucount = 0;
            $wavelengths_added = 0;
            $wavelengths_mult = 1;
            foreach ($jumps as $jump => $wavelength) {
              if ($jump % 2 == 0) {
                $ucount++;
                $wavelength_old = $wavelengths_added;
                $wavelengths_added = $wavelength_old + abs($wavelength['wavelength']);
                $wavelength_old = $ucount*$wavelengths_mult;
                $wavelengths_mult = abs($wavelength_old)*abs($wavelength['wavelength']);
                $wavelengths[$tone][abs($wavelength['wavelength'])]['jump'] = $jump;
              }
            }
            $ocount = 0;
            $owavelengths_added = 0;
            $owavelengths_mult = 1;
            foreach ($jumps as $jump => $owavelength) {
              if ($jump % 2 != 0) {
                $ocount++;
                $owavelength_old = $owavelengths_added;
                $owavelengths_added = $owavelength_old + abs($owavelength['wavelength']*$owavelength['waveheight']);
                $owavelength_old = $ocount*$owavelengths_mult;
                $owavelengths_mult = abs($owavelength_old)*abs($owavelength['wavelength']);
                $owavelengths[$tone][abs($owavelength['wavelength'])]['jump'] = $jump;
              }
            }
            //dpm($wave_height);
            if ($wavelengths_added > 0) {
              $wavelengths_phi = ($phi * $wavelengths_mult) / abs($wavelengths_added);
            }
            if ($owavelengths_added > 0) {
              $owavelengths_phi = ($phi * $owavelengths_mult) / abs($owavelengths_added);
            }

            $wavelength_calculation .= '<strong>EVEN Tone ' . $tone . '</strong> with <strong>' . $ucount . '</strong> wavelength/s counted.';
            if (!empty($wavelengths['tone'])) {
              foreach ($wavelengths['tone'] as $w => $value) {
                $wavelength_calculation .= '<br/>Half-wavelength for <strong>rhythm ' . $value['jump'] . '</strong> is ' . $w . '.';
              }
            }
            $wavelength_calculation .= '</strong><br/>phi(wavelengths multiplied)/wavelengths added = ' . $phi . '*' . abs($wavelengths_mult) . '/' . abs($wavelengths_added) . ' = <strong>' . $wavelengths_phi . '</strong></br>';

            if ($owavelengths_added > 0) {
              $wavelength_calculation .= '<strong>ODD Tone ' . $tone . '</strong> with <strong>' . $ocount . '</strong> wavelength/s counted.';
              foreach ($owavelengths['tone'] as $ow => $ovalue) {
                $wavelength_calculation .= '<br/>Half-wavelength for <strong>rhythm ' . $ovalue['jump'] . '</strong> is ' . $ow . '.';
              }
              $wavelength_calculation .= '</strong><br/>phi(wavelengths multiplied)/wavelengths added = ' . $phi . '*' . abs($owavelengths_mult) . '/' . abs($owavelengths_added) . ' = <strong>' . $owavelengths_phi . '</strong></br>';
            }
            // Retrieve an array which contains the path pieces.
            $current_path = \Drupal::service('path.current')->getPath();
            $path_args = explode('/', $current_path);
            if ($path_args[3] == 20 && $path_args[2] == 13) {
              $wavelengths_phi = (.615 * $wavelengths_mult) / abs($wavelengths_added);
              $wavelength_calculation .= '<strong>VENUS Calculation</strong> with <strong>' . $ucount . '</strong> wavelength/s counted.';
              foreach ($wavelengths[$tone] as $w => $value) {
                $wavelength_calculation .= '<br/>Half-wavelength for <strong>rhythm ' . $value['jump'] . '</strong> is ' . $w. '.';
              }
              $wavelength_calculation .= '</strong><br/>venus ratio = orbit of venus / orbit of earth = .615<br/> venus ratio(wavelengths multiplied)/wavelengths added = .615*' . abs($wavelengths_mult) . '/' . abs($wavelengths_added) . ' = <strong>' . $wavelengths_phi . '</strong></br>';
            }
          }
          $wavelength_calculation .= '</p></div></div>';
        }*/
      //}
    //}


    if (isset($no_scales)) {
    } else {

      if (!empty($spliced_matrix)) {
        $wave_detection['spliced_matrix'] = $spliced_matrix;
      }
      if (!empty($spliced_matrix_reversed)) {
        $wave_detection['spliced_matrix_reversed'] = $spliced_matrix_reversed;
      }
      if (!empty($wavelength_calculation)) {
        //$wave_detection['wavelength_calculation'] = $wavelength_calculation;
      }
      if (!empty($scale_increments)) {
        $wave_detection['scale_increments'] = $scale_increments;
      }
      //unset($spliced_matrix_reversed);
      //unset($spliced_matrix);
      //unset($wavelength_calculation);
      //unset($scale_increments);
      if (!empty($wave_detection)) {
        return $wave_detection;
      }
    }
    unset($jump);
    unset($direction);
    unset($scale_direction);
//    unset($colors);
//    unset($c);
//    unset($count);
//    unset($fount);
    unset($z);
    unset($j);
    unset($cad);
    unset($explode);
    unset($current_position_key);
    unset($current_position_k);
    unset($spliced_matrix_reversed);
    unset($spliced_matrix);
    unset($wavelength_calculation);
    unset($scale_increments);
   
  }
}

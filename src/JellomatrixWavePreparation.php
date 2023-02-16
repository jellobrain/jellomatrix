<?php

namespace Drupal\jellomatrix;

/**
 * Description of JellomatrixWavePreparation
 *
 * @author eleven11
 */
class JellomatrixWavePreparation {
  
  /**
   * Returns the Wave Preparation.
   * name: jellomatrix_wave_preparation
   * @param $prime_matrix
   * @param $tone
   * @param $interval
   * @param $spliced_matrix
   * @return = array()
   *
   **/
  public function getWavePreparation($prime_matrix, $tone, $interval, $spliced_matrix) {
    
    $prime_series = array();

    $prime_series_calculator = (($tone + 1) / 2);
    for ($t = 0; $t < $tone; $t++) {
      if ($t == 0) {
        $prime_series[$t] = 1;
        $next = 1;
      } elseif (isset($next) && $next >= 1) {
        $progression = $next + $prime_series_calculator;
        if ($progression > $tone) {
          $progression = $progression - $tone;
        }
        $prime_series[$t] = $progression;
        $next = $progression;
      }
    }

    // Interesting to note that this can be taken away as it does not seem to matter.
    // Except on certain ones which it does matter one way or another on.
    $prime_series_reversed = array_reverse($prime_series);

    foreach ($spliced_matrix as $key => $spliced_row) {
      $fount = 1;
      foreach ($spliced_row as $k => $item) {
        if (isset($spliced_matrix[$key][$k + 1]['tone'])) {
          if ($spliced_matrix[$key][$k + 1]['tone'] == $spliced_matrix[$key][$k]['tone']) {
            $spliced_matrix[$key][$k]['wave_limit'] = 'active';
            $spliced_matrix[$key][$k + 1]['pole_shift'] = '2';
            $spliced_matrix[$key][$k]['pole_shift'] = '1';
            if ($fount == 1) {
              $spliced_matrix[$key][$k]['first'] = 1;
            }
            $fount++;
          }
          else {
            $return = (2*$tone) - 1;
            if (isset($spliced_matrix[$key][$return]['tone']) && $spliced_matrix[$key][$return]['tone'] == $spliced_matrix[$key][$k]['tone'] && $k == 1 && $fount == 1) {
              $spliced_matrix[$key][$k]['wave_limit'] = 'active';
              $spliced_matrix[$key][$k]['pole_shift'] = '2';
              if ($fount == 1) {
                $spliced_matrix[$key][$k]['first'] = 1;
              }
              $fount++;
            }
          }

          foreach ($prime_series as $position => $note) {
            if ($item['tone'] == $note) {
              // We don't want to start at zero.
              $spliced_matrix[$key][$k]['scale_position'] = $position + 1;
            }
          }
        }
        if (isset($spliced_matrix[$key][$k - 1]['tone'])) {
          if ($spliced_matrix[$key][$k - 1]['tone'] == $spliced_matrix[$key][$k]['tone']) {
            $fount++;
            $spliced_matrix[$key][$k]['wave_limit'] = 'active';
            $spliced_matrix[$key][$k - 1]['pole_shift'] = '1';
            $spliced_matrix[$key][$k]['pole_shift'] = '2';
          }
          foreach ($prime_series as $position => $note) {
            if ($item['tone'] == $note) {
              // We don't want to start at zero.
              $spliced_matrix[$key][$k]['scale_position'] = $position + 1;
            }
          }
        }
        if (!isset($spliced_matrix[$key][$k + 1]['tone'])) {
          $bridge_kplus = ($k + 1) - (2 * $tone);
          if ($spliced_matrix[$key][$bridge_kplus]['tone'] == $spliced_matrix[$key][$k]['tone']) {
            $fount++;
            $spliced_matrix[$key][$k]['wave_limit'] = 'active';
            $spliced_matrix[$key][$bridge_kplus]['pole_shift'] = '2';
            $spliced_matrix[$key][$k]['pole_shift'] = '1';
          }
          foreach ($prime_series as $position => $note) {
            if ($item['tone'] == $note) {
              // We don't want to start at zero.
              $spliced_matrix[$key][$k]['scale_position'] = $position + 1;
            }
          }
        }
        if (!isset($spliced_matrix[$key][$k - 1]['tone'])) {
          $bridge_kminus = ($k - 1) + (2 * $tone);
          if ($spliced_matrix[$key][$bridge_kminus]['tone'] == $spliced_matrix[$key][$k]['tone']) {
            $fount++;
            $spliced_matrix[$key][$k]['wave_limit'] = 'active';
            $spliced_matrix[$key][$bridge_kminus]['pole_shift'] = '1';
            $spliced_matrix[$key][$k]['pole_shift'] = '2';
          }
          foreach ($prime_series as $position => $note) {
            if ($item['tone'] == $note) {
              // We don't want to start at zero.
              $spliced_matrix[$key][$k]['scale_position'] = $position + 1;
            }
          }
        }
      }
      unset($fount);
    }

    foreach ($spliced_matrix as $key => $spliced_row) {
      foreach ($spliced_row as $k => $item) {
        if (!isset($item['scale_position'])) {
          $no_scales = 'no scales';
        }
        if (isset($item['pole_shift']) && !isset($no_scales)) {
          if ($item['pole_shift'] == '1') {
            $spliced_matrix[$key][$k]['scale'] = $spliced_matrix[$key][$k]['scale_position'];
            $spliced_matrix[$key][$k]['phase_color'] = 'red';
            if (isset($spliced_matrix[$key][$k]['scale']) && isset($spliced_matrix[$key][$k]['scale_position'])) {
              if (isset($spliced_matrix[$key + 1][$k - 2]) && isset($spliced_matrix[$key][$k]['scale'])) {
                if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix[$key + 1][$k - 2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] + 1) {
                    $spliced_matrix[$key + 1][$k - 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$key + 1][$k - 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                } else {
                  if ($spliced_matrix[$key + 1][$k - 2]['scale_position'] == 1) {
                    $spliced_matrix[$key + 1][$k - 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$key + 1][$k - 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              if (isset($spliced_matrix[$key - 1][$k - 2]) && isset($spliced_matrix[$key][$k]['scale'])) {
                if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix[$key - 1][$k - 2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] + 1) {
                    $spliced_matrix[$key - 1][$k - 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$key - 1][$k - 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                } else {
                  if ($spliced_matrix[$key - 1][$k - 2]['scale_position'] == 1) {
                    $spliced_matrix[$key - 1][$k - 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$key - 1][$k - 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              if (!isset($spliced_matrix[$key + 1][$k - 2]) && isset($spliced_matrix[$key][$k]['scale']) && isset($spliced_matrix[$key][$k]['scale_position'])) {
                unset($new_key);
                unset($bridge_kplus);
                if (!isset($spliced_matrix[$key + 1])) {
                  $new_key = ($key + 1) - $interval;
                  if (!isset($spliced_matrix[$new_key][$k - 2])) {
                    $bridge_kplus = (2 * $tone) + ($k - 2);
                  }
                }
                if (isset($spliced_matrix[$key + 1])) {
                  if (!isset($spliced_matrix[$key + 1][$k - 2])) {
                    $bridge_kplus = (2 * $tone) + ($k - 2);
                  }
                }
                if (isset($new_key) && !isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                    if ($spliced_matrix[$new_key][$k - 2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix[$new_key][$k - 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$k - 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix[$new_key][$k - 2]['scale_position'] == 1) {
                      $spliced_matrix[$new_key][$k - 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$k - 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                if (isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                    if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == 1) {
                      $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                if (!isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                    if ($spliced_matrix[$key + 1][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix[$key + 1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$key + 1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix[$key + 1][$bridge_kplus]['scale_position'] == 1) {
                      $spliced_matrix[$key + 1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$key + 1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (!isset($spliced_matrix[$key - 1][$k - 2]) && isset($spliced_matrix[$key][$k]['scale'])) {
                unset($new_key);
                unset($bridge_kplus);
                if (!isset($spliced_matrix[$key - 1])) {
                  $new_key = ($key - 1) + $interval;
                  if (!isset($spliced_matrix[$new_key][$k - 2])) {
                    $bridge_kplus = (2 * $tone) + ($k - 2);
                  }
                }
                if (isset($spliced_matrix[$key - 1])) {
                  if (!isset($spliced_matrix[$key - 1][$k - 2])) {
                    $bridge_kplus = (2 * $tone) + ($k - 2);
                  }
                }
                if (isset($new_key) && !isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] < $tone && isset($spliced_matrix[$new_key][$k - 2]['scale_position'])) {
                    if ($spliced_matrix[$new_key][$k - 2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix[$new_key][$k - 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$k - 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix[$new_key][$k - 2]['scale_position'] == 1) {
                      $spliced_matrix[$new_key][$k - 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$k - 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                if (isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] < $tone && isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  } else {
                    if (isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position']) && $spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == 1) {
                      $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                if (!isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] < $tone && isset($spliced_matrix[$key - 1][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix[$key - 1][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix[$key - 1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$key - 1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix[$key - 1][$bridge_kplus]['scale_position'] == 1) {
                      $spliced_matrix[$key - 1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$key - 1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
              }
            }
          }
          if ($item['pole_shift'] == '2' && isset($spliced_matrix[$key][$k]['scale_position'])) {
            $spliced_matrix[$key][$k]['scale'] = $spliced_matrix[$key][$k]['scale_position'];
            $spliced_matrix[$key][$k]['phase_color'] = 'green';
            if (isset($spliced_matrix[$key][$k]['scale'])) {
              if (isset($spliced_matrix[$key + 1][$k + 2]) && isset($spliced_matrix[$key][$k]['scale'])) {
                if ($spliced_matrix[$key][$k]['scale_position'] > 1) {
                  if ($spliced_matrix[$key + 1][$k + 2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] - 1) {
                    $spliced_matrix[$key + 1][$k + 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$key + 1][$k + 2]['phase_color'])) {
                      $spliced_matrix[$key + 1][$k + 2]['phase_color'] = 'purple';
                    } else {
                      $spliced_matrix[$key + 1][$k + 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                } else {
                  if ($spliced_matrix[$key + 1][$k + 2]['scale_position'] == $tone) {
                    $spliced_matrix[$key + 1][$k + 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$key + 1][$k + 2]['phase_color'])) {
                      $spliced_matrix[$key + 1][$k + 2]['phase_color'] = 'purple';
                    } else {

                      $spliced_matrix[$key + 1][$k + 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (isset($spliced_matrix[$key - 1][$k + 2]) && isset($spliced_matrix[$key][$k]['scale'])) {
                if ($spliced_matrix[$key][$k]['scale_position'] > 1) {
                  if ($spliced_matrix[$key - 1][$k + 2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] - 1) {
                    $spliced_matrix[$key - 1][$k + 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$key - 1][$k + 2]['phase_color'])) {
                      $spliced_matrix[$key - 1][$k + 2]['phase_color'] = 'purple';
                    } else {
                      $spliced_matrix[$key - 1][$k + 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                } else {
                  if ($spliced_matrix[$key - 1][$k + 2]['scale_position'] == $tone) {
                    $spliced_matrix[$key - 1][$k + 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$key - 1][$k + 2]['phase_color'])) {
                      $spliced_matrix[$key - 1][$k + 2]['phase_color'] = 'purple';
                    } else {
                      $spliced_matrix[$key - 1][$k + 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (!isset($spliced_matrix[$key + 1][$k + 2]) && isset($spliced_matrix[$key][$k]['scale'])) {
                unset($new_key);
                unset($bridge_kplus);
                if (!isset($spliced_matrix[$key + 1])) {
                  $new_key = ($key + 1) - $interval;
                  if (!isset($spliced_matrix[$new_key][$k + 2])) {
                    $bridge_kplus = ($k + 2) - (2 * $tone);
                  }
                }
                if (isset($spliced_matrix[$key + 1])) {
                  if (!isset($spliced_matrix[$key + 1][$k + 2])) {
                    $bridge_kplus = ($k + 2) - (2 * $tone);
                  }
                }
                if (isset($bridge_kplus)) {
                  if ($bridge_kplus > (2 * $tone)) {
                    $bridge_kplus = (3 * $tone) - $bridge_kplus;
                  }
                  if ($bridge_kplus < 1) {
                    $bridge_kplus = $bridge_kplus + $tone;
                  }
                }
                if (isset($new_key) && !isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$new_key][$k + 2]['scale_position'])) {
                    if ($spliced_matrix[$new_key][$k + 2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix[$new_key][$k + 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$new_key][$k + 2]['phase_color'])) {
                        $spliced_matrix[$new_key][$k + 2]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$new_key][$k + 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix[$new_key][$k + 2]['scale_position'] == $tone) {
                      $spliced_matrix[$new_key][$k + 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      $spliced_matrix[$new_key][$k + 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                if (isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$new_key][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $tone) {
                      $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$new_key][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
                if (!isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$key + 1][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix[$key + 1][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix[$key + 1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$key + 1][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix[$key + 1][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$key + 1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix[$key + 1][$bridge_kplus]['scale_position'] == $tone) {
                      $spliced_matrix[$key + 1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$key + 1][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix[$key + 1][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$key + 1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
              }
              if (!isset($spliced_matrix[$key - 1][$k + 2]) && isset($spliced_matrix[$key][$k]['scale'])) {
                unset($new_key);
                unset($bridge_kplus);
                if (!isset($spliced_matrix[$key - 1])) {
                  $new_key = ($key - 1) + $interval;
                  if (!isset($spliced_matrix[$new_key][$k + 2])) {
                    $bridge_kplus = ($k + 2) - (2 * $tone);
                  }
                }
                if (isset($spliced_matrix[$key - 1])) {
                  if (!isset($spliced_matrix[$key - 1][$k + 2])) {
                    $bridge_kplus = ($k + 2) - (2 * $tone);
                  }
                }
                if (isset($bridge_kplus)) {
                  if ($bridge_kplus > (2 * $tone)) {
                    $bridge_kplus = (3 * $tone) - $bridge_kplus;
                  }
                  if ($bridge_kplus < 1) {
                    $bridge_kplus = $bridge_kplus + $tone;
                  }
                }
                if (isset($new_key) && !isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$new_key][$k + 2]['scale_position'])) {
                    if ($spliced_matrix[$new_key][$k + 2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix[$new_key][$k + 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$new_key][$k + 2]['phase_color'])) {
                        $spliced_matrix[$new_key][$k + 2]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$new_key][$k + 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if (isset($bridge_plus) && isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position']) && $spliced_matrix[$new_key][$k + 2]['scale_position'] == $tone) {
                      $spliced_matrix[$new_key][$k + 2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$new_key][$k + 2]['phase_color'])) {
                        $spliced_matrix[$new_key][$k + 2]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$new_key][$k + 2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
                if (isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$new_key][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $tone) {
                      $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$new_key][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
                if (!isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$key - 1][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix[$key - 1][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix[$key - 1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$key - 1][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix[$key - 1][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$key - 1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix[$key - 1][$bridge_kplus]['scale_position'] == $tone) {
                      $spliced_matrix[$key - 1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                      if (isset($spliced_matrix[$key - 1][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix[$key - 1][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix[$key - 1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    // NOW USING THE REVERSE PRIME SERIES.
    $spliced_matrix_reversed = $spliced_matrix;

    foreach ($spliced_matrix_reversed as $key => $spliced_row) {
      $dount = 1;
      foreach ($spliced_row as $k => $item) {
        if (isset($spliced_matrix_reversed[$key][$k + 1]['tone'])) {
          if ($spliced_matrix_reversed[$key][$k + 1]['tone'] == $spliced_matrix_reversed[$key][$k]['tone']) {
            $spliced_matrix_reversed[$key][$k]['wave_limit'] = 'active';
            $spliced_matrix_reversed[$key][$k + 1]['pole_shift'] = '2';
            $spliced_matrix_reversed[$key][$k]['pole_shift'] = '1';
            if ($dount == 1) {
              $spliced_matrix[$key][$k]['first'] = 1;
            }
            $dount++;
          }
          foreach ($prime_series_reversed as $position => $note) {
            if ($item['tone'] == $note) {
              // We don't want to start at zero.
              $spliced_matrix_reversed[$key][$k]['scale_position'] = $position + 1;
            }
          }
        }
        if (isset($spliced_matrix_reversed[$key][$k - 1]['tone'])) {
          if ($spliced_matrix_reversed[$key][$k - 1]['tone'] == $spliced_matrix_reversed[$key][$k]['tone']) {
            $dount++;
            $spliced_matrix_reversed[$key][$k]['wave_limit'] = 'active';
            $spliced_matrix_reversed[$key][$k - 1]['pole_shift'] = '1';
            $spliced_matrix_reversed[$key][$k]['pole_shift'] = '2';
          }
          foreach ($prime_series_reversed as $position => $note) {
            if ($item['tone'] == $note) {
              // We don't want to start at zero.
              $spliced_matrix_reversed[$key][$k]['scale_position'] = $position + 1;
            }
          }
        }
        if (!isset($spliced_matrix_reversed[$key][$k + 1]['tone'])) {
          $bridge_kplus = ($k + 1) - (2 * $tone);
          if ($spliced_matrix_reversed[$key][$bridge_kplus]['tone'] == $spliced_matrix_reversed[$key][$k]['tone']) {
            $dount++;
            $spliced_matrix_reversed[$key][$k]['wave_limit'] = 'active';
            $spliced_matrix_reversed[$key][$bridge_kplus]['pole_shift'] = '2';
            $spliced_matrix_reversed[$key][$k]['pole_shift'] = '1';
          }
          foreach ($prime_series_reversed as $position => $note) {
            if ($item['tone'] == $note) {
              // We don't want to start at zero.
              $spliced_matrix_reversed[$key][$k]['scale_position'] = $position + 1;
            }
          }
        }
        if (!isset($spliced_matrix_reversed[$key][$k - 1]['tone'])) {
          $bridge_kminus = ($k - 1) + (2 * $tone);
          if ($spliced_matrix_reversed[$key][$bridge_kminus]['tone'] == $spliced_matrix_reversed[$key][$k]['tone']) {
            $dount++;
            $spliced_matrix_reversed[$key][$k]['wave_limit'] = 'active';
            $spliced_matrix_reversed[$key][$bridge_kminus]['pole_shift'] = '1';
            $spliced_matrix_reversed[$key][$k]['pole_shift'] = '2';
          }
          foreach ($prime_series_reversed as $position => $note) {
            if ($item['tone'] == $note) {
              // We don't want to start at zero.
              $spliced_matrix_reversed[$key][$k]['scale_position'] = $position + 1;
            }
          }
        }
      }
    }

    foreach ($spliced_matrix_reversed as $key => $spliced_row) {
      $qount = 1;
      foreach ($spliced_row as $k => $item) {
        if (!isset($item['scale_position'])) {
          $no_scales = 'no scales';
        }
        if (isset($item['pole_shift']) && !isset($no_scales)) {
          if ($item['pole_shift'] == '1') {
            $spliced_matrix_reversed[$key][$k]['scale'] = $spliced_matrix_reversed[$key][$k]['scale_position'];
            $spliced_matrix_reversed[$key][$k]['phase_color'] = 'red';
            if (isset($spliced_matrix_reversed[$key][$k]['scale']) && isset($spliced_matrix_reversed[$key][$k]['scale_position'])) {
              if (isset($spliced_matrix_reversed[$key + 1][$k - 2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix_reversed[$key + 1][$k - 2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] + 1) {
                    $spliced_matrix_reversed[$key + 1][$k - 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$key + 1][$k - 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                } else {
                  if ($spliced_matrix_reversed[$key + 1][$k - 2]['scale_position'] == 1) {
                    $spliced_matrix_reversed[$key + 1][$k - 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$key + 1][$k - 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              if (isset($spliced_matrix_reversed[$key - 1][$k - 2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix_reversed[$key - 1][$k - 2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] + 1) {
                    $spliced_matrix_reversed[$key - 1][$k - 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$key - 1][$k - 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                } else {
                  if ($spliced_matrix_reversed[$key - 1][$k - 2]['scale_position'] == 1) {
                    $spliced_matrix_reversed[$key - 1][$k - 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$key - 1][$k - 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              if (!isset($spliced_matrix_reversed[$key + 1][$k - 2]) && isset($spliced_matrix_reversed[$key][$k]['scale']) && isset($spliced_matrix_reversed[$key][$k]['scale_position'])) {
                unset($new_key);
                unset($bridge_kplus);
                if (!isset($spliced_matrix_reversed[$key + 1])) {
                  $new_key = ($key + 1) - $interval;
                  if (!isset($spliced_matrix_reversed[$new_key][$k - 2])) {
                    $bridge_kplus = (2 * $tone) + ($k - 2);
                  }
                }
                if (isset($spliced_matrix_reversed[$key + 1])) {
                  if (!isset($spliced_matrix_reversed[$key + 1][$k - 2])) {
                    $bridge_kplus = (2 * $tone) + ($k - 2);
                  }
                }
                if (isset($new_key) && !isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone && isset($spliced_matrix_reversed[$new_key][$k - 2]['scale_position'])) {
                    if ($spliced_matrix_reversed[$new_key][$k - 2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix_reversed[$new_key][$k - 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$k - 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix_reversed[$new_key][$k - 2]['scale_position'] == 1) {
                      $spliced_matrix_reversed[$new_key][$k - 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$k - 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                if (isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone && isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == 1) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                if (!isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone && isset($spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$key + 1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale_position'] == 1) {
                      $spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$key + 1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (!isset($spliced_matrix_reversed[$key - 1][$k - 2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
                unset($new_key);
                unset($bridge_kplus);
                if (!isset($spliced_matrix_reversed[$key - 1])) {
                  $new_key = ($key - 1) + $interval;
                  if (!isset($spliced_matrix_reversed[$new_key][$k - 2])) {
                    $bridge_kplus = (2 * $tone) + ($k - 2);
                  }
                }
                if (isset($spliced_matrix_reversed[$key - 1])) {
                  if (!isset($spliced_matrix_reversed[$key - 1][$k - 2])) {
                    $bridge_kplus = (2 * $tone) + ($k - 2);
                  }
                }
                if (isset($new_key) && !isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                    if ($spliced_matrix_reversed[$new_key][$k - 2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix_reversed[$new_key][$k - 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$k - 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix_reversed[$new_key][$k - 2]['scale_position'] == 1) {
                      $spliced_matrix_reversed[$new_key][$k - 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$k - 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                if (isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                    if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == 1) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                if (!isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                    if ($spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] + 1) {
                      $spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$key - 1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  } else {
                    if ($spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale_position'] == 1) {
                      $spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$key - 1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
              }
            }
          }
          if ($item['pole_shift'] == '2' && isset($spliced_matrix_reversed[$key][$k]['scale_position'])) {
            $spliced_matrix_reversed[$key][$k]['scale'] = $spliced_matrix_reversed[$key][$k]['scale_position'];
            $spliced_matrix_reversed[$key][$k]['phase_color'] = 'green';
            if (isset($spliced_matrix_reversed[$key][$k]['scale'])) {
              if (isset($spliced_matrix_reversed[$key + 1][$k + 2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1) {
                  if ($spliced_matrix_reversed[$key + 1][$k + 2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] - 1) {
                    $spliced_matrix_reversed[$key + 1][$k + 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$key + 1][$k + 2]['phase_color'])) {
                      $spliced_matrix_reversed[$key + 1][$k + 2]['phase_color'] = 'purple';
                    } else {
                      $spliced_matrix_reversed[$key + 1][$k + 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                } else {
                  if ($spliced_matrix_reversed[$key + 1][$k + 2]['scale_position'] == $tone) {
                    $spliced_matrix_reversed[$key + 1][$k + 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$key + 1][$k + 2]['phase_color'])) {
                      $spliced_matrix_reversed[$key + 1][$k + 2]['phase_color'] = 'purple';
                    } else {

                      $spliced_matrix_reversed[$key + 1][$k + 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (isset($spliced_matrix_reversed[$key - 1][$k + 2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1) {
                  if ($spliced_matrix_reversed[$key - 1][$k + 2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] - 1) {
                    $spliced_matrix_reversed[$key - 1][$k + 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$key - 1][$k + 2]['phase_color'])) {
                      $spliced_matrix_reversed[$key - 1][$k + 2]['phase_color'] = 'purple';
                    } else {
                      $spliced_matrix_reversed[$key - 1][$k + 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                } else {
                  if ($spliced_matrix_reversed[$key - 1][$k + 2]['scale_position'] == $tone) {
                    $spliced_matrix_reversed[$key - 1][$k + 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$key - 1][$k + 2]['phase_color'])) {
                      $spliced_matrix_reversed[$key - 1][$k + 2]['phase_color'] = 'purple';
                    } else {
                      $spliced_matrix_reversed[$key - 1][$k + 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (!isset($spliced_matrix_reversed[$key + 1][$k + 2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
                unset($new_key);
                unset($bridge_kplus);
                if (!isset($spliced_matrix_reversed[$key + 1])) {
                  $new_key = ($key + 1) - $interval;
                  if (!isset($spliced_matrix_reversed[$new_key][$k + 2])) {
                    $bridge_kplus = ($k + 2) - (2 * $tone);
                  }
                }
                if (isset($spliced_matrix_reversed[$key + 1])) {
                  if (!isset($spliced_matrix_reversed[$key + 1][$k + 2])) {
                    $bridge_kplus = ($k + 2) - (2 * $tone);
                  }
                }
                if (isset($bridge_kplus)) {
                  if ($bridge_kplus > (2 * $tone)) {
                    $bridge_kplus = (3 * $tone) - $bridge_kplus;
                  }
                  if ($bridge_kplus < 1) {
                    $bridge_kplus = $bridge_kplus + $tone;
                  }
                }
                if (isset($new_key) && !isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$new_key][$k + 2]['scale_position'])) {
                    if ($spliced_matrix_reversed[$new_key][$k + 2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix_reversed[$new_key][$k + 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$new_key][$k + 2]['phase_color'])) {
                        $spliced_matrix_reversed[$new_key][$k + 2]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$new_key][$k + 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix_reversed[$new_key][$k + 2]['scale_position'] == $tone) {
                      $spliced_matrix_reversed[$new_key][$k + 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      $spliced_matrix_reversed[$new_key][$k + 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                if (isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $tone) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
                if (!isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$key + 1][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix_reversed[$key + 1][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$key + 1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale_position'] == $tone) {
                      $spliced_matrix_reversed[$key + 1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$key + 1][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix_reversed[$key + 1][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$key + 1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
              }
              if (!isset($spliced_matrix_reversed[$key - 1][$k + 2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
                unset($new_key);
                unset($bridge_kplus);
                if (!isset($spliced_matrix_reversed[$key - 1])) {
                  $new_key = ($key - 1) + $interval;
                  if (!isset($spliced_matrix_reversed[$new_key][$k + 2])) {
                    $bridge_kplus = ($k + 2) - (2 * $tone);
                  }
                }
                if (isset($spliced_matrix_reversed[$key - 1])) {
                  if (!isset($spliced_matrix_reversed[$key - 1][$k + 2])) {
                    $bridge_kplus = ($k + 2) - (2 * $tone);
                  }
                }
                if (isset($bridge_kplus)) {
                  if ($bridge_kplus > (2 * $tone)) {
                    $bridge_kplus = (3 * $tone) - $bridge_kplus;
                  }
                  if ($bridge_kplus < 1) {
                    $bridge_kplus = $bridge_kplus + $tone;
                  }
                }
                if (isset($new_key) && !isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$new_key][$k + 2]['scale_position'])) {
                    if ($spliced_matrix_reversed[$new_key][$k + 2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix_reversed[$new_key][$k + 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$new_key][$k + 2]['phase_color'])) {
                        $spliced_matrix_reversed[$new_key][$k + 2]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$new_key][$k + 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix_reversed[$new_key][$k + 2]['scale_position'] == $tone) {
                      $spliced_matrix_reversed[$new_key][$k + 2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$new_key][$k + 2]['phase_color'])) {
                        $spliced_matrix_reversed[$new_key][$k + 2]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$new_key][$k + 2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
                if (isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $tone) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
                if (!isset($new_key) && isset($bridge_kplus)) {
                  if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale_position'])) {
                    if ($spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position'] - 1) {
                      $spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$key - 1][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix_reversed[$key - 1][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$key - 1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  } else {
                    if ($spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale_position'] == $tone) {
                      $spliced_matrix_reversed[$key - 1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                      if (isset($spliced_matrix_reversed[$key - 1][$bridge_kplus]['phase_color'])) {
                        $spliced_matrix_reversed[$key - 1][$bridge_kplus]['phase_color'] = 'purple';
                      } else {
                        $spliced_matrix_reversed[$key - 1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    $hscale = array();
    $h_scale_sum_ratios = array();
    // Horizontal Scale
    $cs = 0;
    $array_count = 0;


    foreach ($prime_matrix as $k => $prime_row) {
      $cs++;
      foreach ($prime_row as $key => $value) {
        if ($cs == 1) {
          //dpm('one');
          $hscale[$array_count] = $prime_matrix[$k][$key]['tone'];
          $array_count++;
          if (!empty($prime_matrix[$k][$key + 1])) {
            if ($prime_matrix[$k][$key]['tone'] - $prime_matrix[$k][$key + 1]['tone'] >= 0) {
              //dpm('ltr');
              $h_scale_sum_ratios['ltr'] = $prime_matrix[$k][$key]['tone'] - $prime_matrix[$k][$key + 1]['tone'];
            }
            else {
              if (!empty($prime_matrix[$k][$tone]['tone'])) {
                //dpm('ltr');
                $h_scale_sum_ratios['ltr'] = $prime_matrix[$k][$key]['tone'] - $prime_matrix[$k][$tone]['tone'];
              }
            }
          }
          if (!empty($prime_matrix[$k][$key - 1])) {
            if ($prime_matrix[$k][$key]['tone'] - $prime_matrix[$k][$key - 1]['tone'] >= 0) {
              //dpm('rtl');
              $h_scale_sum_ratios['rtl'] = $prime_matrix[$k][$key]['tone'] - $prime_matrix[$k][$key - 1]['tone'];
            }
            else {
              if (!empty($prime_matrix[$k][$tone]['tone']) && $prime_matrix[$k][$key]['tone'] - $prime_matrix[$k][$tone]['tone'] >= 0) {
                //dpm('rtl');
                $h_scale_sum_ratios['rtl'] = $prime_matrix[$k][$key]['tone'] - $prime_matrix[$k][$key - 1]['tone'];
              }
            }
          }
        }
      }
    }

    if (isset($h_scale_sum_ratios['rtl']) || isset($h_scale_sum_ratios['ltr'])) {
      $scaled = '<span class="blue-text">HORIZONTAL SCALE [<->] (' . $h_scale_sum_ratios['rtl'] . '/' . $h_scale_sum_ratios['ltr'] . '): ';
      $hscaled = '<span class="blue-text">HORIZONTAL SCALE [<->] (' . $h_scale_sum_ratios['rtl'] . '/' . $h_scale_sum_ratios['ltr'] . '): ';
    }
    if (isset($hscale) && isset($hscaled) &&  !empty($hscale)) {
      foreach ($hscale as $array => $value) {
        $scaled .= $value . ', ';
        $hscaled .= $value . ', ';
      }
      $scaled .= '</span>';
      $hscaled .= '</span>';
    }
    elseif (isset($hscale) && isset($hscaled) &&  empty($hscale)) {
      foreach ($hscale as $array => $value) {
        $scaled = $value . ', ';
        $hscaled = $value . ', ';
      }
      $scaled .= '</span>';
      $hscaled .= '</span>';
    }
    //dpm($hscale);

    // Forward Slash Diagonal
    $fscale = array();
    $f_scale_sum_ratios = array();
    $cs = 0;
    $array_count = 0;
    foreach ($prime_matrix as $k => $prime_row) {
      $cs++;
      foreach ($prime_row as $key => $value) {
        if ($cs == $tone + 1 && $prime_matrix[$k][$key]['tone'] == 1) {
          $fscale[$array_count] = $prime_matrix[$k][$key]['tone'];
          $array_count++;
          for ($i = 1; $i <= $tone; $i++) {
            if (!empty($prime_matrix[$k - $i][$key + $i])) {
              $fscale[$array_count] = $prime_matrix[$k - $i][$key + $i]['tone'];
              $array_count++;
              if (!empty($prime_matrix[$k - $i - 1][$key + $i + 1])) {
                if ($prime_matrix[$k - $i][$key + $i]['tone'] - $prime_matrix[$k - $i - 1][$key + $i + 1]['tone'] >= 0) {
                  $f_scale_sum_ratios['ltr'] = $prime_matrix[$k - $i][$key + $i]['tone'] - $prime_matrix[$k - $i - 1][$key + $i + 1]['tone'];
                }
                elseif (isset($prime_matrix[$k - $i][$key + $i]['tone']) && isset ($prime_matrix[$k - $i - 1][$key + $i + 1]['tone']) && isset($prime_matrix[$k - $i - 1][$key + $i]['tone']) && isset($prime_matrix[$k - $i - 2][$key + $i + 1]['tone'])) {
                  if ($prime_matrix[$k - $i][$key + $i]['tone'] - $prime_matrix[$k - $i - 1][$key + $i + 1]['tone'] < 0 && $prime_matrix[$k - $i - 1][$key + $i]['tone'] - $prime_matrix[$k - $i - 2][$key + $i + 1]['tone'] >= 0) {
                    $f_scale_sum_ratios['ltr'] = $prime_matrix[$k - $i - 1][$key + $i]['tone'] - $prime_matrix[$k - $i - 2][$key + $i + 1]['tone'];
                  }
                }
              }
              if (!empty($prime_matrix[$k - $i + 1][$key + $i - 1])) {
                if ($prime_matrix[$k - $i][$key + $i]['tone'] - $prime_matrix[$k - $i + 1][$key + $i - 1]['tone'] >= 0) {
                  $f_scale_sum_ratios['rtl'] = $prime_matrix[$k - $i][$key + $i]['tone'] - $prime_matrix[$k - $i + 1][$key + $i - 1]['tone'];
                }
              }
            }
          }
        }
      }
    }
    if (isset($f_scale_sum_ratios['rtl']) && isset($f_scale_sum_ratios['ltr'])) {
      $scaled .= '<br><span class="groen-text">FORWARD SLASH DIAGONAL SCALE [/] (' . $f_scale_sum_ratios['rtl'] . '/' . $f_scale_sum_ratios['ltr'] . '): ';
      $fscaled = '<br><span class="groen-text">FORWARD SLASH DIAGONAL SCALE [/] (' . $f_scale_sum_ratios['rtl'] . '/' . $f_scale_sum_ratios['ltr'] . '): ';
    }
    if (isset($fscale) && isset($fscaled) && !empty($fscale)) {
      foreach ($fscale as $array => $value) {
        $scaled .= $value . ', ';
        $fscaled .= $value . ', ';
      }
      $scaled .= '</span>';
      $fscaled .= '</span>';
    }
    elseif (isset($fscale) && isset($fscaled) && empty($fscale)) {
      foreach ($fscale as $array => $value) {
        $scaled = $value . ', ';
        $fscaled = $value . ', ';
      }
      $scaled .= '</span>';
      $fscaled .= '</span>';
    }
    //dpm($fscale);

    // Backward Slash Diagonal
    $bscale = array();
    $b_scale_sum_ratios = array();
    $cs = 0;
    $array_count = 0;
    foreach ($prime_matrix as $k => $prime_row) {
      $cs++;
      foreach ($prime_row as $key => $value) {
        if ($cs == 1 && $prime_matrix[$k][$key]['tone'] == 1) {
          $bscale[$array_count] = $prime_matrix[$k][$key]['tone'];
          $array_count++;
          for ($i = 1; $i <= $tone; $i++) {
            if (!empty($prime_matrix[$k + $i][$key + $i])) {
              $bscale[$array_count] = $prime_matrix[$k + $i][$key + $i]['tone'];
              $array_count++;
              if (!empty($prime_matrix[$k + $i + 1][$key + $i + 1])) {

                if ($prime_matrix[$k + $i][$key + $i]['tone'] - $prime_matrix[$k + $i + 1][$key + $i + 1]['tone'] >= 0) {
                  $b_scale_sum_ratios['ltr'] = $prime_matrix[$k + $i][$key + $i]['tone'] - $prime_matrix[$k + $i + 1][$key + $i + 1]['tone'];
                }
                elseif (isset($prime_matrix[$k + $i][$key + $i]['tone']) && isset($prime_matrix[$k + $i + 1][$key + $i + 1]['tone']) && isset($prime_matrix[$k + $i + 1][$key + $i + 1]['tone']) && isset($prime_matrix[$k + $i + 2][$key + $i + 2]['tone'])) {
                  if ($prime_matrix[$k + $i][$key + $i]['tone'] - $prime_matrix[$k + $i + 1][$key + $i + 1]['tone'] < 0 && $prime_matrix[$k + $i + 1][$key + $i + 1]['tone'] - $prime_matrix[$k + $i + 2][$key + $i + 2]['tone'] >= 0) {
                    $b_scale_sum_ratios['ltr'] = $prime_matrix[$k + $i][$key + $i]['tone'] - $prime_matrix[$k + $i + 1][$key + $i + 1]['tone'];
                  }
                }
              }
              if (!empty($prime_matrix[$k + $i - 1][$key + $i - 1])) {
                if ($prime_matrix[$k + $i][$key + $i]['tone'] - $prime_matrix[$k + $i - 1][$key + $i - 1]['tone'] >= 0) {
                  $b_scale_sum_ratios['rtl'] = $prime_matrix[$k + $i][$key + $i]['tone'] - $prime_matrix[$k + $i - 1][$key + $i - 1]['tone'];
                }
              }
            }
          }
        }
      }
    }
    if (isset($b_scale_sum_ratios['rtl']) && isset($b_scale_sum_ratios['ltr'])) {
      $scaled .= '<br><span class="salmon-text">BACKWARD SLASH DIAGONAL SCALE [\] (' . $b_scale_sum_ratios['rtl'] . '/' . $b_scale_sum_ratios['ltr'] . '): ';
      $bscaled = '<br><span class="salmon-text">BACKWARD SLASH DIAGONAL SCALE [\] (' . $b_scale_sum_ratios['rtl'] . '/' . $b_scale_sum_ratios['ltr'] . '): ';
    }
    if (isset($bscale) && isset($bscaled) &&  !empty($bscale)) {
      foreach ($bscale as $array => $value) {
        $scaled .= $value . ', ';
        $bscaled .= $value . ', ';
      }
      $scaled .= '</span>';
      $bscaled .= '</span>';
    }
    elseif (isset($bscale) && isset($bscaled) &&  empty($bscale)) {
      foreach ($bscale as $array => $value) {
        $scaled = $value . ', ';
        $bscaled = $value . ', ';
      }
      $scaled .= '</span>';
      $bscaled .= '</span>';
    }
    //dpm($bscale);


    $scales = array('h' => $hscale, 'f' => $fscale, 'b' => $bscale);

    //dpm($scales);
    $wave_detection = array();

    if (!empty($scales)) {
      $wave_detection['scales'] = $scales;
    }
    if (!empty($scaled)) {
      $wave_detection['scaled'] = $scaled;
    }
    if (!empty($hscale)) {
      $wave_detection['hscale'] = $hscale;
    }
    if (!empty($hscaled)) {
      $wave_detection['hscaled'] = $hscaled;
    }
    if (!empty($fscale)) {
      $wave_detection['fscale'] = $fscale;
    }
    if (!empty($fscaled)) {
      $wave_detection['fscaled'] = $fscaled;
    }
    if (!empty($bscale)) {
      $wave_detection['bscale'] = $bscale;
    }
    if (!empty($bscaled)) {
      $wave_detection['bscaled'] = $bscaled;
    }

    if (!empty($h_scale_sum_ratios)) {
      $wave_detection['h_scale_sum_ratios'] = $h_scale_sum_ratios;
    }
    if (!empty($f_scale_sum_ratios)) {
      $wave_detection['f_scale_sum_ratios'] = $f_scale_sum_ratios;
    }
    if (!empty($b_scale_sum_ratios)) {
      $wave_detection['b_scale_sum_ratios'] = $b_scale_sum_ratios;
    }
    if (!empty($h_scale_sum_ratios['rtl'])) {
      $h_increment = $h_scale_sum_ratios['rtl'];
      $wave_detection['h_increment'] = $h_increment;
    }
    if (!empty($f_scale_sum_ratios['rtl'])) {
      $f_increment = $f_scale_sum_ratios['rtl'];
      $wave_detection['f_increment'] = $f_increment;
    }
    if (!empty($b_scale_sum_ratios['rtl'])) {
      $b_increment = $b_scale_sum_ratios['rtl'];
      $wave_detection['b_increment'] = $b_increment;
    }
    if (!empty($spliced_matrix)) {
      $wave_detection['spliced_matrix'] = $spliced_matrix;
    }
    if (!empty($spliced_matrix_reversed)) {
      $wave_detection['spliced_matrix_reversed'] = $spliced_matrix_reversed;
    }
    if (!empty($scales)) {
      $wave_detection['scales'] = $scales;
    }

    /*unset($scales);
    unset($scaled);
    unset($hscale);
    unset($hscaled);
    unset($fscale);
    unset($fscaled);
    unset($bscale);
    unset($bscaled);
    unset($spliced_matrix);
    unset($spliced_matrix_reversed);
    unset($h_scale_sum_ratios);
    unset($f_scale_sum_ratios);
    unset($b_scale_sum_ratios);
    unset($h_increment);
    unset($f_increment);
    unset($b_increment);*/

    return $wave_detection;
  }
}

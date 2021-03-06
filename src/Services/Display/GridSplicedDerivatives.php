<?php

namespace Drupal\jellomatrix\Services\Display;

/**
 * Description of GridSplicedDerivatives
 *
 * @author eleven11
 */
class GridSplicedDerivatives {
  
  /**
   * Returns the SplicedDerivatives for the Waves.
   * name: grid_spliced_derivatives
   * @return = string
   *
   **/
  public function getGridSplicedDerivatives($increments, $primes, $tone, $harmonics, $frequency) {
    // Place block output here //
    $output = '';
    
    $lambdoma_map = [];
    $note_assembly = [];
    
    // Now output the differences between different integers.
    $output .= '<div class="endtable begintext"><h2>ODD+EVEN: Derivatives</h2>';
    $output .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios of repeating increments create.<br><div class="endtext"><br></div>';

    foreach($increments as $k=>$increment) {
      if ($k == 'row') {
        $r = '<h3>ODD+EVEN: Original Matrix</h3>';
        foreach ($increment as $ke => $direction) {
          if ($ke == 'forward') {
            $r .= '';
            $r .= '<p></p><div class="endtext"><br></div>';
            $count = 1;
            $r .= '<table class="table"><tr>';
            foreach ($direction as $spliced_row) {
              $r .= '<td class="tdgridltfirst">Row ' . $count . ': </td>';
              foreach ($spliced_row as $key => $item) {
                $test = $spliced_row;
                unset($clink); $clink = '';
                if (is_array($test)) {
                  $a = array_pop($test);
                  $b = array_pop($test);

                  if (is_numeric($a) && ($a) % 2 == 0 && ($b) % 2 == 0) {
                    $clink = 'highlight';
                  }
                  elseif (is_numeric($a) && ($a) % 2 != 0 && ($b) % 2 != 0) {
                    $clink = 'highlight';
                  }
                }
                if (isset($clink) && $clink == 'highlight') {
                  $r .= '<td class="tdgrid highlight">' . $item . '</td>';
                }
                elseif (($item) % 2 == 0) {
                  $r .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                elseif (($item) % 2 != 0) {
                  $r .= '<td class="tdgrid">' . $item . '</td>';
                }
                if ($key == 0) {
                  $zero = $item;
                }
                if ($key == 1) {
                  $one = $item;
                }
                if ($key == 2) {
                  $two = $item;
                }
                if ($key == 3) {
                  $three = $item;
                }
                if ($key == 4) {
                  $four = $item;
                }
                if ($key == 5) {
                  $five = $item;
                }
                if ($key == 6) {
                  $six = $item;
                }
                if ($key == 7) {
                  $seven = $item;
                }
              }

              if (isset($three) && isset($one) && $three == $one) {
                $upper = $one;
              } else {
                unset($upper);
              }
              if (isset($four) && $two == $four) {
                $lower = $two;
              } else {
                unset($lower);
              }

              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $r .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $r .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
                    }
                    $lambdoma_map[$k][$ke][$key][$count]['lower'] = $explode[0];
                    $lambdoma_map[$k][$ke][$key][$count]['upper'] = $explode[1];
                    $lambdoma_map[$k][$ke][$key][$count]['key'] = $explode[2];
                    if ($explode[2] == 'origin') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'black';
                    }
                    if ($explode[2] == 'C') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zcee';
                    }
                    elseif ($explode[2] == 'C#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zceesharp';
                    }
                    elseif ($explode[2] == 'D') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zdee';
                    }
                    elseif ($explode[2] == 'Eb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zeeeflat';
                    }
                    elseif ($explode[2] == 'E') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zeee';
                    }
                    elseif ($explode[2] == 'F') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zeff';
                    }
                    elseif ($explode[2] == 'F#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zeffsharp';
                    }
                    elseif ($explode[2] == 'G') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zgee';
                    }
                    elseif ($explode[2] == 'G#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zgeesharp';
                    }
                    elseif ($explode[2] == 'A') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zay';
                    }
                    elseif ($explode[2] == 'Bb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zbeeflat';
                    }
                    elseif ($explode[2] == 'B') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zbee';
                    }
                    else {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'darkslategray';
                    }
                    if (isset($explode[3])) {
                      $lambdoma_map[$k][$ke][$key][$count]['frequency'] = $explode[3];
                    }
                  }
                }
              }
              $r .= '</tr>';
              $count++;
            }
            $r .= '</table>';
            $r .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $r .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $r .= '</ol><hr>';
            $r .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['r'][] = $note_order;
            }
          }

          if ($ke == 'derivative') {
            unset($note_order);
            $rd = '<h4>First Derivative (Odd/Even)</h4>';
            $count = 1;
            $rd .= '<table class="table"><tr>';
            foreach ($direction as $spliced_row) {
              $rd .= '<td class="tdgridltfirst">Row ' . $count . ': </td>';
              foreach ($spliced_row as $key => $item) {
                $test = $spliced_row;
                unset($clink); $clink = '';
                if (is_array($test)) {
                  $a = array_pop($test);
                  $b = array_pop($test);

                  if (is_numeric($a) && ($a) % 2 == 0 && ($b) % 2 == 0) {
                    $clink = 'highlight';
                  }
                  elseif (is_numeric($a) && ($a) % 2 != 0 && ($b) % 2 != 0) {
                    $clink = 'highlight';
                  }
                }
                if (isset($clink) && $clink == 'highlight') {
                  $rd .= '<td class="tdgrid highlight">' . $item . '</td>';
                }
                elseif (($item) % 2 == 0) {
                  $rd .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                elseif (($item) % 2 != 0) {
                  $rd .= '<td class="tdgrid">' . $item . '</td>';
                }
                if ($key == 0) {
                  $zero = $item;
                }
                if ($key == 1) {
                  $one = $item;
                }
                if ($key == 2) {
                  $two = $item;
                }
                if ($key == 3) {
                  $three = $item;
                }
                if ($key == 4) {
                  $four = $item;
                }
              }

              if (isset($three) && isset($one) && $three == $one) {
                $upper = $one;
              } else {
                unset($upper);
              }
              if (isset($four) && $two == $four) {
                $lower = $two;
              } else {
                unset($lower);
              }

              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $rd .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $rd .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
                    }
                    $lambdoma_map[$k][$ke][$key][$count]['lower'] = $explode[0];
                    $lambdoma_map[$k][$ke][$key][$count]['upper'] = $explode[1];
                    $lambdoma_map[$k][$ke][$key][$count]['key'] = $explode[2];
                    if ($explode[2] == 'origin') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'black';
                    }
                    if ($explode[2] == 'C') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'cee';
                    }
                    elseif ($explode[2] == 'C#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'ceesharp';
                    }
                    elseif ($explode[2] == 'D') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'dee';
                    }
                    elseif ($explode[2] == 'Eb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eeeflat';
                    }
                    elseif ($explode[2] == 'E') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eee';
                    }
                    elseif ($explode[2] == 'F') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eff';
                    }
                    elseif ($explode[2] == 'F#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'effsharp';
                    }
                    elseif ($explode[2] == 'G') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'gee';
                    }
                    elseif ($explode[2] == 'G#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'geesharp';
                    }
                    elseif ($explode[2] == 'A') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'ay';
                    }
                    elseif ($explode[2] == 'Bb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'beeflat';
                    }
                    elseif ($explode[2] == 'B') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'bee';
                    }
                    else {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'darkslategray';
                    }
                    if (isset($explode[3])) {
                      $lambdoma_map[$k][$ke][$key][$count]['frequency'] = $explode[3];
                    }
                  }
                }
              }

              $rd .= '</tr>';
              $count++;
            }
            $rd .= '</table>';
            $rd .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $rd .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $rd .= '</ol><hr>';
            $rd .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['rd'][] = $note_order;
            }
          }


          if ($ke == 'derivative_2') {
            unset($note_order);
            $rd2 = '<h4>Second Derivative (Odd/Even)</h4>';
            $count = 1;
            $rd2 .= '<table class="table"><tr>';
            foreach ($direction as $spliced_row) {
              $rd2 .= '<td class="tdgridltfirst">Row ' . $count . ': </td>';
              foreach ($spliced_row as $key => $item) {
                $test = $spliced_row;
                unset($clink); $clink = '';
                if (is_array($test)) {
                  $a = array_pop($test);
                  $b = array_pop($test);

                  if (is_numeric($a) && ($a) % 2 == 0 && ($b) % 2 == 0) {
                    $clink = 'highlight';
                  }
                  elseif (is_numeric($a) && ($a) % 2 != 0 && ($b) % 2 != 0) {
                    $clink = 'highlight';
                  }
                }
                if (isset($clink) && $clink == 'highlight') {
                  $rd2 .= '<td class="tdgrid highlight">' . $item . '</td>';
                }
                elseif (($item) % 2 == 0) {
                  $rd2 .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                elseif (($item) % 2 != 0) {
                  $rd2 .= '<td class="tdgrid">' . $item . '</td>';
                }
                if ($key == 0) {
                  $zero = $item;
                }
                if ($key == 1) {
                  $one = $item;
                }
                if ($key == 2) {
                  $two = $item;
                }
                if ($key == 3) {
                  $three = $item;
                }
                if ($key == 4) {
                  $four = $item;
                }
              }

              if (isset($three) && isset($one) && $three == $one) {
                $upper = $one;
              } else {
                unset($upper);
              }
              if (isset($four) && $two == $four) {
                $lower = $two;
              } else {
                unset($lower);
              }
              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $rd2 .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $rd2 .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
                    }
                    $lambdoma_map[$k][$ke][$key][$count]['lower'] = $explode[0];
                    $lambdoma_map[$k][$ke][$key][$count]['upper'] = $explode[1];
                    $lambdoma_map[$k][$ke][$key][$count]['key'] = $explode[2];
                    if ($explode[2] == 'origin') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'black';
                    }
                    if ($explode[2] == 'C') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'cee';
                    }
                    elseif ($explode[2] == 'C#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'ceesharp';
                    }
                    elseif ($explode[2] == 'D') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'dee';
                    }
                    elseif ($explode[2] == 'Eb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eeeflat';
                    }
                    elseif ($explode[2] == 'E') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eee';
                    }
                    elseif ($explode[2] == 'F') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eff';
                    }
                    elseif ($explode[2] == 'F#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'effsharp';
                    }
                    elseif ($explode[2] == 'G') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'gee';
                    }
                    elseif ($explode[2] == 'G#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'geesharp';
                    }
                    elseif ($explode[2] == 'A') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'ay';
                    }
                    elseif ($explode[2] == 'Bb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'beeflat';
                    }
                    elseif ($explode[2] == 'B') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'bee';
                    }
                    else {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'darkslategray';
                    }
                    if (isset($explode[3])) {
                      $lambdoma_map[$k][$ke][$key][$count]['frequency'] = $explode[3];
                    }
                  }
                }
              }
              $rd2 .= '</tr>';
              $count++;
            }
            $rd2 .= '</table>';
            $rd2 .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $rd2 .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $rd2 .= '</ol><hr>';
            $rd2 .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['rd2'][] = $note_order;
            }
          }
        }
      }
    }
  ///BOOKMARK
    $output .= $r;


    /*$output .= '<table><tr><th>k</th><th>ke</th><th>key</th><th>row</th><th>note</th><th>lower</th><th>upper</th></tr>';
    foreach($lambdoma_map as $k => $data) {
      foreach($data as $ke => $stuff) {
        foreach ($stuff as $key => $counts) {
          foreach ($counts as $count => $values) {
            $output .= "<tr><td>" . $k . "</td><td>" . $ke . "</td><td>" . $key . "</td><td>" . $count . "</td><td>" . $values['key'] . "</td><td>" . $values['lower'] . "</td><td>" . $values['upper'] . "</td></tr>";
          }
        }
      }
    }
    $output .= "</table>";*/

    $map = [];
    foreach ($lambdoma_map as $k => $data) {
      foreach ($data as $ke => $stuff) {
        //if ($ke = 'forward' || $k = 'row') {*/
        foreach ($stuff as $key => $counts) {
          foreach ($counts as $count => $values) {
            for ($u = 0; $u <= 16; $u++) {
              for ($l = 0; $l <= 16; $l++) {
                if (!in_array($values['upper'] . ':' . $values['lower'] . ':' . $values['class'] . ':' . $values['frequency'] . ':' . $values['key'], $map)) {
                  $map[] = $values['upper'] . ':' . $values['lower'] . ':' . $values['class'] . ':' . $values['frequency'] . ':' . $values['key'];
                }
              }
            }
          }
        }
        //}
      }
    }

    $output .= '<h3>Lambdoma Keyboard (<a href="http://lambdoma.com" target="_blank">Barbara Hero</a>) colored in with the locally determined frequency , and the letter note values based on a 256Hz C.</h3><table><tr><th></th><th>0</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th></tr>';
    for ($u=0; $u<=16; $u++) {
      $output .= '<tr>';
      $output .= '<td>' . $u . '</td>';


      for ($l = 0; $l <= 16; $l++) {
        $class = 'white';
        foreach ($map as $n => $unexploded) {
          $exploded = explode(':', $unexploded);
          if ($exploded[0] == $l && $exploded[1] == $u && !empty($explode[2])) {
            $np = 'b' . $n;
            if ($n >= $tone ) {
              $np = $n - $tone;
              $np = 'f' . $np;
            }
            $class = $exploded[2];
            $freq = $exploded[3];
            $kay = $exploded[4] . ':' . $np;
            //$output .= '<td>' . $u . '</td>';
          }
          if ($class == 'white') {
            if ($l == 0) {
              $class = 'lightgray';
            }
            elseif ($u == 0) {
              $class = 'lightgray';
            }
          }
        }
        $output .= '<td class ="' . $class . '">' . $u . '/' . $l . '<br>';
        if (isset($freq) && $freq != 'INF' && $class != 'white' && $class != 'lightgray') {
          $output .= intval($freq) . 'Hz<br>' . $kay . '</td>';
        }
        elseif (isset($freq) && $freq == 'INF') {
          $output .= 'INF</td>';
        }
        else {
          $output .= '-<br>-</td>';
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table>';

    $output .= $rd;


    $output .= '<h3>Lambdoma Keyboard (<a href="http://lambdoma.com" target="_blank">Barbara Hero</a>) colored in with the locally determined frequency , and the letter note values based on a 256Hz C.</h3><table><tr><th></th><th>0</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th></tr>';
    for ($u=0; $u<=16; $u++) {
      $output .= '<tr>';
      $output .= '<td>' . $u . '</td>';


      for ($l = 0; $l <= 16; $l++) {
        $class = 'white';
        foreach ($map as $n => $unexploded) {
          $exploded = explode(':', $unexploded);
          if ($exploded[0] == $l && $exploded[1] == $u && !empty($explode[2])) {
            $np = 'b' . $n;
            if ($n >= $tone ) {
              $np = $n - $tone;
              $np = 'f' . $np;
            }
            $class = $exploded[2];
            $freq = $exploded[3];
            $kay = $exploded[4] . ':' . $np;
            //$output .= '<td>' . $u . '</td>';
          }
          if ($class == 'white') {
            if ($l == 0) {
              $class = 'lightgray';
            }
            elseif ($u == 0) {
              $class = 'lightgray';
            }
          }
        }
        $output .= '<td class ="' . $class . '">' . $u . '/' . $l . '<br>';
        if (isset($freq) && $freq != 'INF' && $class != 'white' && $class != 'lightgray') {
          $output .= intval($freq) . 'Hz<br>' . $kay . '</td>';
        }
        elseif (isset($freq) && $freq == 'INF') {
          $output .= 'INF</td>';
        }
        else {
          $output .= '-<br>-</td>';
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table>';

    $output .= $rd2;


    $output .= '<h3>Lambdoma Keyboard (<a href="http://lambdoma.com" target="_blank">Barbara Hero</a>) colored in with the locally determined frequency , and the letter note values based on a 256Hz C.</h3><table><tr><th></th><th>0</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th></tr>';
    for ($u=0; $u<=16; $u++) {
      $output .= '<tr>';
      $output .= '<td>' . $u . '</td>';



      for ($l = 0; $l <= 16; $l++) {
        $class = 'white';
        foreach ($map as $n => $unexploded) {
          $exploded = explode(':', $unexploded);
          if ($exploded[0] == $l && $exploded[1] == $u && !empty($explode[2])) {
            $np = 'b' . $n;
            if ($n >= $tone ) {
              $np = $n - $tone;
              $np = 'f' . $np;
            }
            $class = $exploded[2];
            $freq = $exploded[3];
            $kay = $exploded[4] . ':' . $np;
            //$output .= '<td>' . $u . '</td>';
          }
          if ($class == 'white') {
            if ($l == 0) {
              $class = 'lightgray';
            }
            elseif ($u == 0) {
              $class = 'lightgray';
            }
          }
        }
        $output .= '<td class ="' . $class . '">' . $u . '/' . $l . '<br>';
        if (isset($freq) && $freq != 'INF' && $class != 'white' && $class != 'lightgray') {
          $output .= intval($freq) . 'Hz<br>' . $kay . '</td>';
        }
        elseif (isset($freq) && $freq == 'INF') {
          $output .= 'INF</td>';
        }
        else {
          $output .= '-<br>-</td>';
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table>';

    $output .= '</div><hr class="hr"><br></div>';

    // Now output the differences between different integers.
    $output .= '<div class="endtable begintext"><h2>PRIMES: Derivatives</h2>';
    $output .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios of repeating increments create.</p><div class="endtext"><br></div>';

    foreach($increments as $k=>$increment) {
      if ($k == 'row') {
        $r = '<h3>PRIMES: Original Matrix</h3>';
        foreach ($increment as $ke=>$direction) {
          if ($ke == 'forward') {
            unset($note_order);
            $r .= '';
            $r .= '<p></p><div class="endtext"><br></div>';
            $count = 1;
            $r .= '<table class="table"><tr>';
            foreach ($direction as $spliced_row) {
              $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
              foreach ($spliced_row as $key=>$item) {
                if (in_array($item, $primes)) {
                  $r .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                if (!in_array($item, $primes)) {
                  $r .= '<td class="tdgrid">' . $item . '</td>';
                }
                if ($key == 0) {
                  $zero = $item;
                }
                if ($key == 1) {
                  $one = $item;
                }
                if ($key == 2) {
                  $two = $item;
                }
                if ($key == 3) {
                  $three = $item;
                }
                if ($key == 4) {
                  $four = $item;
                }
              }

              if (isset($three) && isset($one) && $three == $one) {
                $upper = $one;
              } else {
                unset($upper);
              }
              if (isset($four) && $two == $four) {
                $lower = $two;
              } else {
                unset($lower);
              }

              ////dpm($upper);
              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $r .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $r .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
                    }
                    $lambdoma_map[$k][$ke][$key][$count]['lower'] = $explode[0];
                    $lambdoma_map[$k][$ke][$key][$count]['upper'] = $explode[1];
                    $lambdoma_map[$k][$ke][$key][$count]['key'] = $explode[2];
                    if ($explode[2] == 'origin') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'black';
                    }
                    if ($explode[2] == 'C') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zcee';
                    }
                    elseif ($explode[2] == 'C#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zceesharp';
                    }
                    elseif ($explode[2] == 'D') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zdee';
                    }
                    elseif ($explode[2] == 'Eb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zeeeflat';
                    }
                    elseif ($explode[2] == 'E') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zeee';
                    }
                    elseif ($explode[2] == 'F') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zeff';
                    }
                    elseif ($explode[2] == 'F#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zeffsharp';
                    }
                    elseif ($explode[2] == 'G') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zgee';
                    }
                    elseif ($explode[2] == 'G#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zgeesharp';
                    }
                    elseif ($explode[2] == 'A') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zay';
                    }
                    elseif ($explode[2] == 'Bb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zbeeflat';
                    }
                    elseif ($explode[2] == 'B') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'zbee';
                    }
                    else {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'darkslategray';
                    }
                    if (isset($explode[3])) {
                      $lambdoma_map[$k][$ke][$key][$count]['frequency'] = $explode[3];
                    }
                  }
                }
              }
              $r .= '</tr>';
              $count++;
            }
            $r .= '</table>';
            $r .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $r .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $r .= '</ol><hr>';
            $r .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['r'][] = $note_order;
            }
          }
          if ($ke == 'derivative') {
            unset($note_order);
            $rd = '<h4>First Derivative (Primes)</h4>';
            $count = 1;
            $rd .= '<table class="table"><tr>';
            foreach ($direction as $spliced_row) {
              $rd .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
              foreach ($spliced_row as $key=>$item) {
                if (in_array($item, $primes)) {
                  $rd .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                if (!in_array($item, $primes)) {
                  $rd .= '<td class="tdgrid">' . $item . '</td>';
                }
                if ($key == 0) {
                  $zero = $item;
                }
                if ($key == 1) {
                  $one = $item;
                }
                if ($key == 2) {
                  $two = $item;
                }
                if ($key == 3) {
                  $three = $item;
                }
                if ($key == 4) {
                  $four = $item;
                }
              }

              if (isset($three) && isset($one) && $three == $one) {
                $upper = $one;
              } else {
                unset($upper);
              }
              if (isset($four) && $two == $four) {
                $lower = $two;
              } else {
                unset($lower);
              }

              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $rd .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $rd .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
                    }
                    $lambdoma_map[$k][$ke][$key][$count]['lower'] = $explode[0];
                    $lambdoma_map[$k][$ke][$key][$count]['upper'] = $explode[1];
                    $lambdoma_map[$k][$ke][$key][$count]['key'] = $explode[2];
                    if ($explode[2] == 'origin') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'black';
                    }
                    if ($explode[2] == 'C') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'cee';
                    }
                    elseif ($explode[2] == 'C#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'ceesharp';
                    }
                    elseif ($explode[2] == 'D') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'dee';
                    }
                    elseif ($explode[2] == 'Eb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eeeflat';
                    }
                    elseif ($explode[2] == 'E') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eee';
                    }
                    elseif ($explode[2] == 'F') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eff';
                    }
                    elseif ($explode[2] == 'F#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'effsharp';
                    }
                    elseif ($explode[2] == 'G') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'gee';
                    }
                    elseif ($explode[2] == 'G#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'geesharp';
                    }
                    elseif ($explode[2] == 'A') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'ay';
                    }
                    elseif ($explode[2] == 'Bb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'beeflat';
                    }
                    elseif ($explode[2] == 'B') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'bee';
                    }
                    else {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'darkslategray';
                    }
                    if (isset($explode[3])) {
                      $lambdoma_map[$k][$ke][$key][$count]['frequency'] = $explode[3];
                    }
                  }
                }
              }
              $rd .= '</tr>';
              $count++;
            }
            $rd .= '</table>';
            $rd .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $rd .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $rd .= '</ol><hr>';
            $rd .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['rd'][] = $note_order;
            }
          }
          if ($ke == 'derivative_2') {
            unset($note_order);
            $rd2 = '<h4>Second Derivative (Primes)</h4>';
            $count = 1;
            $rd2 .= '<table class="table"><tr>';
            foreach ($direction as $spliced_row) {
              $rd2 .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
              foreach ($spliced_row as $key=>$item) {
                if (in_array($item, $primes)) {
                  $rd2 .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                if (!in_array($item, $primes)) {
                  $rd2 .= '<td class="tdgrid">' . $item . '</td>';
                }
                if ($key == 0) {
                  $zero = $item;
                }
                if ($key == 1) {
                  $one = $item;
                }
                if ($key == 2) {
                  $two = $item;
                }
                if ($key == 3) {
                  $three = $item;
                }
                if ($key == 4) {
                  $four = $item;
                }
              }

              if (isset($three) && isset($one) && $three == $one) {
                $upper = $one;
              } else {
                unset($upper);
              }
              if (isset($four) && $two == $four) {
                $lower = $two;
              } else {
                unset($lower);
              }

              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $rd2 .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $rd2 .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
                    }
                    $lambdoma_map[$k][$ke][$key][$count]['lower'] = $explode[0];
                    $lambdoma_map[$k][$ke][$key][$count]['upper'] = $explode[1];
                    $lambdoma_map[$k][$ke][$key][$count]['key'] = $explode[2];
                    if ($explode[2] == 'origin') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'black';
                    }
                    if ($explode[2] == 'C') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'cee';
                    }
                    elseif ($explode[2] == 'C#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'ceesharp';
                    }
                    elseif ($explode[2] == 'D') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'dee';
                    }
                    elseif ($explode[2] == 'Eb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eeeflat';
                    }
                    elseif ($explode[2] == 'E') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eee';
                    }
                    elseif ($explode[2] == 'F') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'eff';
                    }
                    elseif ($explode[2] == 'F#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'effsharp';
                    }
                    elseif ($explode[2] == 'G') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'gee';
                    }
                    elseif ($explode[2] == 'G#') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'geesharp';
                    }
                    elseif ($explode[2] == 'A') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'ay';
                    }
                    elseif ($explode[2] == 'Bb') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'beeflat';
                    }
                    elseif ($explode[2] == 'B') {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'bee';
                    }
                    else {
                      $lambdoma_map[$k][$ke][$key][$count]['class'] = 'darkslategray';
                    }
                    if (isset($explode[3])) {
                      $lambdoma_map[$k][$ke][$key][$count]['frequency'] = $explode[3];
                    }
                  }
                }
              }
              $rd2 .= '</tr>';
              $count++;
            }
            $rd2 .= '</table>';
            $rd2 .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $rd2 .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $rd2 .= '</ol><hr>';
            $rd2 .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['rd2'][] = $note_order;
            }
          }
        }
      }
    }
    $output .= $r;

    /*$output .= '<table><tr><th>k</th><th>ke</th><th>key</th><th>row</th><th>note</th><th>lower</th><th>upper</th></tr>';
    foreach($lambdoma_map as $k => $data) {
      foreach($data as $ke => $stuff) {
        foreach ($stuff as $key => $counts) {
          foreach ($counts as $count => $values) {
            $output .= "<tr><td>" . $k . "</td><td>" . $ke . "</td><td>" . $key . "</td><td>" . $count . "</td><td>" . $values['key'] . "</td><td>" . $values['lower'] . "</td><td>" . $values['upper'] . "</td></tr>";
          }
        }
      }
    }
    $output .= "</table>";*/

    $map = [];
    foreach ($lambdoma_map as $k => $data) {
      foreach ($data as $ke => $stuff) {
        //if ($ke = 'forward' || $k = 'row') {*/
        foreach ($stuff as $key => $counts) {
          foreach ($counts as $count => $values) {
            for ($u = 0; $u <= 16; $u++) {
              for ($l = 0; $l <= 16; $l++) {
                if (!in_array($values['upper'] . ':' . $values['lower'] . ':' . $values['class'] . ':' . $values['frequency'] . ':' . $values['key'], $map)) {
                  $map[] = $values['upper'] . ':' . $values['lower'] . ':' . $values['class'] . ':' . $values['frequency'] . ':' . $values['key'];
                }
              }
            }
          }
        }
        //}
      }
    }


    $output .= '<h3>Lambdoma Keyboard (<a href="http://lambdoma.com" target="_blank">Barbara Hero</a>) colored in with the locally determined frequency , and the letter note values based on a 256Hz C.</h3><table><tr><th></th><th>0</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th></tr>';
    for ($u=0; $u<=16; $u++) {

      $output .= '<tr>';
      $output .= '<td>' . $u . '</td>';


      for ($l = 0; $l <= 16; $l++) {
        $class = 'white';
        foreach ($map as $n => $unexploded) {
          $exploded = explode(':', $unexploded);
          if ($exploded[0] == $l && $exploded[1] == $u && !empty($explode[2])) {
            $np = 'b' . $n;
            if ($n >= $tone ) {
              $np = $n - $tone;
              $np = 'f' . $np;
            }
            $class = $exploded[2];
            $freq = $exploded[3];
            $kay = $exploded[4] . ':' . $np;
            //$output .= '<td>' . $u . '</td>';
          }
          if ($class == 'white') {
            if ($l == 0) {
              $class = 'lightgray';
            }
            elseif ($u == 0) {
              $class = 'lightgray';
            }
          }
        }
        $output .= '<td class ="' . $class . '">' . $u . '/' . $l . '<br>';
        if (isset($freq) && $freq != 'INF' && $class != 'white' && $class != 'lightgray') {
          $output .= intval($freq) . 'Hz<br>' . $kay . '</td>';
        }
        elseif (isset($freq) && $freq == 'INF') {
          $output .= 'INF</td>';
        }
        else {
          $output .= '-<br>-</td>';
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table>';

    $output .= $rd;


    $output .= '<h3>Lambdoma Keyboard (<a href="http://lambdoma.com" target="_blank">Barbara Hero</a>) colored in with the locally determined frequency , and the letter note values based on a 256Hz C.</h3><table><tr><th></th><th>0</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th></tr>';
    for ($u=0; $u<=16; $u++) {
      $output .= '<tr>';
      $output .= '<td>' . $u . '</td>';


      for ($l = 0; $l <= 16; $l++) {
        $class = 'white';
        foreach ($map as $n => $unexploded) {
          $exploded = explode(':', $unexploded);
          if ($exploded[0] == $l && $exploded[1] == $u && !empty($explode[2])) {
            $np = 'b' . $n;
            if ($n >= $tone ) {
              $np = $n - $tone;
              $np = 'f' . $np;
            }
            $class = $exploded[2];
            $freq = $exploded[3];
            $kay = $exploded[4] . ':' . $np;
            //$output .= '<td>' . $u . '</td>';
          }
          if ($class == 'white') {
            if ($l == 0) {
              $class = 'lightgray';
            }
            elseif ($u == 0) {
              $class = 'lightgray';
            }
          }
        }
        $output .= '<td class ="' . $class . '">' . $u . '/' . $l . '<br>';
        if (isset($freq) && $freq != 'INF' && $class != 'white' && $class != 'lightgray') {
          $output .= intval($freq) . 'Hz<br>' . $kay . '</td>';
        }
        elseif (isset($freq) && $freq == 'INF') {
          $output .= 'INF</td>';
        }
        else {
          $output .= '-<br>-</td>';
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table>';

    $output .= $rd2;


    $output .= '<h3>Lambdoma Keyboard (<a href="http://lambdoma.com" target="_blank">Barbara Hero</a>) colored in with the locally determined frequency , and the letter note values based on a 256Hz C.</h3><table><tr><th></th><th>0</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th></tr>';
    for ($u=0; $u<=16; $u++) {
      $output .= '<tr>';
      $output .= '<td>' . $u . '</td>';


      for ($l = 0; $l <= 16; $l++) {
        $class = 'white';
        foreach ($map as $n => $unexploded) {
          $exploded = explode(':', $unexploded);
          if ($exploded[0] == $l && $exploded[1] == $u && !empty($explode[2])) {
            $np = 'b' . $n;
            if ($n >= $tone ) {
              $np = $n - $tone;
              $np = 'f' . $np;
            }
            $class = $exploded[2];
            $freq = $exploded[3];
            $kay = $exploded[4] . ':' . $np;
            //$output .= '<td>' . $u . '</td>';
          }
          if ($class == 'white') {
            if ($l == 0) {
              $class = 'lightgray';
            }
            elseif ($u == 0) {
              $class = 'lightgray';
            }
          }
        }
        $output .= '<td class ="' . $class . '">' . $u . '/' . $l . '<br>';
        if (isset($freq) && $freq != 'INF' && $class != 'white' && $class != 'lightgray') {
          $output .= intval($freq) . 'Hz<br>' . $kay . '</td>';
        }
        elseif (isset($freq) && $freq == 'INF') {
          $output .= 'INF</td>';
        }
        else {
          $output .= '-<br>-</td>';
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table>';

    //dpm($note_assembly);

    
    return $output;
  }
}

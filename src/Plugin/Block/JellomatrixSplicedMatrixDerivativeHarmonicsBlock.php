<?php

namespace Drupal\jellomatrix\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Example: configurable text string' block.
 *
 * Drupal\Core\Block\BlockBase gives us a very useful set of basic functionality
 * for this configurable block. We can just fill in a few of the blanks with
 * defaultConfiguration(), blockForm(), blockSubmit(), and build().
 *
 * @Block(
 *   id = "jellomatrix_spliced_matrix_derivative_harmonics_block",
 *   admin_label = @Translation("Jellomatrix Spliced Matrix Derivative Harmonicss Block"),
 *   category = @Translation("JelloMatrix block")
 * )
 */
class JellomatrixSplicedMatrixDerivativeHarmonicsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * This method sets the block default configuration. This configuration
   * determines the block's behavior when a block is initially placed in a
   * region. Default values for the block configuration form should be added to
   * the configuration array. System default configurations are assembled in
   * BlockBase::__construct() e.g. cache setting and block title visibility.
   *
   * @see \Drupal\block\BlockBase::__construct()
   */
  public function defaultConfiguration($increment_original, $harmonics, $primes, $tone, $frequency) {
    // Place block output here //
    $intro = '';
    $output = '';
    $append = '';
    
    $note_assembly = [];
    $lambdoma_map = [];

    // PRIMARY MATRIX DERIVATIVES.

    // Increment_PRIME: with the prime_matrix grid and the prime_increments variable.

    // First output the original harmonics with $columns and $rows.
    // Now output the differences between different integers.
    $note_order = [];
    $intro.= '<div class="endtable begintext"><h2>PRIMES: Differences and Harmonics</h2>';
    $intro .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios of repeating increment_prime_original create.</p><div class="endtext"><br></div>';
    foreach($increment_original as $k=>$increment_prime) {
      if ($k == 'rldiag') {
        $r = '<h3>Row</h3>';
        foreach ($increment_prime as $ke=>$direction) {
          if ($ke == 'forward') {
            unset($note_order);
            $r .= '<h4>Forward (Primes) (x,y)|(x+1,y)</h4>';
            $count = 1;
            $r .= '<table class="table"><tr>';
            foreach ($direction as $row) {
              $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
              foreach ($row as $key=>$item) {
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
              }
              if (isset($two) && isset($zero) && $zero == $two) {
                $upper = $zero;
              }
              else {
                unset($upper);
              }
              if (isset($three) && $one == $three) {
                $lower = $one;
              }
              else {
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
          if ($ke == 'backward') {
            unset($note_order);
            $rd = '<h4>Backward (Primes) (x,y)|(x-1,y)</h4>';
            $count = 1;
            $rd .= '<table class="table"><tr>';
            foreach ($direction as $row) {
              $rd .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
              foreach ($row as $key=>$item) {
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
              }
              if (isset($two) && isset($zero) && $zero == $two) {
                $upper = $zero;
              }
              else {
                unset($upper);
              }
              if (isset($three) && $one == $three) {
                $lower = $one;
              }
              else {
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
        }
      }
      if ($k == 'row') {
        $lr = '<h3>Left to Right Diagonals across a Row</h3>';
        foreach ($increment_prime as $ke=>$direction) {
          if ($ke == 'forward') {
            unset($note_order);
            $lr .= '<h4>Forward (Odd/Even) (x,y)|(x+1,y+1)</h4>';
            $count = 1;
            $lr .= '<table class="table"><tr>';
            foreach ($direction as $row) {
              $lr .= '<td class="tdgridlt">LR Row ' . $count .': </td>';
              foreach ($row as $key=>$item) {
                $test = $row;
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
                  $lr .= '<td class="tdgrid highlight">' . $item . '</td>';
                }
                elseif (($item) % 2 == 0) {
                  $lr .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                elseif (($item) % 2 != 0) {
                  $lr .= '<td class="tdgrid">' . $item . '</td>';
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
              }
              if (isset($two) && isset($zero) && $zero == $two) {
                $upper = $zero;
              }
              else {
                unset($upper);
              }
              if (isset($three) && $one == $three) {
                $lower = $one;
              }
              else {
                unset($lower);
              }

              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $lr .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $lr .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
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
              $lr .= '</tr>';
              $count++;
            }
            $lr .= '</table>';
            $lr .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $lr .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $lr .= '</ol><hr>';
            $lr .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['lr'][] = $note_order;
            }
          }
          if ($ke == 'backward') {
            unset($note_order);
            $lrd = '<h4>Backward (x,y)|(x-1,y-1)</h4>';
            $count = 1;
            $lrd .= '<table class="table"><tr>';
            foreach ($direction as $row) {
              $lrd .= '<td class="tdgridlt">LR Row ' . $count .': </td>';
              foreach ($row as $key=>$item) {
                $test = $row;
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
                  $lrd .= '<td class="tdgrid highlight">' . $item . '</td>';
                }
                elseif (($item) % 2 == 0) {
                  $lrd .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                elseif (($item) % 2 != 0) {
                  $lrd .= '<td class="tdgrid">' . $item . '</td>';
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
              }
              if (isset($two) && isset($zero) && $zero == $two) {
                $upper = $zero;
              }
              else {
                unset($upper);
              }
              if (isset($three) && $one == $three) {
                $lower = $one;
              }
              else {
                unset($lower);
              }

              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $lrd .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $lrd .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
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
              $lrd .= '</tr>';
              $count++;
            }
            $lrd .= '</table>';
            $lrd .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $lrd .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $lrd .= '</ol><hr>';
            $lrd .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['lrd'][] = $note_order;
            }
          }
        }
      }
      if ($k == 'lrdiag') {
        $rl = '<h3>Right to Left Diagonals across a Row</h3>';
        foreach ($increment_prime as $ke=>$direction) {
          if ($ke == 'forward') {
            unset($note_order);
            $rl .= '<h4>Forward (Primes) (x,y)|(x+1,y-1)</h4>';
            $count = 1;
            $rl .= '<table class="table"><tr>';
            foreach ($direction as $row) {
              $rl .= '<td class="tdgridlt">RL Row ' . $count .': </td>';
              foreach ($row as $key=>$item) {
                if (in_array($item, $primes)) {
                  $rl .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                if (!in_array($item, $primes)) {
                  $rl .= '<td class="tdgrid">' . $item . '</td>';
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
              }
              if (isset($two) && isset($zero) && $zero == $two) {
                $upper = $zero;
              }
              else {
                unset($upper);
              }
              if (isset($three) && $one == $three) {
                $lower = $one;
              }
              else {
                unset($lower);
              }

              if (isset($upper)) {

                foreach ($harmonics as $note) {
                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $rl .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $rl .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
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
              $rl .= '</tr>';
              $count++;
            }
            $rl .= '</table>';
            $rl .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $rl .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $rl .= '</ol><hr>';
            $rl .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['rl'][] = $note_order;
            }
          }
          if ($ke == 'backward') {
            unset($note_order);
            $rld = '<h4>Backward (Primes) (x,y)|(x-1,y+1)</h4>';
            $count = 1;
            $rld .= '<table class="table"><tr>';
            foreach ($direction as $row) {
              $rld .= '<td class="tdgridlt">RL Row ' . $count .': </td>';
              foreach ($row as $key=>$item) {
                if (in_array($item, $primes)) {
                  $rld .= '<td class="tdgrid subhighlight">' . $item . '</td>';
                }
                if (!in_array($item, $primes)) {
                  $rld .= '<td class="tdgrid">' . $item . '</td>';
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
              }
              if (isset($two) && isset($zero) && $zero == $two) {
                $upper = $zero;
              }
              else {
                unset($upper);
              }
              if (isset($three) && $one == $three) {
                $lower = $one;
              }
              else {
                unset($lower);
              }

              if (isset($upper)) {
                foreach ($harmonics as $note) {

                  $explode = explode(':', $note);
                  if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower && !empty($explode[2])) {
                    $rld .= '<td class="tdgrid"><strong>' . $explode[2] . '</strong></td>';
                    $note_order[]= $explode[3];
                    $lower = (int)$lower;
                    $upper = (int)$upper; $added = $upper+$lower;
                    if (is_numeric($upper) && is_numeric($lower)) {
                      $rld .= '<td>' . $upper . '+' . $lower . '=<strong>' . $added . '</strong></td>';
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
              $rld .= '</tr>';
              $count++;
            }
            $rld .= '</table>';
            $rld .= '<h3>Order of frequencies: based on a ' . $frequency . 'Hz baseline or "C"</h3><ol>';
            if (isset($note_order)) {
              foreach ($note_order as $key => $hz) {

                $rld .= '<li>' . $hz . 'Hz</li>';
              }
            }
            $rld .= '</ol><hr>';
            $rld .= '<div class="endtext"><br></div>';
            if (isset($note_order)) {
              $note_assembly[$ke]['rld'][] = $note_order;
            }
          }
        }
      }
    }
    $output .= $r;
    $output .= $rd;


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

    $output .= $rl;
    $output .= $rld;


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

    $output .= $lr;
    $output .= $lrd;


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

    $output .= '<hr class="hr">';

    //dpm($note_assembly);$output .= '</div><hr class="hr"><br>';

    $append .= '<h2>Solving for the 3D: extrapolating the matrix into 3 dimensions creating a fabric that is infinitely scalable in 6 directions: a sample using waveform pairs form the 13/20 matrix:</h2>';
    $append .= '<img src="/sites/default/files/2019-07/jellomatrix_1320_3d_0.png" width="100%" height="auto" /><hr>';

    $append .= '<div class="begintext"><p><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">Jellomatrix</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://www.jellobrain.com" property="cc:attributionName" rel="cc:attributionURL">Ana Willem</a> is licensed since 2007 under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="https://www.jellobrain.com" rel="dct:source">https://www.jellobrain.com</a>.</p></div><hr class="hr">';

    return [
      'jellomatrix_intro_string' => $intro,
      'jellomatrix_append_string' => $append,
      'jellomatrix_block_meat' => $output,
    ];
  }

  /**
   * {@inheritdoc}
   *
   * This method defines form elements for custom block configuration. Standard
   * block configuration fields are added by BlockBase::buildConfigurationForm()
   * (block title and title visibility) and BlockFormController::form() (block
   * visibility settings).
   *
   * @see \Drupal\block\BlockBase::buildConfigurationForm()
   * @see \Drupal\block\BlockFormController::form()
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['jellomatrix_intro_string_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Block contents'),
      '#description' => $this->t('This text will appear in the block.'),
      '#default_value' => $this->configuration['jellomatrix_intro_string'] . '<br>' . $this->configuration['jellomatrix_block_meat'],
    ];
    $form['jellomatrix_append_string_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Block contents'),
      '#description' => $this->t('This text will appear in the block.'),
      '#default_value' => $this->configuration['jellomatrix_append_string'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * This method processes the blockForm() form fields when the block
   * configuration form is submitted.
   *
   * The blockValidate() method can be used to validate the form submission.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['jellomatrix_intro_string']
      = $form_state->getValue('jellomatrix_intro_string_text');
    $this->configuration['jellomatrix_append_string']
      = $form_state->getValue('jellomatrix_append_string_text');
  }

  /**
   * {@inheritdoc}
   */
  public function build($scale) {
    return [
      '#markup' => $this->configuration['jellomatrix_intro_string'] . '<br>' . $this->configuration['jellomatrix_block_meat'] . '<br>' . $this->configuration['jellomatrix_append_string'],
    ];
  }


}

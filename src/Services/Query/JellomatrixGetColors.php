<?php

namespace Drupal\jellomatrix\Services\Query;

/**
 * Description of JellomatrixGetColors
 *
 * @author eleven11
 */
class JellomatrixGetColors {
  
  /**
   * Returns the Color Options for the Waves.
   * name: jellomatrix_get_colors
   * @return = array()
   *
   **/
  public function getColors() {

    $hexvalues = [];
    $hexvalues = array(0 => "f", 1 => "e", 2 => "d", 3 => "c", 4 => "b", 5 => "a", 6 => "9", 7 => "8", 8 => "7", 9 => "6", 10 => "5", 11 => "4", 12 => "3", 13 => "2", 14 => "1", 15 => "0");

    // get 6 random indexes from array $hexvalues
    $hexvalue = shuffle($hexvalues);
    $color_hex[0] = $hexvalues[0];
    shuffle($hexvalues);
    $color_hex[1] = $hexvalues[0];
    shuffle($hexvalues);
    $color_hex[2] = $hexvalues[0];
    shuffle($hexvalues);
    $color_hex[3] = $hexvalues[0];
    shuffle($hexvalues);
    $color_hex[4] = $hexvalues[0];
    shuffle($hexvalues);
    $color_hex[5] = $hexvalues[0];

    $color_array = implode("", $color_hex);

    if (strlen($color_array) != 6) {
      $color_array = '000000';
    }
    $color = $color_array;
    $prependHash = FALSE;

    IF(STRPOS($color,'#')!==FALSE) {
      $prependHash = TRUE;
      $color       = STR_REPLACE('#',NULL,$color);
    }

    SWITCH($len=STRLEN($color)) {
      CASE 3:
        $color=PREG_REPLACE("/(.)(.)(.)/","\\1\\1\\2\\2\\3\\3",$color);
      CASE 6:
        BREAK;
      DEFAULT:
        TRIGGER_ERROR("Invalid hex length ($len). Must be (3) or (6)", E_USER_ERROR);
    }

    IF(!PREG_MATCH('/[a-f0-9]{6}/i',$color)) {
      $color = HTMLENTITIES($color);
      TRIGGER_ERROR( "Invalid hex string #$color", E_USER_ERROR );
    }

    $r = DECHEX(255-HEXDEC(SUBSTR($color,0,2)));
    $r = (STRLEN($r)>1)?$r:'0'.$r;
    $g = DECHEX(255-HEXDEC(SUBSTR($color,2,2)));
    $g = (STRLEN($g)>1)?$g:'0'.$g;
    $b = DECHEX(255-HEXDEC(SUBSTR($color,4,2)));
    $b = (STRLEN($b)>1)?$b:'0'.$b;

    $color_inverse = ($prependHash?'#':NULL).$r.$g.$b;

    $colors = array(
      'color_text' => '#' . $color_inverse,
      'color_background' => '#' . $color_array
    );

    return $colors;
  }
}

<?php

namespace Drupal\jellomatrix;

/**
 * Description of JellomatrixCircleGrids
 *
 * @author eleven11
 */
class JellomatrixCircleGrids {
  
  /**
   * Returns the Circle Grids for the Waves.
   * name: jellomatrix_circle_grids
   * @return = array()
   *
   **/
  public function getCircleGrids() {
  
  }
    
  public function saveImagePng($canvas,$file){
    // Store output.
    ob_start();
    // Output to buffer.
    imagePNG($canvas);
    // Write buffer to file.
    file_put_contents($file, ob_get_contents());
    chmod($file, 0777);
    // Clear and turn off buffer.
    ob_end_clean();
  }
  

  public function circleDetection($increment, $tone, $interval, $radius, $direction) {

    $circle_point_array = array();
    $slice = (360/$tone);
    $point = array();
    for($i=0;$i<$tone;$i++){
      $angle = (pi()*($slice * $i))/180.0;
      $newx = (($radius) * cos(($increment)*$angle));
      $newy = (($radius) * sin(($increment)*$angle));
      $point['newx'] = $newx;
      $point['newy'] = $newy;
      array_push($circle_point_array,$point);
    }

    foreach ($circle_point_array as $key=>$point) {
      $circle_point_array[$key]['newx'] = (int)($circle_point_array[$key]['newx'])+$radius;
      $circle_point_array[$key]['newy'] = (int)($circle_point_array[$key]['newy'])+$radius;
    }
    //dpm($direction);
    //dpm($circle_point_array);
    unlink("./sites/default/files/" . $direction . "_circle.png");

    $canvas = imagecreatetruecolor($radius*2, $radius*2);
    $back = imagecolorallocate($canvas, 255, 255, 255);
    imagefilledrectangle($canvas, 0, 0, $radius*2, $radius*2, $back);
    imagesetthickness($canvas , 2);

    foreach($circle_point_array as $key=>$point) {
      if ($direction == 'f') {
        $color = imagecolorallocate($canvas, 37, 182, 137);
      }
      if ($direction == 'h') {
        $color = imagecolorallocate($canvas, 37, 102, 200);
      }
      if ($direction == 'b') {
        $color = imagecolorallocate($canvas, 187, 102, 137);
      }
      if (isset($circle_point_array[$key+1])) {
        imageline($canvas, $point['newx'], $point['newy'], $circle_point_array[$key+1]['newx'], $circle_point_array[$key+1]['newy'], $color);
      } else {
        imageline($canvas, $point['newx'], $point['newy'], $circle_point_array[0]['newx'], $circle_point_array[0]['newy'], $color);
      }
    }

    header('Content-Type: image/png');
    $this->saveImagePng($canvas, "./sites/default/files/" . $direction . "_circle.png");
    imagepng($canvas, "./sites/default/files/" . $direction . "_circle.png");
    chmod("./sites/default/files/" . $direction . "_circle.png", 0777);

    $url = "./sites/default/files/" . $direction . "_circle.png";
    imagecreatefromstring(file_get_contents($url));

    return $canvas;
  }

  public function circleGrid($tone, $interval, $radius) {
    $circle_point_array = array();
    header("Content-Type: image/png");
    $canvas = @imagecreate(3*$radius, 3*$radius)
    or die("Cannot Initialize new GD image stream");
    //$canvas = imagecreatetruecolor($radius*2, $radius*2);
    $back = imagecolorallocate($canvas, 245, 245, 245);
    imagefilledellipse($canvas, 0, 0, $radius*2, $radius*2, $back);
    imagesetthickness($canvas , 1);

    $red = imagecolorallocate($canvas, 153, 0, 0);
    $purple = imagecolorallocate($canvas, 153, 0, 153);
    $arc = 360/$interval;
    $linebit = $tone/$radius;
    for($i=0;$i<=360;$i+=$arc) {
      //$x = round(cos($i)*400)+450;
      //$y = round(sin($i)*400)+450;
      for($l=0;$l<=$tone+2;$l++) {
        $bit = $linebit*$l*(1.5*$radius);
        $x = round(cos(deg2rad($i))*($bit))+((1.5*$radius));
        $y = round(sin(deg2rad($i))*($bit))+((1.5*$radius));
        imagefilledellipse($canvas, $x, $y, 7, 7, $red);
        imageline($canvas, (1.5*$radius), (1.5*$radius), $x, $y, $purple);
      }
    }
    $this->saveImagePng($canvas,"./sites/default/files/circle_grid.png");
    imagepng( $canvas, "./sites/default/files/circle_grid.png" );
    chmod("./sites/default/files/circle_grid.png", 0777);


    $url = "./sites/default/files/circle_grid.png";
    imagecreatefromstring(file_get_contents($url));

    return $canvas;
  }

  public function toArg($arg) {
    if ($arg == '%') {
      return 'none';
    }
    else {
      return $arg;
    }
  }
}

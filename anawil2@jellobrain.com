<?php
function save_imagepng($canvas,$file){
  // Store output.
  ob_start();
  // Output to buffer.
  imagePNG($canvas);
  // Write buffer to file.
  file_put_contents($file, ob_get_contents(), FILE_BINARY);
  // Clear and turn off buffer.
  ob_end_clean();
}

/**
 * Implements hook_theme().
 */
function jellomatrix_theme($existing, $type, $theme, $path) {
  return array (
    'jellomatrix' => array(
      'render element' => 'custom_page',
      'path' => $path . '/templates',
      'template' => 'jellomatrix',
    ),
  );
}


function jellomatrix_circle_detection($tone, $interval, $radius) {
  $circle_point_array = array();
  $slice = (360/$tone);
  $point = array();
  for($i=0;$i<$tone;$i++){
    $angle = (pi()*(($slice * $i)))/180.0;
    $balance = $interval - $tone;
    while ($balance > $interval) {
      $balance = $balance - $tone;
    }
    $newx = (($radius) * cos(($balance)*$angle));
    $newy = (($radius) * sin(($balance)*$angle));
    $point['newx'] = $newx;
    $point['newy'] = $newy;
    array_push($circle_point_array,$point);
  }

  foreach ($circle_point_array as $key=>$point) {
    $circle_point_array[$key]['newx'] = (int)($circle_point_array[$key]['newx'])+$radius;
    $circle_point_array[$key]['newy'] = (int)($circle_point_array[$key]['newy'])+$radius;
  }

  $canvas = imagecreatetruecolor($radius*2, $radius*2);
  $back = imagecolorallocate($canvas, 245, 245, 245);
  imagefilledrectangle($canvas, 0, 0, $radius*2, $radius*2, $back);
  imagesetthickness($canvas , 2);
  foreach($circle_point_array as $key=>$point) {
      $color = imagecolorallocate($canvas, 37, 82, 137);
      if (isset($circle_point_array[$key+1])) {
        imageline($canvas, $point['newx'], $point['newy'], $circle_point_array[$key+1]['newx'], $circle_point_array[$key+1]['newy'], $color);
      } else {
        imageline($canvas, $point['newx'], $point['newy'], $circle_point_array[0]['newx'], $circle_point_array[0]['newy'], $color);
      }
  }
  header('Content-Type: image/png');
  save_imagepng($canvas,"./sites/default/files/voodoo-doll-penticle.png");
  imagepng( $canvas, "./sites/default/files/voodoo-doll-penticle.png" );

  $url = "./sites/default/files/voodoo-doll-penticle.png";
  imagecreatefromstring(file_get_contents($url));

  return $canvas;
}

function jellomatrix_to_arg($arg) {
  if ($arg == '%') {
    return 'none';
  }
  else {
    return $arg;
  }
}

function jellomatrix_phi_equation($spliced_matrix, $tone, $interval) {


}

function jellomatrix_primes($tone) {
  $primes = array();
  $primes[] = 1;
  // Numbers to be checked as prime.
  for($i=1;$i<=$tone;$i++){
		$counter = 0;
    // All divisible factors.
		for($j=1;$j<=$i;$j++){
			if($i % $j==0){
				$counter++;
			}
		}
		// Prime requires 2 rules ( divisible by 1 and divisible by itself).
		if($counter==2){
			$primes[] = $i;
	  }
  }
  return $primes;
}

/*
 *
 * name: jellomatrix_prime_basetone
 * @param = $tone, $interval
 * @return = array()
 *
 */
function jellomatrix_prime_basetone($tone, $interval) {
  $matrix_count = 1;
  $prime_bt = array();

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
          if ($i <= $tone) {
            $prime_bt[$i][$t]['tone'] = $i;
          }
          else {
            $prime_bt[$i][$t]['tone'] = $i-$tone;
            $sm_ct = 0;
            while ($prime_bt[$i][$t]['tone'] > $tone) {
              $prime_bt[$i][$t]['tone'] = $prime_bt[$i][$t]['tone']-$tone;
              $sm_ct++;
            }
          }
          $prime_bt[$i][$t]['column'] = $column;
          $prime_bt[$i][$t]['row'] = $row;
          $prime_bt[$i][$t]['grid_x'] = $column;
          $prime_bt[$i][$t]['grid_y'] = $row;
          $prime_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
          $prime_bt[$i][$t]['interval'] = floor($prime_bt[$i][$t]['count']/$tone*$interval);
          $prime_bt[$i][$t]['color'] = '#333';
          $prime_bt[$i][$t]['opacity'] = 1;
          $prime_bt[$i][$t]['padding'] = 3;
          $prime_bt[$i][$t]['background'] = '#fafafa';
          $record = $prime_bt[$i][$t]['tone'];
        }
        else {
          if (2*$record <= $interval) {
            while ($record >= $tone) {
              $record = $record-$tone;
            }
          }
          $new_record = $record + $balance;
          while ($new_record > $tone) {
            $new_record = $new_record - $tone;
          }

          $record = $new_record;


          $prime_bt[$i][$t]['tone'] = $record;
          $prime_bt[$i][$t]['column'] = $column;
          $prime_bt[$i][$t]['row'] = $row;
          $prime_bt[$i][$t]['grid_x'] = $column;
          $prime_bt[$i][$t]['grid_y'] = $row;
          $prime_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
          $prime_bt[$i][$t]['interval'] = floor($prime_bt[$i][$t]['count']/$tone*$interval);
          $prime_bt[$i][$t]['color'] = '#333';
          $prime_bt[$i][$t]['opacity'] = 1;
          $prime_bt[$i][$t]['padding'] = 3;
          $prime_bt[$i][$t]['background'] = '#fafafa';
        }
        $matrix_count++;
      }
    }
  }

  return $prime_bt;
}

/*
 *
 * name: jellomatrix_response_basetone
 * @param = $tone, $interval
 * @return = array()
 *
 */
function jellomatrix_response_basetone($tone, $interval) {
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


/*
 *
 * name: jellomatrix_prime_offset
 * @param = $tone, $interval
 * @return = array()
 *
 */
function jellomatrix_prime_offset($tone, $interval, $offset) {
  $matrix_count = 1;
  $prime_bt = array();

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
          if ($i <= $tone) {
            $prime_bt[$i][$t]['tone'] = $i;
          }
          else {
            $prime_bt[$i][$t]['tone'] = $i-$tone;
            $sm_ct = 0;
            while ($prime_bt[$i][$t]['tone'] > $tone) {
              $prime_bt[$i][$t]['tone'] = $prime_bt[$i][$t]['tone']-$tone;
              $sm_ct++;
            }
          }
          $prime_bt[$i][$t]['column'] = $column;
          $prime_bt[$i][$t]['row'] = $row;
          $prime_bt[$i][$t]['grid_x'] = $column;
          $prime_bt[$i][$t]['grid_y'] = $row;
          $prime_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
          $prime_bt[$i][$t]['interval'] = floor($prime_bt[$i][$t]['count']/$tone*$interval);
          $prime_bt[$i][$t]['color'] = '#333';
          $prime_bt[$i][$t]['opacity'] = 1;
          $prime_bt[$i][$t]['padding'] = 3;
          $prime_bt[$i][$t]['background'] = '#fafafa';
          $record = $prime_bt[$i][$t]['tone'];
        }
        else {
          if (2*$record <= $interval) {
            while ($record >= $tone) {
              $record = $record-$tone;
            }
          }
          $new_record = $record + $balance;
          while ($new_record > $tone) {
            $new_record = $new_record - $tone;
          }

          $record = $new_record;


          $prime_bt[$i][$t]['tone'] = $record;
          $prime_bt[$i][$t]['column'] = $column;
          $prime_bt[$i][$t]['row'] = $row;
          $prime_bt[$i][$t]['grid_x'] = $column;
          $prime_bt[$i][$t]['grid_y'] = $row;
          $prime_bt[$i][$t]['count'] = (($column-1)*$interval)+$row;
          $prime_bt[$i][$t]['interval'] = floor($prime_bt[$i][$t]['count']/$tone*$interval);
          $prime_bt[$i][$t]['color'] = '#333';
          $prime_bt[$i][$t]['opacity'] = 1;
          $prime_bt[$i][$t]['padding'] = 3;
          $prime_bt[$i][$t]['background'] = '#fafafa';
        }
        $matrix_count++;
      }
    }
  }

  if ($offset < 0) {
    for ($i = 1; $i <= $offset; $i++) {
      array_shift($prime_bt);
    }
  }
  elseif ($offset > 0) {
    for ($i = 1; $i <= $offset; $i++) {
      array_pop($prime_bt);
    }
  }

  $old_arr = $prime_bt;
  $prime_bt = array();
  $i = 1;
  foreach($old_arr as $old_val) {
      $prime_bt[$i]  = $old_val;
      $i++;
  }
  foreach($prime_bt as $r=>$row) {
    foreach ($row as $i=>$item) {
      $prime_bt[$r][$i]['row'] = $r;
      $prime_bt[$r][$i]['grid_y'] = $r;
    }
  }

  return $prime_bt;
}

/*
 *
 * name: jellomatrix_response_offset
 * @param = $tone, $interval
 * @return = array()
 *
 */
function jellomatrix_response_offset($tone, $interval, $offset) {
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
          else {
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

/**
 *
 * name: jellomatrix_spliced_basetone
 * @param $prime_matrix
 * @param $response_matrix
 * @param $tone
 * @param $interval
 * @return array = array()
 *
 * @internal param $ = $prime_matrix, $prime_reversed
 */
function jellomatrix_spliced_basetone($prime_matrix, $response_matrix, $tone, $interval) {

  $spliced_bt = array();
  for ($i = 1; $i <= $interval; $i++) {
    $count = 1;
    for ($t = 1; $t <= $tone; $t++) {
      if ($prime_matrix[$i][$t]['count'] == 1) {
        $prime_matrix[$i][$t]['spliced_count'] = $prime_matrix[$i][$t]['count']*$prime_matrix[$i][$t]['column'];
        $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
        $prime_splice_record = $prime_matrix[$i][$t]['spliced_count'];
        $count++;
        $response_matrix[$i][$t]['spliced_count'] = $interval + ($response_matrix[$i][$t]['count']*$response_matrix[$i][$t]['column']);
        $response_matrix[$i][$t]['column'] = $prime_matrix[$i][$t]['column']+1;
        $response_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column']+1;
        $spliced_bt[$i][$count] = $response_matrix[$i][$t];
        $response_splice_record = $response_matrix[$i][$t]['spliced_count'];
        $count++;
      }
      else {
        if (isset($response_splice_record)) {
          if (isset($prime_splice_record)) {
            $prime_matrix[$i][$t]['spliced_count'] = $prime_splice_record + $interval;
            $prime_matrix[$i][$t]['column'] = ($prime_matrix[$i][$t]['column'] * 2) - 1;
            $prime_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column'];
            $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
            $count++;
            $response_matrix[$i][$t]['spliced_count'] = $response_splice_record + $interval;
            $response_matrix[$i][$t]['column'] = $prime_matrix[$i][$t]['column'] + 1;
            $response_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column']+1;
            $spliced_bt[$i][$count] = $response_matrix[$i][$t];
            $response_splice_record = $response_matrix[$i][$t]['spliced_count'];
            $count++;
          }
        }
      }
    }
  }
  return $spliced_bt;
}



/**
 *
 * name: jellomatrix_spliced_offset
 * @param $prime_matrix
 * @param $response_matrix
 * @param $tone
 * @param $interval
 * @param $offset
 * @return array = array()
 *
 * @internal param $ = $prime_matrix, $prime_reversed
 */
function jellomatrix_spliced_offset($prime_matrix, $response_matrix, $tone, $interval, $offset) {
  $spliced_bt = array();

  $intoff = $interval - abs($offset);
  for ($i = 1; $i <= $intoff; $i++) {
    $count = 1;
    for ($t = 1; $t <= $tone; $t++) {
      if ($prime_matrix[$i][$t]['count'] == 1) {
        $prime_matrix[$i][$t]['spliced_count'] = $prime_matrix[$i][$t]['count']*$prime_matrix[$i][$t]['column'];
        $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
        $prime_splice_record = $prime_matrix[$i][$t]['spliced_count'];
        $count++;
        $response_matrix[$i][$t]['spliced_count'] = $interval + ($response_matrix[$i][$t]['count']*$response_matrix[$i][$t]['column']);
        $response_matrix[$i][$t]['column'] = $prime_matrix[$i][$t]['column']+1;
        $response_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column']+1;
        $spliced_bt[$i][$count] = $response_matrix[$i][$t];
        $response_splice_record = $response_matrix[$i][$t]['spliced_count'];
        $count++;
      }
      else {
        if (isset($response_splice_record)) {
          if (isset($prime_splice_record)) {
            $prime_matrix[$i][$t]['spliced_count'] = $prime_splice_record + $interval;
            $prime_matrix[$i][$t]['column'] = ($prime_matrix[$i][$t]['column'] * 2) - 1;
            $prime_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column'];
            $spliced_bt[$i][$count] = $prime_matrix[$i][$t];
            $count++;
            $response_matrix[$i][$t]['spliced_count'] = $response_splice_record + $interval;
            $response_matrix[$i][$t]['column'] = $prime_matrix[$i][$t]['column'] + 1;
            $response_matrix[$i][$t]['grid_x'] = $prime_matrix[$i][$t]['column'] + 1;
            $spliced_bt[$i][$count] = $response_matrix[$i][$t];
            $response_splice_record = $response_matrix[$i][$t]['spliced_count'];
            $count++;
          }
        }
      }
    }
  }
  return $spliced_bt;
}

function jellomatrix_wave_detection($prime_matrix, $tone, $interval, $spliced_matrix) {

  $prime_series = array();

  $prime_series_calculator = (($tone+1)/2);
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

  foreach ($spliced_matrix as $key=>$spliced_row) {
    $count = 1;
    foreach ($spliced_row as $k=>$item) {
      if (isset($spliced_matrix[$key][$k+1]['tone'])) {
        if ($spliced_matrix[$key][$k+1]['tone'] == $spliced_matrix[$key][$k]['tone']) {
          $spliced_matrix[$key][$k]['wave_limit'] = 'active';
          $spliced_matrix[$key][$k+1]['pole_shift'] = 2;
          $spliced_matrix[$key][$k]['pole_shift'] = 1;
          if ($count == 1) {
            $spliced_matrix[$key][$k]['first'] = 1;
          }
          $count++;
        }
        foreach ($prime_series as $position => $note) {
          if ($item['tone'] == $note) {
            // We don't want to start at zero.
            $spliced_matrix[$key][$k]['scale_position'] = $position + 1;
          }
        }
      }
      if (isset($spliced_matrix[$key][$k-1]['tone'])) {
        if ($spliced_matrix[$key][$k-1]['tone'] == $spliced_matrix[$key][$k]['tone']) {
          $count++;
          $spliced_matrix[$key][$k]['wave_limit'] = 'active';
          $spliced_matrix[$key][$k-1]['pole_shift'] = 1;
          $spliced_matrix[$key][$k]['pole_shift'] = 2;
        }
        foreach ($prime_series as $position => $note) {
          if ($item['tone'] == $note) {
            // We don't want to start at zero.
            $spliced_matrix[$key][$k]['scale_position'] = $position + 1;
          }
        }
      }
      if (!isset($spliced_matrix[$key][$k+1]['tone'])) {
        $bridge_kplus = ($k+1)-(2 * $tone);
        if ($spliced_matrix[$key][$bridge_kplus]['tone'] == $spliced_matrix[$key][$k]['tone']) {
          $count++;
          $spliced_matrix[$key][$k]['wave_limit'] = 'active';
          $spliced_matrix[$key][$bridge_kplus]['pole_shift'] = 2;
          $spliced_matrix[$key][$k]['pole_shift'] = 1;
        }
        foreach ($prime_series as $position => $note) {
          if ($item['tone'] == $note) {
            // We don't want to start at zero.
            $spliced_matrix[$key][$k]['scale_position'] = $position + 1;
          }
        }
      }
      if (!isset($spliced_matrix[$key][$k-1]['tone'])) {
        $bridge_kminus = ($k-1) + (2 * $tone);
        if ($spliced_matrix[$key][$bridge_kminus]['tone'] == $spliced_matrix[$key][$k]['tone']) {
          $count++;
          $spliced_matrix[$key][$k]['wave_limit'] = 'active';
          $spliced_matrix[$key][$bridge_kminus]['pole_shift'] = 1;
          $spliced_matrix[$key][$k]['pole_shift'] = 2;
        }
        foreach ($prime_series as $position => $note) {
          if ($item['tone'] == $note) {
            // We don't want to start at zero.
            $spliced_matrix[$key][$k]['scale_position'] = $position + 1;
          }
        }
      }
    }
  }

  foreach ($spliced_matrix as $key=>$spliced_row) {
    foreach ($spliced_row as $k=>$item) {
      if (!isset($item['scale_position'])) {
        $no_scales = 'no scales';
      }
      if (isset($item['pole_shift'])  && !isset($no_scales)) {
        if ($item['pole_shift'] == 1) {
          $spliced_matrix[$key][$k]['scale'] = $spliced_matrix[$key][$k]['scale_position'];
          $spliced_matrix[$key][$k]['phase_color'] = 'red';
          if (isset($spliced_matrix[$key][$k]['scale']) && isset($spliced_matrix[$key][$k]['scale_position'])) {
            if (isset($spliced_matrix[$key+1][$k-2]) && isset($spliced_matrix[$key][$k]['scale'])) {
              if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                if ($spliced_matrix[$key+1][$k-2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']+1) {
                  $spliced_matrix[$key+1][$k-2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                  $spliced_matrix[$key+1][$k-2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                }
              }
              else {
                if ($spliced_matrix[$key+1][$k-2]['scale_position'] == 1) {
                  $spliced_matrix[$key+1][$k-2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                  $spliced_matrix[$key+1][$k-2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                }
              }
            }
            if (isset($spliced_matrix[$key-1][$k-2]) && isset($spliced_matrix[$key][$k]['scale'])) {
              if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                if ($spliced_matrix[$key-1][$k-2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']+1) {
                  $spliced_matrix[$key-1][$k-2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                  $spliced_matrix[$key-1][$k-2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                }
              }
              else {
                if ($spliced_matrix[$key-1][$k-2]['scale_position'] == 1) {
                  $spliced_matrix[$key-1][$k-2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                  $spliced_matrix[$key-1][$k-2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                }
              }
            }
            if (!isset($spliced_matrix[$key+1][$k-2]) && isset($spliced_matrix[$key][$k]['scale']) && isset($spliced_matrix[$key][$k]['scale_position'])) {
              unset($new_key);
              unset($bridge_kplus);
              if (!isset($spliced_matrix[$key+1])) {
                $new_key = ($key+1) - $interval;
                if (!isset($spliced_matrix[$new_key][$k-2])) {
                  $bridge_kplus = (2*$tone)+($k-2);
                }
              }
              if (isset($spliced_matrix[$key+1])) {
                if (!isset($spliced_matrix[$key+1][$k-2])) {
                  $bridge_kplus = (2*$tone)+($k-2);
                }
              }
              if (isset($new_key) && !isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix[$new_key][$k-2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']+1) {
                    $spliced_matrix[$new_key][$k-2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$k-2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix[$new_key][$k-2]['scale_position'] == 1) {
                    $spliced_matrix[$new_key][$k-2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$k-2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              if (isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']+1) {
                    $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == 1) {
                    $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              if (!isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix[$key+1][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']+1) {
                    $spliced_matrix[$key+1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$key+1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix[$key+1][$bridge_kplus]['scale_position'] == 1) {
                    $spliced_matrix[$key+1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$key+1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
            }
            if (!isset($spliced_matrix[$key-1][$k-2]) && isset($spliced_matrix[$key][$k]['scale'])) {
              unset($new_key);
              unset($bridge_kplus);
              if (!isset($spliced_matrix[$key-1])) {
                $new_key = ($key-1) + $interval;
                if (!isset($spliced_matrix[$new_key][$k-2])) {
                  $bridge_kplus = (2*$tone)+($k-2);
                }
              }
              if (isset($spliced_matrix[$key-1])) {
                if (!isset($spliced_matrix[$key-1][$k-2])) {
                  $bridge_kplus = (2*$tone)+($k-2);
                }
              }
              if (isset($new_key) && !isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] < $tone && isset($spliced_matrix[$new_key][$k-2]['scale_position'])) {
                  if ($spliced_matrix[$new_key][$k-2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']+1) {
                    $spliced_matrix[$new_key][$k-2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$k-2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix[$new_key][$k-2]['scale_position'] == 1) {
                    $spliced_matrix[$new_key][$k-2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$k-2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              if (isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] < $tone && isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']+1) {
                    $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
                else {
                  if (isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position']) && $spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == 1) {
                    $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              if (!isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] < $tone && isset($spliced_matrix[$key-1][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix[$key-1][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']+1) {
                    $spliced_matrix[$key-1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$key-1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix[$key-1][$bridge_kplus]['scale_position'] == 1) {
                    $spliced_matrix[$key-1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$key-1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
            }
          }
        }
        if ($item['pole_shift'] == 2 && isset($spliced_matrix[$key][$k]['scale_position'])) {
          $spliced_matrix[$key][$k]['scale'] = $spliced_matrix[$key][$k]['scale_position'];
          $spliced_matrix[$key][$k]['phase_color'] = 'green';
          if (isset($spliced_matrix[$key][$k]['scale'])) {
            if (isset($spliced_matrix[$key+1][$k+2]) && isset($spliced_matrix[$key][$k]['scale'])) {
              if ($spliced_matrix[$key][$k]['scale_position'] > 1) {
                if ($spliced_matrix[$key+1][$k+2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']-1) {
                  $spliced_matrix[$key+1][$k+2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                  if (isset($spliced_matrix[$key+1][$k+2]['phase_color'])) {
                    $spliced_matrix[$key+1][$k+2]['phase_color'] = 'purple';
                  }
                  else {
                    $spliced_matrix[$key+1][$k+2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              else {
                if ($spliced_matrix[$key+1][$k+2]['scale_position'] == $tone) {
                  $spliced_matrix[$key+1][$k+2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                  if (isset($spliced_matrix[$key+1][$k+2]['phase_color'])) {
                    $spliced_matrix[$key+1][$k+2]['phase_color'] = 'purple';
                  }
                  else {

                    $spliced_matrix[$key+1][$k+2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
            }
            if (isset($spliced_matrix[$key-1][$k+2]) && isset($spliced_matrix[$key][$k]['scale'])) {
              if ($spliced_matrix[$key][$k]['scale_position'] > 1) {
                if ($spliced_matrix[$key-1][$k+2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']-1) {
                  $spliced_matrix[$key-1][$k+2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                  if (isset($spliced_matrix[$key-1][$k+2]['phase_color'])) {
                    $spliced_matrix[$key-1][$k+2]['phase_color'] = 'purple';
                  }
                  else {
                    $spliced_matrix[$key-1][$k+2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              else {
                if ($spliced_matrix[$key-1][$k+2]['scale_position'] == $tone) {
                  $spliced_matrix[$key-1][$k+2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                  if (isset($spliced_matrix[$key-1][$k+2]['phase_color'])) {
                    $spliced_matrix[$key-1][$k+2]['phase_color'] = 'purple';
                  }
                  else {
                    $spliced_matrix[$key-1][$k+2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
            }
            if (!isset($spliced_matrix[$key+1][$k+2]) && isset($spliced_matrix[$key][$k]['scale'])) {
              unset($new_key);
              unset($bridge_kplus);
              if (!isset($spliced_matrix[$key+1])) {
                $new_key = ($key+1) - $interval;
                if (!isset($spliced_matrix[$new_key][$k+2])) {
                  $bridge_kplus = ($k+2)-(2*$tone);
                }
              }
              if (isset($spliced_matrix[$key+1])) {
                if (!isset($spliced_matrix[$key+1][$k+2])) {
                  $bridge_kplus = ($k+2)-(2*$tone);
                }
              }
              if (isset($bridge_kplus)) {
                if ($bridge_kplus > (2*$tone)) {
                  $bridge_kplus = (3*$tone) - $bridge_kplus;
                }
                if ($bridge_kplus < 1) {
                  $bridge_kplus = $bridge_kplus + $tone;
                }
              }
              if (isset($new_key) && !isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$new_key][$k+2]['scale_position'])) {
                  if ($spliced_matrix[$new_key][$k+2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']-1) {
                    $spliced_matrix[$new_key][$k+2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$new_key][$k+2]['phase_color'])) {
                      $spliced_matrix[$new_key][$k+2]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$new_key][$k+2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix[$new_key][$k+2]['scale_position'] == $tone) {
                    $spliced_matrix[$new_key][$k+2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    $spliced_matrix[$new_key][$k+2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                  }
                }
              }
              if (isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']-1) {
                    $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$new_key][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $tone) {
                    $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$new_key][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (!isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$key+1][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix[$key+1][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']-1) {
                    $spliced_matrix[$key+1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$key+1][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix[$key+1][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$key+1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix[$key+1][$bridge_kplus]['scale_position'] == $tone) {
                    $spliced_matrix[$key+1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$key+1][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix[$key+1][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$key+1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
              }
            }
            if (!isset($spliced_matrix[$key-1][$k+2]) && isset($spliced_matrix[$key][$k]['scale'])) {
              unset($new_key);
              unset($bridge_kplus);
              if (!isset($spliced_matrix[$key-1])) {
                $new_key = ($key-1) + $interval;
                if (!isset($spliced_matrix[$new_key][$k+2])) {
                  $bridge_kplus = ($k+2)-(2*$tone);
                }
              }
              if (isset($spliced_matrix[$key-1])) {
                if (!isset($spliced_matrix[$key-1][$k+2])) {
                  $bridge_kplus = ($k+2)-(2*$tone);
                }
              }
              if (isset($bridge_kplus)) {
                if ($bridge_kplus > (2*$tone)) {
                  $bridge_kplus = (3*$tone) - $bridge_kplus;
                }
                if ($bridge_kplus < 1) {
                  $bridge_kplus = $bridge_kplus + $tone;
                }
              }
              if (isset($new_key) && !isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$new_key][$k+2]['scale_position'])) {
                  if ($spliced_matrix[$new_key][$k+2]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']-1) {
                    $spliced_matrix[$new_key][$k+2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$new_key][$k+2]['phase_color'])) {
                      $spliced_matrix[$new_key][$k+2]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$new_key][$k+2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if (isset($bridge_plus) && isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position']) && $spliced_matrix[$new_key][$k+2]['scale_position'] == $tone) {
                    $spliced_matrix[$new_key][$k+2]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$new_key][$k+2]['phase_color'])) {
                      $spliced_matrix[$new_key][$k+2]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$new_key][$k+2]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$new_key][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']-1) {
                    $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$new_key][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix[$new_key][$bridge_kplus]['scale_position'] == $tone) {
                    $spliced_matrix[$new_key][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$new_key][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (!isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix[$key][$k]['scale_position'] > 1 && isset($spliced_matrix[$key-1][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix[$key-1][$bridge_kplus]['scale_position'] == $spliced_matrix[$key][$k]['scale_position']-1) {
                    $spliced_matrix[$key-1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$key-1][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix[$key-1][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$key-1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix[$key-1][$bridge_kplus]['scale_position'] == $tone) {
                    $spliced_matrix[$key-1][$bridge_kplus]['scale'] = $spliced_matrix[$key][$k]['scale'];
                    if (isset($spliced_matrix[$key-1][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix[$key-1][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix[$key-1][$bridge_kplus]['phase_color'] = $spliced_matrix[$key][$k]['phase_color'];
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

  foreach ($spliced_matrix_reversed as $key=>$spliced_row) {
    $count = 1;
    foreach ($spliced_row as $k=>$item) {
      if (isset($spliced_matrix_reversed[$key][$k+1]['tone'])) {
        if ($spliced_matrix_reversed[$key][$k+1]['tone'] == $spliced_matrix_reversed[$key][$k]['tone']) {
          $spliced_matrix_reversed[$key][$k]['wave_limit'] = 'active';
          $spliced_matrix_reversed[$key][$k+1]['pole_shift'] = 2;
          $spliced_matrix_reversed[$key][$k]['pole_shift'] = 1;
          if ($count == 1) {
            $spliced_matrix[$key][$k]['first'] = 1;
          }
          $count++;
        }
        foreach ($prime_series_reversed as $position=>$note) {
          if ($item['tone'] == $note) {
            // We don't want to start at zero.
            $spliced_matrix_reversed[$key][$k]['scale_position'] = $position+1;
          }
        }
      }
      if (isset($spliced_matrix_reversed[$key][$k-1]['tone'])) {
        if ($spliced_matrix_reversed[$key][$k-1]['tone'] == $spliced_matrix_reversed[$key][$k]['tone']) {
          $count++;
          $spliced_matrix_reversed[$key][$k]['wave_limit'] = 'active';
          $spliced_matrix_reversed[$key][$k-1]['pole_shift'] = 1;
          $spliced_matrix_reversed[$key][$k]['pole_shift'] = 2;
        }
        foreach ($prime_series_reversed as $position=>$note) {
          if ($item['tone'] == $note) {
            // We don't want to start at zero.
            $spliced_matrix_reversed[$key][$k]['scale_position'] = $position+1;
          }
        }
      }
      if (!isset($spliced_matrix_reversed[$key][$k+1]['tone'])) {
        $bridge_kplus = ($k+1)-(2*$tone);
        if ($spliced_matrix_reversed[$key][$bridge_kplus]['tone'] == $spliced_matrix_reversed[$key][$k]['tone']) {
          $count++;
          $spliced_matrix_reversed[$key][$k]['wave_limit'] = 'active';
          $spliced_matrix_reversed[$key][$bridge_kplus]['pole_shift'] = 2;
          $spliced_matrix_reversed[$key][$k]['pole_shift'] = 1;
        }
        foreach ($prime_series_reversed as $position=>$note) {
          if ($item['tone'] == $note) {
            // We don't want to start at zero.
            $spliced_matrix_reversed[$key][$k]['scale_position'] = $position+1;
          }
        }
      }
      if (!isset($spliced_matrix_reversed[$key][$k-1]['tone'])) {
        $bridge_kminus = ($k-1)+(2*$tone);
        if ($spliced_matrix_reversed[$key][$bridge_kminus]['tone'] == $spliced_matrix_reversed[$key][$k]['tone']) {
          $count++;
          $spliced_matrix_reversed[$key][$k]['wave_limit'] = 'active';
          $spliced_matrix_reversed[$key][$bridge_kminus]['pole_shift'] = 1;
          $spliced_matrix_reversed[$key][$k]['pole_shift'] = 2;
        }
        foreach ($prime_series_reversed as $position=>$note) {
          if ($item['tone'] == $note) {
            // We don't want to start at zero.
            $spliced_matrix_reversed[$key][$k]['scale_position'] = $position+1;
          }
        }
      }
    }
  }

  foreach ($spliced_matrix_reversed as $key=>$spliced_row) {
    $count = 1;
    foreach ($spliced_row as $k=>$item) {
      if (!isset($item['scale_position'])) {
        $no_scales = 'no scales';
      }
      if (isset($item['pole_shift'])  && !isset($no_scales)) {
        if ($item['pole_shift'] == 1) {
          $spliced_matrix_reversed[$key][$k]['scale'] = $spliced_matrix_reversed[$key][$k]['scale_position'];
          $spliced_matrix_reversed[$key][$k]['phase_color'] = 'red';
          if (isset($spliced_matrix_reversed[$key][$k]['scale']) && isset($spliced_matrix_reversed[$key][$k]['scale_position'])) {
            if (isset($spliced_matrix_reversed[$key+1][$k-2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
              if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                if ($spliced_matrix_reversed[$key+1][$k-2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']+1) {
                  $spliced_matrix_reversed[$key+1][$k-2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                  $spliced_matrix_reversed[$key+1][$k-2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                }
              }
              else {
                if ($spliced_matrix_reversed[$key+1][$k-2]['scale_position'] == 1) {
                  $spliced_matrix_reversed[$key+1][$k-2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                  $spliced_matrix_reversed[$key+1][$k-2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                }
              }
            }
            if (isset($spliced_matrix_reversed[$key-1][$k-2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
              if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                if ($spliced_matrix_reversed[$key-1][$k-2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']+1) {
                  $spliced_matrix_reversed[$key-1][$k-2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                  $spliced_matrix_reversed[$key-1][$k-2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                }
              }
              else {
                if ($spliced_matrix_reversed[$key-1][$k-2]['scale_position'] == 1) {
                  $spliced_matrix_reversed[$key-1][$k-2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                  $spliced_matrix_reversed[$key-1][$k-2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                }
              }
            }
            if (!isset($spliced_matrix_reversed[$key+1][$k-2]) && isset($spliced_matrix_reversed[$key][$k]['scale']) && isset($spliced_matrix_reversed[$key][$k]['scale_position'])) {
              unset($new_key);
              unset($bridge_kplus);
              if (!isset($spliced_matrix_reversed[$key+1])) {
                $new_key = ($key+1) - $interval;
                if (!isset($spliced_matrix_reversed[$new_key][$k-2])) {
                  $bridge_kplus = (2*$tone)+($k-2);
                }
              }
              if (isset($spliced_matrix_reversed[$key+1])) {
                if (!isset($spliced_matrix_reversed[$key+1][$k-2])) {
                  $bridge_kplus = (2*$tone)+($k-2);
                }
              }
              if (isset($new_key) && !isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone && isset($spliced_matrix_reversed[$new_key][$k-2]['scale_position'])) {
                  if ($spliced_matrix_reversed[$new_key][$k-2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']+1) {
                    $spliced_matrix_reversed[$new_key][$k-2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$k-2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$new_key][$k-2]['scale_position'] == 1) {
                    $spliced_matrix_reversed[$new_key][$k-2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$k-2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              if (isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone && isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']+1) {
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == 1) {
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              if (!isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone && isset($spliced_matrix_reversed[$key+1][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix_reversed[$key+1][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']+1) {
                    $spliced_matrix_reversed[$key+1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$key+1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$key+1][$bridge_kplus]['scale_position'] == 1) {
                    $spliced_matrix_reversed[$key+1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$key+1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
            }
            if (!isset($spliced_matrix_reversed[$key-1][$k-2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
              unset($new_key);
              unset($bridge_kplus);
              if (!isset($spliced_matrix_reversed[$key-1])) {
                $new_key = ($key-1) + $interval;
                if (!isset($spliced_matrix_reversed[$new_key][$k-2])) {
                  $bridge_kplus = (2*$tone)+($k-2);
                }
              }
              if (isset($spliced_matrix_reversed[$key-1])) {
                if (!isset($spliced_matrix_reversed[$key-1][$k-2])) {
                  $bridge_kplus = (2*$tone)+($k-2);
                }
              }
              if (isset($new_key) && !isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix_reversed[$new_key][$k-2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']+1) {
                    $spliced_matrix_reversed[$new_key][$k-2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$k-2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$new_key][$k-2]['scale_position'] == 1) {
                    $spliced_matrix_reversed[$new_key][$k-2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$k-2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              if (isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']+1) {
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == 1) {
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              if (!isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] < $tone) {
                  if ($spliced_matrix_reversed[$key-1][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']+1) {
                    $spliced_matrix_reversed[$key-1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$key-1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$key-1][$bridge_kplus]['scale_position'] == 1) {
                    $spliced_matrix_reversed[$key-1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$key-1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
            }
          }
        }
        if ($item['pole_shift'] == 2 && isset($spliced_matrix_reversed[$key][$k]['scale_position'])) {
          $spliced_matrix_reversed[$key][$k]['scale'] = $spliced_matrix_reversed[$key][$k]['scale_position'];
          $spliced_matrix_reversed[$key][$k]['phase_color'] = 'green';
          if (isset($spliced_matrix_reversed[$key][$k]['scale'])) {
            if (isset($spliced_matrix_reversed[$key+1][$k+2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
              if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1) {
                if ($spliced_matrix_reversed[$key+1][$k+2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']-1) {
                  $spliced_matrix_reversed[$key+1][$k+2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                  if (isset($spliced_matrix_reversed[$key+1][$k+2]['phase_color'])) {
                    $spliced_matrix_reversed[$key+1][$k+2]['phase_color'] = 'purple';
                  }
                  else {
                    $spliced_matrix_reversed[$key+1][$k+2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              else {
                if ($spliced_matrix_reversed[$key+1][$k+2]['scale_position'] == $tone) {
                  $spliced_matrix_reversed[$key+1][$k+2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                  if (isset($spliced_matrix_reversed[$key+1][$k+2]['phase_color'])) {
                    $spliced_matrix_reversed[$key+1][$k+2]['phase_color'] = 'purple';
                  }
                  else {

                    $spliced_matrix_reversed[$key+1][$k+2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
            }
            if (isset($spliced_matrix_reversed[$key-1][$k+2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
              if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1) {
                if ($spliced_matrix_reversed[$key-1][$k+2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']-1) {
                  $spliced_matrix_reversed[$key-1][$k+2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                  if (isset($spliced_matrix_reversed[$key-1][$k+2]['phase_color'])) {
                    $spliced_matrix_reversed[$key-1][$k+2]['phase_color'] = 'purple';
                  }
                  else {
                    $spliced_matrix_reversed[$key-1][$k+2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              else {
                if ($spliced_matrix_reversed[$key-1][$k+2]['scale_position'] == $tone) {
                  $spliced_matrix_reversed[$key-1][$k+2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                  if (isset($spliced_matrix_reversed[$key-1][$k+2]['phase_color'])) {
                    $spliced_matrix_reversed[$key-1][$k+2]['phase_color'] = 'purple';
                  }
                  else {
                    $spliced_matrix_reversed[$key-1][$k+2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
            }
            if (!isset($spliced_matrix_reversed[$key+1][$k+2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
              unset($new_key);
              unset($bridge_kplus);
              if (!isset($spliced_matrix_reversed[$key+1])) {
                $new_key = ($key+1) - $interval;
                if (!isset($spliced_matrix_reversed[$new_key][$k+2])) {
                  $bridge_kplus = ($k+2)-(2*$tone);
                }
              }
              if (isset($spliced_matrix_reversed[$key+1])) {
                if (!isset($spliced_matrix_reversed[$key+1][$k+2])) {
                  $bridge_kplus = ($k+2)-(2*$tone);
                }
              }
              if (isset($bridge_kplus)) {
                if ($bridge_kplus > (2*$tone)) {
                  $bridge_kplus = (3*$tone) - $bridge_kplus;
                }
                if ($bridge_kplus < 1) {
                  $bridge_kplus = $bridge_kplus + $tone;
                }
              }
              if (isset($new_key) && !isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$new_key][$k+2]['scale_position'])) {
                  if ($spliced_matrix_reversed[$new_key][$k+2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']-1) {
                    $spliced_matrix_reversed[$new_key][$k+2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$new_key][$k+2]['phase_color'])) {
                      $spliced_matrix_reversed[$new_key][$k+2]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$new_key][$k+2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$new_key][$k+2]['scale_position'] == $tone) {
                    $spliced_matrix_reversed[$new_key][$k+2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    $spliced_matrix_reversed[$new_key][$k+2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                  }
                }
              }
              if (isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']-1) {
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $tone) {
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (!isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$key+1][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix_reversed[$key+1][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']-1) {
                    $spliced_matrix_reversed[$key+1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$key+1][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix_reversed[$key+1][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$key+1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$key+1][$bridge_kplus]['scale_position'] == $tone) {
                    $spliced_matrix_reversed[$key+1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$key+1][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix_reversed[$key+1][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$key+1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
              }
            }
            if (!isset($spliced_matrix_reversed[$key-1][$k+2]) && isset($spliced_matrix_reversed[$key][$k]['scale'])) {
              unset($new_key);
              unset($bridge_kplus);
              if (!isset($spliced_matrix_reversed[$key-1])) {
                $new_key = ($key-1) + $interval;
                if (!isset($spliced_matrix_reversed[$new_key][$k+2])) {
                  $bridge_kplus = ($k+2)-(2*$tone);
                }
              }
              if (isset($spliced_matrix_reversed[$key-1])) {
                if (!isset($spliced_matrix_reversed[$key-1][$k+2])) {
                  $bridge_kplus = ($k+2)-(2*$tone);
                }
              }
              if (isset($bridge_kplus)) {
                if ($bridge_kplus > (2*$tone)) {
                  $bridge_kplus = (3*$tone) - $bridge_kplus;
                }
                if ($bridge_kplus < 1) {
                  $bridge_kplus = $bridge_kplus + $tone;
                }
              }
              if (isset($new_key) && !isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$new_key][$k+2]['scale_position'])) {
                  if ($spliced_matrix_reversed[$new_key][$k+2]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']-1) {
                    $spliced_matrix_reversed[$new_key][$k+2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$new_key][$k+2]['phase_color'])) {
                      $spliced_matrix_reversed[$new_key][$k+2]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$new_key][$k+2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$new_key][$k+2]['scale_position'] == $tone) {
                    $spliced_matrix_reversed[$new_key][$k+2]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$new_key][$k+2]['phase_color'])) {
                      $spliced_matrix_reversed[$new_key][$k+2]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$new_key][$k+2]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']-1) {
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$new_key][$bridge_kplus]['scale_position'] == $tone) {
                    $spliced_matrix_reversed[$new_key][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$new_key][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
              }
              if (!isset($new_key) && isset($bridge_kplus)) {
                if ($spliced_matrix_reversed[$key][$k]['scale_position'] > 1 && isset($spliced_matrix_reversed[$key-1][$bridge_kplus]['scale_position'])) {
                  if ($spliced_matrix_reversed[$key-1][$bridge_kplus]['scale_position'] == $spliced_matrix_reversed[$key][$k]['scale_position']-1) {
                    $spliced_matrix_reversed[$key-1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$key-1][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix_reversed[$key-1][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$key-1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
                    }
                  }
                }
                else {
                  if ($spliced_matrix_reversed[$key-1][$bridge_kplus]['scale_position'] == $tone) {
                    $spliced_matrix_reversed[$key-1][$bridge_kplus]['scale'] = $spliced_matrix_reversed[$key][$k]['scale'];
                    if (isset($spliced_matrix_reversed[$key-1][$bridge_kplus]['phase_color'])) {
                      $spliced_matrix_reversed[$key-1][$bridge_kplus]['phase_color'] = 'purple';
                    }
                    else {
                      $spliced_matrix_reversed[$key-1][$bridge_kplus]['phase_color'] = $spliced_matrix_reversed[$key][$k]['phase_color'];
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

  $scale = array();
  $c = 0;
  foreach ($prime_matrix as $prime_row) {
    $c++;
    foreach ($prime_row as $item) {
      if ($c == 1) {
        $scale[] = $item['tone'];
      }
    }
  }
  $scaled = 'SCALE: ';
  foreach ($scale as $array => $value) {
    $scaled .= $value . ', ';
  }

  $wave_boundary_count = 1;
  $color_array = array(
      0 => 'gold',
      1 => 'darkorange',
      2 => 'salmon',
      3 => 'yellowgreen',
      4 => 'green',
      5 => 'royalblue',
      6 => 'olive',
      7 => 'blueviolet',
      8 => 'darkorange',
      9 => 'gold',
      10 => 'bluegreen',
      11 => 'darkorange',
      12 => 'salmon',
      13 => 'yellowgreen',
      14 => 'green',
      15 => 'royalblue',
      16 => 'olive',
      17 => 'dodgerblue',
      18 => 'darkorange',
      19 => 'sandybrown',
      20 => 'royalblue',
      21 => 'gold',
      22 => 'darkorange',
      23 => 'salmon',
      24 => 'yellowgreen',
      25 => 'green',
      26 => 'royalblue',
      27 => 'olive',
      28 => 'blueviolet',
      29 => 'darkorange',
      30 => 'gold',
      31 => 'bluegreen',
      32 => 'darkorange',
      33 => 'salmon',
      34 => 'yellowgreen',
      35 => 'green',
      36 => 'royalblue',
      37 => 'olive',
      38 => 'dodgerblue',
      39 => 'darkorange',
      40 => 'sandybrown',
      41 => 'royalblue',
  );
  $color_array = array_reverse($color_array);

  foreach ($spliced_matrix as $key=>$spliced_row) {
    foreach ($spliced_row as $k=>$item) {
      if (isset($item['wave_limit'])) {
        if ($item['wave_limit'] == 'active' && $item['column'] == 1) {
          $wave_boundary_count++;
          $spliced_matrix[$key][$k]['wave_limit_processed'] = 1;
          $current_position = $spliced_matrix[$key][$k];
          if ($wave_boundary_count % 2 == 0) {
            $spliced_matrix[$key][$k]['wave_vertical'] = 'top';
          } else {
            $spliced_matrix[$key][$k]['wave_vertical'] = 'bottom';
          }

          for ($i = 1; $i <= $tone; $i++) {

            $onei = $k+$i;
            $oneione = $k+$i+1;
            $oneitwo = $k+$i+2;
            $twoi = $k+(2*$i);
            $twoione = $k+(2*$i)+1;
            $twoitwo = $k+(2*$i)+2;
            $threei = $k+(3*$i);
            $threeione = $k+(3*$i)+1;
            $keyone = $key+1;
            $keytwo = $key+2;
            $keythree = $key+3;
            $nkeyone = $key-1;
            $nkeytwo = $key-2;
            $nkeythree = $key-3;

            $v_dir = 'down';

            if ($current_position['pole_shift'] == 1) {

              if (isset($spliced_matrix[$key+1][$oneione])) {
                $next_position = $spliced_matrix[$key+1][$oneione];
              }
              if (isset($spliced_matrix[$key+1][$oneitwo])) {
                $next_adjacent_position = $spliced_matrix[$key+1][$oneitwo];
              }

              $nadjacent = '';
              if (isset($next_position)) {
                if (isset($next_adjacent_position) && $next_position['tone'] == $next_adjacent_position['tone']) {
                  $next_next_position = $next_adjacent_position;
                  $nadjacent = 1;
                } else {
                  if (isset($spliced_matrix[$key+2][$twoione])) {
                    $next_next_position = $spliced_matrix[$key+2][$twoione];
                  } else {
                    if (isset($spliced_matrix[$key+2][($twoione)-(2*$tone)])) {
                      $next_next_position = $spliced_matrix[$key+2][($twoione)-(2*$tone)];
                    }
                  }
                }
              } else {
                //drupal_set_message(t('$next_position is not set.'), 'error');
              }

              if ($nadjacent != 1 && isset($next_position) && isset($next_next_position)) {
                if (isset($spliced_matrix[$key+2][$twoitwo])) {
                  $next_next_adjacent_position = $spliced_matrix[$key+2][$twoitwo];
                }

                $nnadjacent = '';
                if (isset($next_next_position['tone']) && $next_next_position['tone'] == $next_next_adjacent_position['tone']) {
                  $next_next_next_position = $next_next_adjacent_position;
                  $nnadjacent = 1;
                } else {
                  if (isset($spliced_matrix[$key+3][$threeione])) {
                    $next_next_next_position = $spliced_matrix[$key+3][$threeione];
                  } else {
                    if (isset($spliced_matrix[$key+3][($threeione)-(2*$tone)])) {
                      $next_next_next_position = $spliced_matrix[$key+3][($threeione)-(2*$tone)];
                    }
                  }
                }
              } else {
                if ($nadjacent == 1 && isset($next_position) && isset($next_next_position) && isset($spliced_matrix[$key][($twoitwo)])) {
                  $next_next_next_position = $spliced_matrix[$key][($twoitwo)];
                } else {
                  if (!isset($next_next_position['tone'])) {
                    //drupal_set_message(t('$next_next_position is not set.'), 'error');
                  }
                }
              }

              //dpm($v_dir . ' jump ' . $i . ' position tone: ' . $current_position['tone'] . '/' . $next_position['tone'] . '/' . $next_next_position['tone'] . '/' . $next_next_next_position['tone']);

              $calc_scale = array();
              $count = 1;
              foreach ($scale as $s => $svalue) {
                if ($current_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $current_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_position) && $next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_next_position['tone']) && $next_next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_next_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_next_next_position) && $next_next_next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_next_next_position['tone'];
                  $count++;
                }
              }

              foreach ($calc_scale as $y=>$scalevar) {
                if ($scalevar['scale'] == 0) {
                  if (isset($calc_scale[$y-1]['scale']) && $calc_scale[$y-1]['scale'] == $tone - 1) {
                    if (isset($calc_scale[$y-2]['scale']) && $calc_scale[$y-2]['scale'] == $tone - 2 || isset($calc_scale[$y+1]['scale']) && $calc_scale[$y+1]['scale'] == 1) {
                      $calc_scale[$y]['scale'] = $tone;
                      if (isset($calc_scale[$y+1])) {
                        $calc_scale[$y+1]['scale'] = $tone + 1;
                      }
                      if (isset($calc_scale[$y+2])) {
                        $calc_scale[$y+2]['scale'] = $tone + 2;
                      }
                    }
                  }
                  if (isset($calc_scale[$y+1]) && $calc_scale[$y+1]['scale'] == $tone - 1) {
                    if (isset($calc_scale[$y+2]['scale']) && $calc_scale[$y+2]['scale'] == $tone - 2 || isset($calc_scale[$y-1]['scale']) && $calc_scale[$y-1]['scale'] == 1) {
                      $calc_scale[$y]['scale'] = $tone;
                      if (isset($calc_scale[$y-1])) {
                        $calc_scale[$y-1]['scale'] = $tone + 1;
                      }
                      if (isset($calc_scale[$y-2])) {
                        $calc_scale[$y-2]['scale'] = $tone + 2;
                      }
                    }
                  }
                }
              }

              //dpm($v_dir . ' poleshift 1 (tone/scale): ' . $calc_scale[1]['tone'] . '/' . $calc_scale[2]['tone'] . '/' . $calc_scale[3]['tone'] . '/' . $calc_scale[4]['tone'] . ' | ' . $calc_scale[1]['scale'] . '/' . $calc_scale[2]['scale'] . '/' . $calc_scale[3]['scale'] . '/' . $calc_scale[4]['scale']);

              // The most basic use cases first.
              if (isset($calc_scale[1]) && isset($calc_scale[2]) && isset($calc_scale[3]) && isset($calc_scale[4])) {
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }

                // Now the wavelengths that are 3 high.
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                // Now the wavelengths that are 2 high.
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
              }
            }
            unset($calc_scale);
            unset($next_position);
            unset($next_next_position);
            unset($next_next_next_position);

            if ($current_position['pole_shift'] == 2) {

              if (isset($spliced_matrix[$key+1][$onei])) {
                $next_position = $spliced_matrix[$key+1][$onei];
              }
              if (isset($spliced_matrix[$key+1][$oneione])) {
                $next_adjacent_position = $spliced_matrix[$key+1][$oneione];
              }

              $nadjacent = '';
              if (isset($next_position)) {
                if (isset($next_adjacent_position) && $next_position['tone'] == $next_adjacent_position['tone']) {
                  $next_next_position = $next_adjacent_position;
                  $nadjacent = 1;
                } else {
                  if (isset($spliced_matrix[$key+2][$twoi])) {
                    $next_next_position = $spliced_matrix[$key+2][$twoi];
                  } else {
                    if (isset($spliced_matrix[$key+2][($twoi)-(2*$tone)])) {
                      $next_next_position = $spliced_matrix[$key+2][($twoi)-(2*$tone)];
                    }
                  }
                }
              } else {
                //drupal_set_message(t('$next_position is not set.'), 'error');
              }

              if ($nadjacent != 1 && isset($next_position) && isset($next_next_position)) {
                if (isset($spliced_matrix[$key+2][$twoione])) {
                  $next_next_adjacent_position = $spliced_matrix[$key+2][$twoione];
                }

                $nnadjacent = '';
                if (isset($next_next_position['tone']) && $next_next_position['tone'] == $next_next_adjacent_position['tone']) {
                  $next_next_next_position = $next_next_adjacent_position;
                  $nnadjacent = 1;
                } else {
                  if (isset($spliced_matrix[$key+3][$threei])) {
                    $next_next_next_position = $spliced_matrix[$key+3][$threei];
                  } else {
                    if (isset($spliced_matrix[$key+3][($threei)-(2*$tone)])) {
                      $next_next_next_position = $spliced_matrix[$key+3][($threei)-(2*$tone)];
                    }
                  }
                }
              } else {
                if ($nadjacent == 1 && isset($next_position) && isset($next_next_position) && isset($spliced_matrix[$key][($twoione)])) {
                  $next_next_next_position = $spliced_matrix[$key][($twoione)];
                } else {
                  if (!isset($next_next_position['tone'])) {
                    //drupal_set_message(t('$next_next_position is not set.'), 'error');
                  }
                }
              }

              //dpm($v_dir . ' jump ' . $i . ' position tone: ' . $current_position['tone'] . '/' . $next_position['tone'] . '/' . $next_next_position['tone'] . '/' . $next_next_next_position['tone']);

              $calc_scale = array();
              $count = 1;
              foreach ($scale as $s => $svalue) {
                if ($current_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $current_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_position) && $next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_next_position['tone']) && $next_next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_next_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_next_next_position) && $next_next_next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_next_next_position['tone'];
                  $count++;
                }
              }

              foreach ($calc_scale as $y=>$scalevar) {
                if ($scalevar['scale'] == 0) {
                  if (isset($calc_scale[$y-1]['scale']) && $calc_scale[$y-1]['scale'] == $tone - 1) {
                    if (isset($calc_scale[$y-2]['scale']) && $calc_scale[$y-2]['scale'] == $tone - 2 || isset($calc_scale[$y+1]['scale']) && $calc_scale[$y+1]['scale'] == 1) {
                      $calc_scale[$y]['scale'] = $tone;
                      if (isset($calc_scale[$y+1])) {
                        $calc_scale[$y+1]['scale'] = $tone + 1;
                      }
                      if (isset($calc_scale[$y+2])) {
                        $calc_scale[$y+2]['scale'] = $tone + 2;
                      }
                    }
                  }
                  if (isset($calc_scale[$y+1]) && $calc_scale[$y+1]['scale'] == $tone - 1) {
                    if (isset($calc_scale[$y+2]['scale']) && $calc_scale[$y+2]['scale'] == $tone - 2 || isset($calc_scale[$y-1]['scale']) && $calc_scale[$y-1]['scale'] == 1) {
                      $calc_scale[$y]['scale'] = $tone;
                      if (isset($calc_scale[$y-1])) {
                        $calc_scale[$y-1]['scale'] = $tone + 1;
                      }
                      if (isset($calc_scale[$y-2])) {
                        $calc_scale[$y-2]['scale'] = $tone + 2;
                      }
                    }
                  }
                }
              }

              //dpm($v_dir . ' poleshift 2 (tone/scale): ' . $calc_scale[1]['tone'] . '/' . $calc_scale[2]['tone'] . '/' . $calc_scale[3]['tone'] . '/' . $calc_scale[4]['tone'] . ' | ' . $calc_scale[1]['scale'] . '/' . $calc_scale[2]['scale'] . '/' . $calc_scale[3]['scale'] . '/' . $calc_scale[4]['scale']);

              // The most basic use cases first.
              if (isset($calc_scale[1]) && isset($calc_scale[2]) && isset($calc_scale[3]) && isset($calc_scale[4])) {
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }

                // Now the wavelengths that are 3 high.
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                // Now the wavelengths that are 2 high.
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
              }
            }
            unset($calc_scale);
            unset($next_position);
            unset($next_next_position);
            unset($next_next_next_position);

            $v_dir = 'up';
            if ($current_position['pole_shift'] == 1) {

              if (isset($spliced_matrix[$key-1][$oneione])) {
                $next_position = $spliced_matrix[$key-1][$oneione];
              }
              if (isset($spliced_matrix[$key-1][$oneitwo])) {
                $next_adjacent_position = $spliced_matrix[$key-1][$oneitwo];
              }

              $nadjacent = '';
              if (isset($next_position)) {
                if (isset($next_adjacent_position) && $next_position['tone'] == $next_adjacent_position['tone']) {
                  $next_next_position = $next_adjacent_position;
                  $nadjacent = 1;
                } else {
                  if (isset($spliced_matrix[$key-2][$twoione])) {
                    $next_next_position = $spliced_matrix[$key-2][$twoione];
                  } else {
                    if (isset($spliced_matrix[$key-2][($twoione)-(2*$tone)])) {
                      $next_next_position = $spliced_matrix[$key-2][($twoione)-(2*$tone)];
                    }
                  }
                }
              } else {
                //drupal_set_message(t('$next_position is not set.'), 'error');
              }

              if ($nadjacent != 1 && isset($next_position) && isset($next_next_position)) {
                if (isset($spliced_matrix[$key-2][$twoitwo])) {
                  $next_next_adjacent_position = $spliced_matrix[$key-2][$twoitwo];
                }

                $nnadjacent = '';
                if (isset($next_next_position['tone']) && $next_next_position['tone'] == $next_next_adjacent_position['tone']) {
                  $next_next_next_position = $next_next_adjacent_position;
                  $nnadjacent = 1;
                } else {
                  if (isset($spliced_matrix[$key-3][$threeione])) {
                    $next_next_next_position = $spliced_matrix[$key-3][$threeione];
                  } else {
                    if (isset($spliced_matrix[$key-3][($threeione)-(2*$tone)])) {
                      $next_next_next_position = $spliced_matrix[$key-3][($threeione)-(2*$tone)];
                    }
                  }
                }
              } else {
                if ($nadjacent == 1 && isset($next_position) && isset($next_next_position) && isset($spliced_matrix[$key][($twoitwo)])) {
                  $next_next_next_position = $spliced_matrix[$key][($twoitwo)];
                } else {
                  if (!isset($next_next_position['tone'])) {
                    //drupal_set_message(t('$next_next_position is not set.'), 'error');
                  }
                }
              }

              //dpm($v_dir . ' jump ' . $i . ' position tone: ' . $current_position['tone'] . '/' . $next_position['tone'] . '/' . $next_next_position['tone'] . '/' . $next_next_next_position['tone']);

              $calc_scale = array();
              $count = 1;
              foreach ($scale as $s => $svalue) {
                if ($current_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $current_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_position) && $next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_next_position['tone']) && $next_next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_next_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_next_next_position) && $next_next_next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_next_next_position['tone'];
                  $count++;
                }
              }

              //dpm('1 ' . $v_dir . ' poleshift 1 (tone/scale): ' . $calc_scale[1]['tone'] . '/' . $calc_scale[2]['tone'] . '/' . $calc_scale[3]['tone'] . '/' . $calc_scale[4]['tone'] . ' | ' . $calc_scale[1]['scale'] . '/' . $calc_scale[2]['scale'] . '/' . $calc_scale[3]['scale'] . '/' . $calc_scale[4]['scale']);

              foreach ($calc_scale as $y=>$scalevar) {
                if ($scalevar['scale'] == 0) {
                  if (isset($calc_scale[$y-1]['scale']) && $calc_scale[$y-1]['scale'] == $tone - 1) {
                    if (isset($calc_scale[$y-2]['scale']) && $calc_scale[$y-2]['scale'] == $tone - 2 || isset($calc_scale[$y+1]['scale']) && $calc_scale[$y+1]['scale'] == 1) {
                      $calc_scale[$y]['scale'] = $tone;
                      if (isset($calc_scale[$y+1])) {
                        $calc_scale[$y+1]['scale'] = $tone + 1;
                      }
                      if (isset($calc_scale[$y+2])) {
                        $calc_scale[$y+2]['scale'] = $tone + 2;
                      }
                    }
                  }
                  if (isset($calc_scale[$y+1]) && $calc_scale[$y+1]['scale'] == $tone - 1) {
                    if (isset($calc_scale[$y+2]['scale']) && $calc_scale[$y+2]['scale'] == $tone - 2 || isset($calc_scale[$y-1]['scale']) && $calc_scale[$y-1]['scale'] == 1) {
                      $calc_scale[$y]['scale'] = $tone;
                      if (isset($calc_scale[$y-1])) {
                        $calc_scale[$y-1]['scale'] = $tone + 1;
                      }
                      if (isset($calc_scale[$y-2])) {
                        $calc_scale[$y-2]['scale'] = $tone + 2;
                      }
                    }
                  }
                }
              }

              //dpm('2 ' . $v_dir . ' poleshift 1 (tone/scale): ' . $calc_scale[1]['tone'] . '/' . $calc_scale[2]['tone'] . '/' . $calc_scale[3]['tone'] . '/' . $calc_scale[4]['tone'] . ' | ' . $calc_scale[1]['scale'] . '/' . $calc_scale[2]['scale'] . '/' . $calc_scale[3]['scale'] . '/' . $calc_scale[4]['scale']);

              // The most basic use cases first.
              if (isset($calc_scale[1]) && isset($calc_scale[2]) && isset($calc_scale[3]) && isset($calc_scale[4])) {
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }

                // Now the wavelengths that are 3 high.
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                // Now the wavelengths that are 2 high.
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
              }
            }
            unset($calc_scale);
            unset($next_position);
            unset($next_next_position);
            unset($next_next_next_position);

            if ($current_position['pole_shift'] == 2) {

              if (isset($spliced_matrix[$key-1][$onei])) {
                $next_position = $spliced_matrix[$key-1][$onei];
              }
              if (isset($spliced_matrix[$key-1][$oneione])) {
                $next_adjacent_position = $spliced_matrix[$key-1][$oneione];
              }

              $nadjacent = '';
              if (isset($next_position)) {
                if (isset($next_adjacent_position) && $next_position['tone'] == $next_adjacent_position['tone']) {
                  $next_next_position = $next_adjacent_position;
                  $nadjacent = 1;
                } else {
                  if (isset($spliced_matrix[$key-2][$twoi])) {
                    $next_next_position = $spliced_matrix[$key-2][$twoi];
                  } else {
                    if (isset($spliced_matrix[$key-2][($twoi)-(2*$tone)])) {
                      $next_next_position = $spliced_matrix[$key-2][($twoi)-(2*$tone)];
                    }
                  }
                }
              } else {
                //drupal_set_message(t('$next_position is not set.'), 'error');
              }

              if ($nadjacent != 1 && isset($next_position) && isset($next_next_position)) {
                if (isset($spliced_matrix[$key-2][$twoione])) {
                  $next_next_adjacent_position = $spliced_matrix[$key-2][$twoione];
                }

                $nnadjacent = '';
                if (isset($next_next_position['tone']) && $next_next_position['tone'] == $next_next_adjacent_position['tone']) {
                  $next_next_next_position = $next_next_adjacent_position;
                  $nnadjacent = 1;
                } else {
                  if (isset($spliced_matrix[$key-3][$threei])) {
                    $next_next_next_position = $spliced_matrix[$key-3][$threei];
                  } else {
                    if (isset($spliced_matrix[$key-3][($threei)-(2*$tone)])) {
                      $next_next_next_position = $spliced_matrix[$key-3][($threei)-(2*$tone)];
                    }
                  }
                }
              } else {
                if ($nadjacent == 1 && isset($next_position) && isset($next_next_position) && isset($spliced_matrix[$key][($twoione)])) {
                  $next_next_next_position = $spliced_matrix[$key][($twoione)];
                } else {
                  if (!isset($next_next_position['tone'])) {
                    //drupal_set_message(t('$next_next_position is not set.'), 'error');
                  }
                }
              }

              //dpm($v_dir . ' jump ' . $i . ' position tone: ' . $current_position['tone'] . '/' . $next_position['tone'] . '/' . $next_next_position['tone'] . '/' . $next_next_next_position['tone']);

              $calc_scale = array();
              $count = 1;
              foreach ($scale as $s => $svalue) {
                if ($current_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $current_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_position) && $next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_next_position['tone']) && $next_next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_next_position['tone'];
                  $count++;
                }
              }
              foreach ($scale as $s => $svalue) {
                if (isset($next_next_next_position) && $next_next_next_position['tone'] == $svalue) {
                  $calc_scale[$count]['scale'] = $s;
                  $calc_scale[$count]['tone'] = $next_next_next_position['tone'];
                  $count++;
                }
              }

              foreach ($calc_scale as $y=>$scalevar) {
                if ($scalevar['scale'] == 0) {
                  if (isset($calc_scale[$y-1]['scale']) && $calc_scale[$y-1]['scale'] == $tone - 1) {
                    if (isset($calc_scale[$y-2]['scale']) && $calc_scale[$y-2]['scale'] == $tone - 2 || isset($calc_scale[$y+1]['scale']) && $calc_scale[$y+1]['scale'] == 1) {
                      $calc_scale[$y]['scale'] = $tone;
                      if (isset($calc_scale[$y+1])) {
                        $calc_scale[$y+1]['scale'] = $tone + 1;
                      }
                      if (isset($calc_scale[$y+2])) {
                        $calc_scale[$y+2]['scale'] = $tone + 2;
                      }
                    }
                  }
                  if (isset($calc_scale[$y+1]) && $calc_scale[$y+1]['scale'] == $tone - 1) {
                    if (isset($calc_scale[$y+2]['scale']) && $calc_scale[$y+2]['scale'] == $tone - 2 || isset($calc_scale[$y-1]['scale']) && $calc_scale[$y-1]['scale'] == 1) {
                      $calc_scale[$y]['scale'] = $tone;
                      if (isset($calc_scale[$y-1])) {
                        $calc_scale[$y-1]['scale'] = $tone + 1;
                      }
                      if (isset($calc_scale[$y-2])) {
                        $calc_scale[$y-2]['scale'] = $tone + 2;
                      }
                    }
                  }
                }
              }

              //dpm($v_dir . ' poleshift 2 (tone/scale): ' . $calc_scale[1]['tone'] . '/' . $calc_scale[2]['tone'] . '/' . $calc_scale[3]['tone'] . '/' . $calc_scale[4]['tone'] . ' | ' . $calc_scale[1]['scale'] . '/' . $calc_scale[2]['scale'] . '/' . $calc_scale[3]['scale'] . '/' . $calc_scale[4]['scale']);

              // The most basic use cases first.
              if (isset($calc_scale[1]) && isset($calc_scale[2]) && isset($calc_scale[3]) && isset($calc_scale[4])) {
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }

                // Now the wavelengths that are 3 high.
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                // Now the wavelengths that are 2 high.
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] - 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] + 1) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] - 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] && $calc_scale[3]['scale'] == $calc_scale[4]['scale'] - 1) {
                  $h_dir = 'forward';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
                if ($calc_scale[1]['scale'] == $calc_scale[2]['scale'] + 1 && $calc_scale[2]['scale'] == $calc_scale[3]['scale'] + 1 && $calc_scale[3]['scale'] == $calc_scale[4]['scale']) {
                  $h_dir = 'reversed';
                  $tempcolor = array_pop($color_array);
                  $scale_increments[] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                  $spliced_matrix[$key][$k]['wave'][] = $calc_scale[1]['tone'] . ':' . $i . ':' . $v_dir . ':' . $h_dir . ':' . $tempcolor;
                }
              }
            }
            unset($calc_scale);
            unset($next_position);
            unset($next_next_position);
            unset($next_next_next_position);
          }
        }
      }
    }
  }

  // Find wave height;
  $wave_height = 0;
  $midwave = 0;
  $wavecount = 0;
  foreach ($spliced_matrix as $key=>$spliced_row) {
    foreach ($spliced_row as $k=>$item) {
      if ($wavecount == 0 && $midwave == 0 && $spliced_matrix[$key][$k]['column'] == 1 && isset($spliced_matrix[$key][$k]['wave_vertical']) && $spliced_matrix[$key][$k]['wave_vertical'] == 'top') {
        $wave_height++;
        $midwave = 1;
        $wavecount++;
      }
      elseif ($wavecount < 2 && $midwave == 1 && $spliced_matrix[$key][$k]['column'] == 1 &&  isset($spliced_matrix[$key][$k]['wave_vertical']) && $spliced_matrix[$key][$k]['wave_vertical'] == 'bottom' && $wave_height > 1) {
        $wave_height++;
        $midwave = 0;
      }
      elseif ($wavecount < 2 && $midwave == 1 && $spliced_matrix[$key][$k]['column'] == 1 && !isset($spliced_matrix[$key][$k]['wave_vertical'])) {
        $wave_height++;
      }
    }
  }

  foreach ($spliced_matrix as $key=>$spliced_row) {
    foreach ($spliced_row as $k=>$item) {
      if (isset($spliced_matrix[$key][$k]['wave_limit_processed']) && $spliced_matrix[$key][$k]['wave_limit_processed'] == 1 && $spliced_matrix[$key][$k]['column'] == 1) {
        if (isset($scale_increments)) {
          foreach ($scale_increments as $i => $increment) {
            //dpm($increment);
            $explode = explode(':', $increment);
            $jump = $explode[1];
            $direction = $explode[2];
            $scale_direction = $explode[3];
            $color = $explode[4];
            $c = 0;

            if (isset($spliced_matrix[$key][$k]['wave'])/* odd waveform && $jump %2 == 0*/) {
              foreach ($spliced_matrix[$key][$k]['wave'] as $w => $wave) {
                if (isset($scale_increments[$i]) && $wave == $increment) {
                  if ($direction == 'down' && $c == 0) {
                    if ($item['pole_shift'] == 1) {
                      $current_position_key = $key;
                      $current_position_k = $k+1;
                      $spliced_matrix[$key][$k]['yellow'] = 'red';
                      $spliced_matrix[$key][$k]['rhythm'][$jump] = $jump;

                      $spliced_matrix[$key][$k]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                      $spliced_matrix[$key][$k+1]['yellow'] = $color;
                      $spliced_matrix[$key][$k+1]['rhythm'][$jump] = $jump;

                      $spliced_matrix[$key][$k+1]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                      $spliced_matrix_reversed[$key][$k]['yellow'] = 'red';
                      $spliced_matrix_reversed[$key][$k]['rhythm'][$jump] = $jump;

                      $spliced_matrix_reversed[$key][$k]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                      $spliced_matrix_reversed[$key][$k+1]['yellow'] = $color;
                      $spliced_matrix_reversed[$key][$k+1]['rhythm'][$jump] = $jump;

                      $spliced_matrix_reversed[$key][$k+1]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                    }
                    if ($item['pole_shift'] == 2) {
                      $current_position_key = $key;
                      $current_position_k = $k;
                      $spliced_matrix[$key][$k]['yellow'] = 'red';
                      $spliced_matrix[$key][$k]['rhythm'][$jump] = $jump;

                      $spliced_matrix[$key][$k]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                      $spliced_matrix_reversed[$key][$k]['yellow'] = 'red';
                      $spliced_matrix_reversed[$key][$k]['rhythm'][$jump] = $jump;

                      $spliced_matrix_reversed[$key][$k]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                    }
                    if (isset($current_position_k) && isset($current_position_key)) {
                      if ($scale_direction == 'forward') {
                        $til = $tone + $tone;
                        $z = 1;
                        while ($z <= $til) {
                          if (isset($spliced_matrix[$current_position_key][$current_position_k + $jump]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] != $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                            if ($direction == 'down' && isset($spliced_matrix[$current_position_key + 1][$current_position_k + $jump])) {
                              $current_position_key = $current_position_key + 1;
                              $current_position_k = $current_position_k + $jump;
                              if (isset($current_position_k) && isset($current_position_key)) {
                                $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                              }
                            } elseif ($direction == 'up' && isset($spliced_matrix[$current_position_key - 1][$current_position_k + $jump])) {
                              $current_position_key = $current_position_key - 1;
                              $current_position_k = $current_position_k + $jump;
                              if (isset($current_position_k) && isset($current_position_key)) {
                                $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                              }
                            } else {
                              break;
                            }
                          } elseif (isset($spliced_matrix[$current_position_key][$current_position_k + 1]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] == $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                            $current_position_k = $current_position_k + 1;
                            $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                            $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                            $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                            $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
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
                        $til = $tone + $tone;
                        $z = 1;
                        while ($z <= $til) {
                          if (isset($spliced_matrix[$current_position_key][$current_position_k + $jump]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] != $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                            if ($direction == 'down' && isset($spliced_matrix[$current_position_key + 1][$current_position_k + $jump])) {
                              $current_position_key = $current_position_key + 1;
                              $current_position_k = $current_position_k + $jump;
                              if (isset($current_position_k) && isset($current_position_key)) {
                                $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                              }
                            } elseif ($direction == 'up' && isset($spliced_matrix[$current_position_key - 1][$current_position_k + $jump])) {
                              $current_position_key = $current_position_key - 1;
                              $current_position_k = $current_position_k + $jump;
                              if (isset($current_position_k) && isset($current_position_key)) {
                                $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                              }
                            } else {
                              break;
                            }
                          } elseif (isset($spliced_matrix[$current_position_key][$current_position_k + 1]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] == $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                            $current_position_k = $current_position_k + 1;
                            $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                            $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                            $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                            $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
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
                    $c++;
                  }
                  unset($current_position_k);
                  unset($current_position_key);
                  if ($direction == 'up' && $c == 0) {
                    if ($item['pole_shift'] == 1) {
                      $current_position_key = $key;
                      $current_position_k = $k+1;
                      $spliced_matrix[$key][$k]['yellow'] = 'red';
                      $spliced_matrix[$key][$k]['rhythm'][$jump] = $jump;

                      $spliced_matrix[$key][$k]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                      $spliced_matrix[$key][$k+1]['yellow'] = $color;
                      $spliced_matrix[$key][$k+1]['rhythm'][$jump] = $jump;

                      $spliced_matrix[$key][$k+1]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                      $spliced_matrix_reversed[$key][$k]['yellow'] = 'red';
                      $spliced_matrix_reversed[$key][$k]['rhythm'][$jump] = $jump;

                      $spliced_matrix_reversed[$key][$k]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                      $spliced_matrix_reversed[$key][$k+1]['yellow'] = $color;
                      $spliced_matrix_reversed[$key][$k+1]['rhythm'][$jump] = $jump;

                      $spliced_matrix_reversed[$key][$k+1]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                    }
                    if ($item['pole_shift'] == 2) {
                      $current_position_key = $key;
                      $current_position_k = $k;
                      $spliced_matrix[$key][$k]['yellow'] = 'red';
                      $spliced_matrix[$key][$k]['rhythm'][$jump] = $jump;

                      $spliced_matrix[$key][$k]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));

                      $spliced_matrix_reversed[$key][$k]['yellow'] = 'red';
                      $spliced_matrix_reversed[$key][$k]['rhythm'][$jump] = $jump;

                      $spliced_matrix_reversed[$key][$k]['wavelength_even'][$jump] = 1+($jump*($wave_height-1));


                    }
                    if (isset($current_position_k) && isset($current_position_key)) {
                      if ($scale_direction == 'forward') {
                        $til = $tone + $tone;
                        $z = 1;
                        while ($z <= $til) {
                          if (isset($spliced_matrix[$current_position_key][$current_position_k + $jump]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] != $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                            if ($direction == 'down' && isset($spliced_matrix[$current_position_key + 1][$current_position_k + $jump])) {
                              $current_position_key = $current_position_key + 1;
                              $current_position_k = $current_position_k + $jump;
                              if (isset($current_position_k) && isset($current_position_key)) {
                                $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                              }
                            } elseif ($direction == 'up' && isset($spliced_matrix[$current_position_key - 1][$current_position_k + $jump])) {
                              $current_position_key = $current_position_key - 1;
                              $current_position_k = $current_position_k + $jump;
                              if (isset($current_position_k) && isset($current_position_key)) {
                                $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                              }
                            } else {
                              break;
                            }
                          } elseif (isset($spliced_matrix[$current_position_key][$current_position_k + 1]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] == $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                            $current_position_k = $current_position_k + 1;
                            $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                            $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                            $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                            $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
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
                        $til = $tone + $tone;
                        $z = 1;
                        while ($z <= $til) {
                          if (isset($spliced_matrix[$current_position_key][$current_position_k + $jump]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] != $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                            if ($direction == 'down' && isset($spliced_matrix[$current_position_key + 1][$current_position_k + $jump])) {
                              $current_position_key = $current_position_key + 1;
                              $current_position_k = $current_position_k + $jump;
                              if (isset($current_position_k) && isset($current_position_key)) {
                                $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                              }
                            } elseif ($direction == 'up' && isset($spliced_matrix[$current_position_key - 1][$current_position_k + $jump])) {
                              $current_position_key = $current_position_key - 1;
                              $current_position_k = $current_position_k + $jump;
                              if (isset($current_position_k) && isset($current_position_key)) {
                                $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                                $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                                $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                              }
                            } else {
                              break;
                            }
                          } elseif (isset($spliced_matrix[$current_position_key][$current_position_k + 1]) && $spliced_matrix[$current_position_key][$current_position_k]['tone'] == $spliced_matrix[$current_position_key][$current_position_k + 1]['tone']) {
                            $current_position_k = $current_position_k + 1;
                            $spliced_matrix[$current_position_key][$current_position_k]['yellow'] = $color;
                            $spliced_matrix_reversed[$current_position_key][$current_position_k]['yellow'] = $color;
                            $spliced_matrix[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
                            $spliced_matrix_reversed[$current_position_key][$current_position_k]['rhythm'][$jump] = $jump;
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
                    $c++;
                  }
                }
              }
            }
            unset($jump);
            unset($direction);
            unset($scale_direction);
            unset($color);
            unset($c);
            unset($explode);
          }
        }
      }
    }
  }

  foreach ($spliced_matrix as $key=>$spliced_row) {
    foreach ($spliced_row as $k=>$item) {
      if (isset($spliced_matrix[$key][$k]['wavelength_even'])) {
        foreach ($spliced_matrix[$key][$k]['wavelength_even'] as $jump=>$value) {
          $wave_interactions[$spliced_matrix[$key][$k]['tone']][$jump]['wavelength'] = $value;
        }
      }
      if (isset($spliced_matrix[$key][$k]['wavelength_odd'])) {
        foreach ($spliced_matrix[$key][$k]['wavelength_odd'] as $jump=>$value) {
          $wave_interactions[$spliced_matrix[$key][$k]['tone']][$jump]['wavelength'] = $value;
        }
      }
    }
  }

  if (isset($wave_interactions)) {

    $phi = 1/1.618;
    $wavelength_calculation = '<div class="endtable"></div><div class="begintext"><div class="wavelength-calculation"><p>';

    $wavelengths = array();
    foreach ($wave_interactions as $tone=>$jumps) {
      $count = 0;
      $wavelengths_added = 0;
      $wavelengths_mult = 1;
      foreach ($jumps as $jump=>$wavelength) {
        if ($jump %2 == 0) {
          $count++;
          $wavelength_old = $wavelengths_added;
          $wavelengths_added = $wavelength_old+$wavelength['wavelength'];
          $wavelength_old = $wavelengths_mult;
          $wavelengths_mult = $wavelength_old*$wavelength['wavelength'];
          $wavelengths[$tone][$wavelength['wavelength']]['jump'] = $jump;
        }
      }
      $ocount = 0;
      $owavelengths_added = 0;
      $owavelengths_mult = 1;
      foreach ($jumps as $jump=>$owavelength) {
        if ($jump %2 != 0) {
          $ocount++;
          $owavelength_old = $owavelengths_added;
          $owavelengths_added = $owavelength_old+$owavelength['wavelength'];
          $owavelength_old = $owavelengths_mult;
          $owavelengths_mult = $owavelength_old*$owavelength['wavelength'];
          $owavelengths[$tone][$owavelength['wavelength']]['jump'] = $jump;
        }
      }
      $wavelengths_phi = ($phi*$wavelengths_mult)/$wavelengths_added;
      if ($owavelengths_added > 0) {
        $owavelengths_phi = ($phi*$owavelengths_mult)/$owavelengths_added;
      }

      $wavelength_calculation .= '<strong>EVEN Tone ' . $tone . '</strong> with <strong>' . $count . '</strong> wavelength/s counted.';
      foreach ($wavelengths[$tone] as $w=>$value) {
        $wavelength_calculation .= '<br/>Half-wavelength for <strong>rhythm ' . $value['jump'] . '</strong> is ' . $w . '.';
      }
      $wavelength_calculation .= '</strong><br/>phi(wavelengths multiplied)/wavelengths added = ' . $phi . '*' . $wavelengths_mult . '/' . $wavelengths_added . ' = <strong>' . $wavelengths_phi . '</strong></br>';

      if ($owavelengths_added > 0) {
        $wavelength_calculation .= '<strong>ODD Tone ' . $tone . '</strong> with <strong>' . $count . '</strong> wavelength/s counted.';
        foreach ($owavelengths[$tone] as $ow=>$value) {
          $wavelength_calculation .= '<br/>Half-wavelength for <strong>rhythm ' . $value['jump'] . '</strong> is ' . $ow . '.';
        }
        $wavelength_calculation .= '</strong><br/>phi(wavelengths multiplied)/wavelengths added = ' . $phi . '*' . $owavelengths_mult . '/' . $owavelengths_added . ' = <strong>' . $owavelengths_phi . '</strong></br>';
      }
      // Retrieve an array which contains the path pieces.
      $current_path = \Drupal::service('path.current')->getPath();
      $path_args = explode('/', $current_path);
      if ($path_args[3] == 20 && $path_args[2] == 13) {
        $wavelengths_phi = (.615*$wavelengths_mult)/$wavelengths_added;
        $wavelength_calculation .= '<strong>VENUS Calculation</strong> with <strong>' . $count . '</strong> wavelength/s counted.';
        foreach ($wavelengths[$tone] as $w=>$value) {
          $wavelength_calculation .= '<br/>Half-wavelength for <strong>rhythm ' . $value['jump'] . '</strong> is ' . $w . '.';
        }
        $wavelength_calculation .= '</strong><br/>venus ratio = orbit of venus / orbit of earth = .615<br/> venus ratio(wavelengths multiplied)/wavelengths added = .615*' . $wavelengths_mult . '/' . $wavelengths_added . ' = <strong>' . $wavelengths_phi . '</strong></br>';
      }
    }
    $wavelength_calculation .= '</p></div></div>';
  }

  if (isset($no_scales)) {
  }
  if (isset($scale_increments) && isset($wavelength_calculation)) {
    $wave_detection = array('spliced_matrix' => $spliced_matrix, 'spliced_matrix_reversed' => $spliced_matrix_reversed, 'scale' => $scale, 'scaled' => $scaled, 'scale_increments' => $scale_increments,);
  }
  else {
    $wave_detection = array('spliced_matrix' => $spliced_matrix, 'spliced_matrix_reversed' => $spliced_matrix_reversed, 'scale' => $scale, 'scaled' => $scaled);
  }
  if (isset($wavelength_calculation)) {
    $wave_detection['wavelength_calculation'] = $wavelength_calculation;
  }
  return $wave_detection;

}

function jellomatrix_increments_derivative($spliced_matrix, $tone) {
  // Now we find the increments for the DERIVATIVES.
  $increments = array();
  // Same row, forward direction.
  foreach ($spliced_matrix as $row=>$spliced_row) {
    foreach ($spliced_row as $column=>$item) {
      $neighbor_tone = 0;
      $item_tone = $item['tone'];
      $c = $item['column'];
      $c1 = $c+1;
      $r = $item['row'];
      $r1 = $r;

      if (isset($spliced_matrix[$r1][$c1]['tone'])) {
        $neighbor_tone = $spliced_matrix[$r1][$c1]['tone'];
        if ($item_tone >= $neighbor_tone) {
          $increments['row']['forward'][$row][$column] = $item_tone-$neighbor_tone;
        }
        if ($neighbor_tone > $item_tone) {
          $increments['row']['forward'][$row][$column] = $tone+$item_tone - $neighbor_tone;
        }
      }
    }
  }
  if (isset($increments['row']['forward'])) {
    foreach ($increments['row']['forward'] as $row=>$spliced_row) {
      foreach ($spliced_row as $column=>$item) {
        $neighbor_tone = 0;
        $item_tone = $item;
        $c = $column;
        $c1 = $column+1;
        $r = $row;
        $r1 = $r;

        if (isset($increments['row']['forward'][$r1][$c1])) {
          $neighbor_tone = $increments['row']['forward'][$r1][$c1];
          if ($item_tone >= $neighbor_tone) {
            $increments['row']['derivative'][$row][$column] = $item_tone-$neighbor_tone;
          }
          if ($neighbor_tone > $item_tone) {
            $increments['row']['derivative'][$row][$column] = $tone+$item_tone - $neighbor_tone;
          }
        }
      }
    }
  }
  if (isset($increments['row']['derivative'])) {
    foreach ($increments['row']['derivative'] as $row=>$spliced_row) {
      foreach ($spliced_row as $column=>$item) {
        $neighbor_tone = 0;
        $item_tone = $item;
        $c = $column;
        $c1 = $column+1;
        $r = $row;
        $r1 = $r;

        if (isset($increments['row']['derivative'][$r1][$c1])) {
          $neighbor_tone = $increments['row']['derivative'][$r1][$c1];
          if ($item_tone >= $neighbor_tone) {
            $increments['row']['derivative_2'][$row][$column] = $item_tone-$neighbor_tone;
          }
          if ($neighbor_tone > $item_tone) {
            $increments['row']['derivative_2'][$row][$column] = $tone+$item_tone - $neighbor_tone;
          }
        }
      }
    }
  }
  return $increments;
}


function jellomatrix_increments_original($spliced_matrix, $tone) {
  // And now we want to start calculating the sums of each of the rows
  // using their grid_x and grid_y values and using a counting system
  // based on the $tone value.  Oh yeah, WITHOUT a database...
  $increment_original = array();
  $count = 1;
  foreach ($spliced_matrix as $row) {
    foreach ($row as $item) {
      $tt = 0;
      $it = $item['tone'];
      $c = $item['grid_x'];
      $c1 = $c+1;
      $r = $item['grid_y'];
      $r1 = $r;
      if (isset($spliced_matrix[$r1][$c1]['tone'])) {
        $tt = $spliced_matrix[$r1][$c1]['tone'];
      }
      if ($tt>0 && $it>0) {
        if ($tt >= $it) {
          $increment_original['row']['forward'][$count][] = $tt-$it;
        }
        elseif ($it > $tt) {
          $increment_original['row']['forward'][$count][] = $tone+$tt-$it;
        }
      }
    }
    $count++;
  }
  $count = 1;
  foreach ($spliced_matrix as $row) {
    foreach ($row as $item) {
      $tt = 0;
      $it = $item['tone'];
      $c = $item['grid_x'];
      $c1 = $c-1;
      $r = $item['grid_y'];
      $r1 = $r;
      if (isset($spliced_matrix[$r1][$c1]['tone'])) {
        $tt = $spliced_matrix[$r1][$c1]['tone'];
      }
      if ($tt>0 && $it>0) {
        if ($it <= $tt && isset($tt) && isset($tt)) {
          $increment_original['row']['backward'][$count][] = $tt-$it;
        }
        elseif ($tt < $it) {
          $increment_original['row']['backward'][$count][] = $tone+$tt-$it;
        }
      }
    }
    $count++;
  }

  // And now we want to start calculating the sums of each of the diagonals
  // using their grid_x and grid_y values and using a counting system
  // based on the $tone value.  Oh yeah, WITHOUT a database...
  $count = 1;
  foreach ($spliced_matrix as $row) {
    foreach ($row as $item) {
      $tt = 0;
      $it = $item['tone'];
      $c = $item['grid_x'];
      $c1 = $c+1;
      $r = $item['grid_y'];
      $r1 = $r;
      $r1 = $r+1;
      if (isset($spliced_matrix[$r1][$c1]['tone'])) {
        $tt = $spliced_matrix[$r1][$c1]['tone'];
      }
      if ($tt>0 && $it>0) {
        if ($tt >= $it) {
          $increment_original['lrdiag']['forward'][$count][] = $tt - $it;
        }
        elseif ($it > $tt) {
          $increment_original['lrdiag']['forward'][$count][] = $tone+$tt-$it;
        }
      }
    }
    $count++;
  }
  $count = 1;
  foreach ($spliced_matrix as $row) {
    foreach ($row as $item) {
      $tt = 0;
      $it = $item['tone'];
      $c = $item['grid_x'];
      $c1 = $c-1;
      $r = $item['grid_y'];
      $r1 = $r-1;
      if (isset($spliced_matrix[$r1][$c1]['tone'])) {
        $tt = $spliced_matrix[$r1][$c1]['tone'];
      }
      if ($tt>0 && $it>0) {
        if ($it <= $tt && isset($tt)) {
          $increment_original['lrdiag']['backward'][$count][] = $tt-$it;
        }
        elseif ($tt < $it) {
          $increment_original['lrdiag']['backward'][$count][] = $tone+$tt-$it;
        }
      }
    }
    $count++;
  }
  $count = 1;
  foreach ($spliced_matrix as $row) {
    foreach ($row as $item) {
      $tt = 0;
      $it = $item['tone'];
      $c = $item['grid_x'];
      $c1 = $c+1;
      $r = $item['grid_y'];
      $r1 = $r-1;
      if (isset($spliced_matrix[$r1][$c1]['tone'])) {
        $tt = $spliced_matrix[$r1][$c1]['tone'];
      }
      if ($tt>0 && $it>0) {
        if ($tt >= $it) {
          $increment_original['rldiag']['forward'][$count][] = $tt-$it;
        }
        elseif ($it > $tt) {
          $increment_original['rldiag']['forward'][$count][] = $tone+$tt-$it;
        }
      }
    }
    $count++;
  }
  $count = 1;
  foreach ($spliced_matrix as $row) {
    $c = 1;
    foreach ($row as $item) {
      $tt = 0;
      $it = $item['tone'];
      $c = $item['grid_x'];
      $c1 = $c-1;
      $r = $item['grid_y'];
      $r1 = $r+1;
      if (isset($spliced_matrix[$r1][$c1]['tone'])) {
        $tt = $spliced_matrix[$r1][$c1]['tone'];
      }
      if ($tt>0 && $it>0) {
        if ($tt >= $it) {
          $increment_original['rldiag']['backward'][$count][] = $tt-$it;
        }
        elseif ($tt < $it) {
          $increment_original['rldiag']['backward'][$count][] = $tone+$tt-$it;
        }
      }
    }
    $count++;
  }
  return $increment_original;
}

function jellomatrix_increments_prime_derivative($prime_matrix, $tone) {
  // Now we find the increments for the DERIVATIVES.
  $increments_prime = array();
  // Same row, forward direction.
  foreach ($prime_matrix as $row=>$prime_row) {
    foreach ($prime_row as $column=>$item) {
      $neighbor_tone = 0;
      $item_tone = $item['tone'];
      $c = $item['column'];
      $c1 = $c+1;
      $r = $item['row'];
      $r1 = $r;

      if (isset($prime_matrix[$r1][$c1]['tone'])) {
        $neighbor_tone = $prime_matrix[$r1][$c1]['tone'];
        if ($item_tone >= $neighbor_tone) {
          $increments_prime['row']['forward'][$row][$column] = $item_tone-$neighbor_tone;
        }
        if ($neighbor_tone > $item_tone) {
          $increments_prime['row']['forward'][$row][$column] = $tone+$item_tone - $neighbor_tone;
        }
      }
    }
  }
  if (isset($increments['row']['forward'])) {
    foreach ($increments_prime['row']['forward'] as $row=>$prime_row) {
      foreach ($prime_row as $column=>$item) {
        $neighbor_tone = 0;
        $item_tone = $item;
        $c = $column;
        $c1 = $column+1;
        $r = $row;
        $r1 = $r;

        if (isset($increments_prime['row']['forward'][$r1][$c1])) {
          $neighbor_tone = $increments_prime['row']['forward'][$r1][$c1];
          if ($item_tone >= $neighbor_tone) {
            $increments_prime['row']['derivative'][$row][$column] = $item_tone-$neighbor_tone;
          }
          if ($neighbor_tone > $item_tone) {
            $increments_prime['row']['derivative'][$row][$column] = $tone+$item_tone - $neighbor_tone;
          }
        }
      }
    }
  }
  if (isset($increments['row']['derivative'])) {
    foreach ($increments_prime['row']['derivative'] as $row=>$prime_row) {
      foreach ($prime_row as $column=>$item) {
        $neighbor_tone = 0;
        $item_tone = $item;
        $c = $column;
        $c1 = $column+1;
        $r = $row;
        $r1 = $r;

        if (isset($increments_prime['row']['derivative'][$r1][$c1])) {
          $neighbor_tone = $increments_prime['row']['derivative'][$r1][$c1];
          if ($item_tone >= $neighbor_tone) {
            $increments_prime['row']['derivative_2'][$row][$column] = $item_tone-$neighbor_tone;
          }
          if ($neighbor_tone > $item_tone) {
            $increments_prime['row']['derivative_2'][$row][$column] = $tone+$item_tone - $neighbor_tone;
          }
        }
      }
    }
  }
  return $increments_prime;
}


function jellomatrix_harmonics() {
  // Now the harmonics.
  $harmonics = array();
  $harmonics[] = '1:1:C';
  $harmonics[] = '1:2:C';
  $harmonics[] = '1:3:F';
  $harmonics[] = '1:4:C';
  $harmonics[] = '1:5:Ab';
  $harmonics[] = '1:6:F';
  $harmonics[] = '1:7:D';
  $harmonics[] = '1:8:C';
  $harmonics[] = '1:9:Bb';
  $harmonics[] = '1:10:Ab';
  $harmonics[] = '1:11:Gb';
  $harmonics[] = '1:12:F';
  $harmonics[] = '1:13:E';
  $harmonics[] = '1:14:D';
  $harmonics[] = '1:15:Db';
  $harmonics[] = '1:16:C';
  $harmonics[] = '2:1:C';
  $harmonics[] = '2:2:C';
  $harmonics[] = '2:3:F';
  $harmonics[] = '2:4:C';
  $harmonics[] = '2:5:Ab';
  $harmonics[] = '2:6:F';
  $harmonics[] = '2:7:D';
  $harmonics[] = '2:8:C';
  $harmonics[] = '2:9:Bb';
  $harmonics[] = '2:10:Ab';
  $harmonics[] = '2:11:Gb';
  $harmonics[] = '2:12:F';
  $harmonics[] = '2:13:E';
  $harmonics[] = '2:14:D';
  $harmonics[] = '2:15:Db';
  $harmonics[] = '2:16:C';
  $harmonics[] = '3:1:G';
  $harmonics[] = '3:2:G';
  $harmonics[] = '3:3:C';
  $harmonics[] = '3:4:G';
  $harmonics[] = '3:5:Eb';
  $harmonics[] = '3:6:C';
  $harmonics[] = '3:7:A';
  $harmonics[] = '3:8:G';
  $harmonics[] = '3:9:F';
  $harmonics[] = '3:10:Eb';
  $harmonics[] = '3:11:Db';
  $harmonics[] = '3:12:C';
  $harmonics[] = '3:13:B';
  $harmonics[] = '3:14:A';
  $harmonics[] = '3:15:Ab';
  $harmonics[] = '3:16:G';
  $harmonics[] = '4:1:C';
  $harmonics[] = '4:2:C';
  $harmonics[] = '4:3:F';
  $harmonics[] = '4:4:C';
  $harmonics[] = '4:5:Ab';
  $harmonics[] = '4:6:F';
  $harmonics[] = '4:7:D';
  $harmonics[] = '4:8:C';
  $harmonics[] = '4:9:Bb';
  $harmonics[] = '4:10:Ab';
  $harmonics[] = '4:11:Gb';
  $harmonics[] = '4:12:F';
  $harmonics[] = '4:13:E';
  $harmonics[] = '4:14:D';
  $harmonics[] = '4:15:Db';
  $harmonics[] = '4:16:C';
  $harmonics[] = '5:1:E';
  $harmonics[] = '5:2:E';
  $harmonics[] = '5:3:A';
  $harmonics[] = '5:4:E';
  $harmonics[] = '5:5:C';
  $harmonics[] = '5:6:A';
  $harmonics[] = '5:7:Gb';
  $harmonics[] = '5:8:E';
  $harmonics[] = '5:9:D';
  $harmonics[] = '5:10:C';
  $harmonics[] = '5:11:Bb';
  $harmonics[] = '5:12:A';
  $harmonics[] = '5:13:G';
  $harmonics[] = '5:14:Gb';
  $harmonics[] = '5:15:F';
  $harmonics[] = '5:16:E';
  $harmonics[] = '6:1:G';
  $harmonics[] = '6:2:G';
  $harmonics[] = '6:3:C';
  $harmonics[] = '6:4:G';
  $harmonics[] = '6:5:Eb';
  $harmonics[] = '6:6:C';
  $harmonics[] = '6:7:A';
  $harmonics[] = '6:8:G';
  $harmonics[] = '6:9:F';
  $harmonics[] = '6:10:Eb';
  $harmonics[] = '6:11:Db';
  $harmonics[] = '6:12:C';
  $harmonics[] = '6:13:B';
  $harmonics[] = '6:14:A';
  $harmonics[] = '6:15:Ab';
  $harmonics[] = '6:16:G';
  $harmonics[] = '7:1:Bb';
  $harmonics[] = '7:2:Bb';
  $harmonics[] = '7:3:Eb';
  $harmonics[] = '7:4:Bb';
  $harmonics[] = '7:5:Gb';
  $harmonics[] = '7:6:Eb';
  $harmonics[] = '7:7:C';
  $harmonics[] = '7:8:Bb';
  $harmonics[] = '7:9:Ab';
  $harmonics[] = '7:10:Gb';
  $harmonics[] = '7:11:E';
  $harmonics[] = '7:12:Eb';
  $harmonics[] = '7:13:Db';
  $harmonics[] = '7:14:C';
  $harmonics[] = '7:15:B';
  $harmonics[] = '7:16:Bb';
  $harmonics[] = '8:1:C';
  $harmonics[] = '8:2:C';
  $harmonics[] = '8:3:F';
  $harmonics[] = '8:4:C';
  $harmonics[] = '8:5:Ab';
  $harmonics[] = '8:6:F';
  $harmonics[] = '8:7:D';
  $harmonics[] = '8:8:C';
  $harmonics[] = '8:9:Bb';
  $harmonics[] = '8:10:Ab';
  $harmonics[] = '8:11:Gb';
  $harmonics[] = '8:12:F';
  $harmonics[] = '8:13:E';
  $harmonics[] = '8:14:D';
  $harmonics[] = '8:15:Db';
  $harmonics[] = '8:16:C';
  $harmonics[] = '9:1:D';
  $harmonics[] = '9:2:D';
  $harmonics[] = '9:3:G';
  $harmonics[] = '9:4:D';
  $harmonics[] = '9:5:Eb';
  $harmonics[] = '9:6:G';
  $harmonics[] = '9:7:E';
  $harmonics[] = '9:8:D';
  $harmonics[] = '9:9:C';
  $harmonics[] = '9:10:Bb';
  $harmonics[] = '9:11:A';
  $harmonics[] = '9:12:G';
  $harmonics[] = '9:13:Gb';
  $harmonics[] = '9:14:E';
  $harmonics[] = '9:15:Eb';
  $harmonics[] = '9:16:D';
  $harmonics[] = '10:1:E';
  $harmonics[] = '10:2:E';
  $harmonics[] = '10:3:A';
  $harmonics[] = '10:4:E';
  $harmonics[] = '10:5:C';
  $harmonics[] = '10:6:A';
  $harmonics[] = '10:7:Gb';
  $harmonics[] = '10:8:E';
  $harmonics[] = '10:9:D';
  $harmonics[] = '10:10:C';
  $harmonics[] = '10:11:Bb';
  $harmonics[] = '10:12:A';
  $harmonics[] = '10:13:G';
  $harmonics[] = '10:14:Gb';
  $harmonics[] = '10:15:F';
  $harmonics[] = '10:16:E';
  $harmonics[] = '11:1:Gb';
  $harmonics[] = '11:2:Gb';
  $harmonics[] = '11:3:B';
  $harmonics[] = '11:4:Gb';
  $harmonics[] = '11:5:D';
  $harmonics[] = '11:6:B';
  $harmonics[] = '11:7:Ab';
  $harmonics[] = '11:8:Gb';
  $harmonics[] = '11:9:Eb';
  $harmonics[] = '11:10:D';
  $harmonics[] = '11:11:C';
  $harmonics[] = '11:12:B';
  $harmonics[] = '11:13:A';
  $harmonics[] = '11:14:Ab';
  $harmonics[] = '11:15:G';
  $harmonics[] = '11:16:Gb';
  $harmonics[] = '12:1:G';
  $harmonics[] = '12:2:G';
  $harmonics[] = '12:3:C';
  $harmonics[] = '12:4:G';
  $harmonics[] = '12:5:Eb';
  $harmonics[] = '12:6:C';
  $harmonics[] = '12:7:A';
  $harmonics[] = '12:8:G';
  $harmonics[] = '12:9:F';
  $harmonics[] = '12:10:Eb';
  $harmonics[] = '12:11:Db';
  $harmonics[] = '12:12:C';
  $harmonics[] = '12:13:C';
  $harmonics[] = '12:14:B';
  $harmonics[] = '12:15:A';
  $harmonics[] = '12:16:Ab';
  $harmonics[] = '13:1:Ab';
  $harmonics[] = '13:2:Ab';
  $harmonics[] = '13:3:Db';
  $harmonics[] = '13:4:Ab';
  $harmonics[] = '13:5:F';
  $harmonics[] = '13:6:Db';
  $harmonics[] = '13:7:B';
  $harmonics[] = '13:8:Ab';
  $harmonics[] = '13:9:Gb';
  $harmonics[] = '13:10:F';
  $harmonics[] = '13:11:Eb';
  $harmonics[] = '13:12:Db';
  $harmonics[] = '13:13:C';
  $harmonics[] = '13:14:B';
  $harmonics[] = '13:15:Bb';
  $harmonics[] = '13:16:Ab';
  $harmonics[] = '14:1:Bb';
  $harmonics[] = '14:2:Bb';
  $harmonics[] = '14:3:Eb';
  $harmonics[] = '14:4:Bb';
  $harmonics[] = '14:5:Gb';
  $harmonics[] = '14:6:Eb';
  $harmonics[] = '14:7:C';
  $harmonics[] = '14:8:Bb';
  $harmonics[] = '14:9:Ab';
  $harmonics[] = '14:10:Gb';
  $harmonics[] = '14:11:E';
  $harmonics[] = '14:12:Eb';
  $harmonics[] = '14:13:Db';
  $harmonics[] = '14:14:C';
  $harmonics[] = '14:15:B';
  $harmonics[] = '14:16:Bb';
  $harmonics[] = '15:1:B';
  $harmonics[] = '15:2:B';
  $harmonics[] = '15:3:E';
  $harmonics[] = '15:4:B';
  $harmonics[] = '15:5:G';
  $harmonics[] = '15:6:E';
  $harmonics[] = '15:7:Db';
  $harmonics[] = '15:8:B';
  $harmonics[] = '15:9:A';
  $harmonics[] = '15:10:G';
  $harmonics[] = '15:11:F';
  $harmonics[] = '15:12:E';
  $harmonics[] = '15:13:D';
  $harmonics[] = '15:14:Db';
  $harmonics[] = '15:15:C';
  $harmonics[] = '15:16:B';
  $harmonics[] = '16:1:C';
  $harmonics[] = '16:2:C';
  $harmonics[] = '16:3:F';
  $harmonics[] = '16:4:C';
  $harmonics[] = '16:5:Ab';
  $harmonics[] = '16:6:F';
  $harmonics[] = '16:7:D';
  $harmonics[] = '16:8:C';
  $harmonics[] = '16:9:Bb';
  $harmonics[] = '16:10:Ab';
  $harmonics[] = '16:11:Gb';
  $harmonics[] = '16:12:F';
  $harmonics[] = '16:13:E';
  $harmonics[] = '16:14:D';
  $harmonics[] = '16:15:Db';
  $harmonics[] = '16:16:C';
  $harmonics[] = '0:1:zero';
  $harmonics[] = '0:2:zero';
  $harmonics[] = '0:3:zero';
  $harmonics[] = '0:4:zero';
  $harmonics[] = '0:5:zero';
  $harmonics[] = '0:6:zero';
  $harmonics[] = '0:7:zero';
  $harmonics[] = '0:8:zero';
  $harmonics[] = '0:9:zero';
  $harmonics[] = '0:10:zero';
  $harmonics[] = '0:11:zero';
  $harmonics[] = '0:12:zero';
  $harmonics[] = '0:13:zero';
  $harmonics[] = '0:14:zero';
  $harmonics[] = '0:15:zero';
  $harmonics[] = '0:16:zero';
  $harmonics[] = '1:0:infinity';
  $harmonics[] = '2:0:infinity';
  $harmonics[] = '3:0:infinity';
  $harmonics[] = '4:0:infinity';
  $harmonics[] = '5:0:infinity';
  $harmonics[] = '6:0:infinity';
  $harmonics[] = '7:0:infinity';
  $harmonics[] = '8:0:infinity';
  $harmonics[] = '9:0:infinity';
  $harmonics[] = '10:0:infinity';
  $harmonics[] = '11:0:infinity';
  $harmonics[] = '12:0:infinity';
  $harmonics[] = '13:0:infinity';
  $harmonics[] = '14:0:infinity';
  $harmonics[] = '15:0:infinity';
  $harmonics[] = '16:0:infinity';

  return $harmonics;
}

function jellomatrix_output_basegrid($scale_increments, $prime_matrix, $primes, $tone, $interval) {
  $output = '';

  $output .= '<div class="begintext">';

  if (isset($scale_increments)) {
    $output .= '<div class="floatright"><h3>You have scales!</h3></div>';
  }
  else {
    $output .= '<div class="floatright"><h3>Not scale active. Try again!</h3></div>';
  }
  $output .= '</div><hr class="hr"><br></div>';
  $output .= '<div class="begingrid"><h3>The Original Matrix</h3><div class="endtext"><br></div>';
  $output .= '<table class="table begingrid" cols="' . $tone . '" rows="' . $interval . '">';
  $totalcount = $tone*$interval;
  foreach($prime_matrix as $prime_row) {
    $output .= '<tr>';
    foreach($prime_row as $item) {
      if ($item['tone']%2 != 0) {
        $color = 'white';
      }
      if ($item['tone']%2 == 0) {
        $color = 'subhighlight';
      }
      foreach ($primes as $prime) {
        if ($item['tone'] == $prime) {
          $color = 'white';
            if ($item['tone']%2 == 0) {
              $color = 'highlight';
            }
        }
      }
      $output .= '<td class="tdgrid ' .$color. ' red-text">'.$item['tone'] . '</td>';
    }
    $output .= '</tr>';
  }
  $output .= '</table></div><p><br/></p><hr class="hr"><p><br/></p><div class="endtext"><br></div>';

  return $output;
}

function jellomatrix_output_splicegrid_basic($spliced_matrix, $primes, $tone, $interval) {
  $output = '';

  // And then we create the spliced matrix grid.
  $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>The Basic Orientation of the Spliced Matrix</h3><table class=" table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
  for ($i = 1; $i <= $interval; $i++) {
    $output .= '<tr>';
    $count = 1;
    foreach ($spliced_matrix as $spliced_row) {
      foreach ($spliced_row as $item) {
        if ($item['row'] == $i) {
          $prime = jellomatrix_primes($tone);
          if (($item['column'])%2 == 0) {
            $item['color'] = 'green-text';
          }
          if (($item['column'])%2 != 0) {
            $item['color'] = 'red-text';
          }
          if ($item['column'] == 1 && $item['row'] == 1 || $item['column'] == 2 && $item['row'] == $interval) {
            $item['background'] = 'green';
            $item['opacity'] = '.' . $item['tone'];
          }
          if ($item['column'] == 2*$tone && $item['row'] == $interval || $item['column'] == (2*$tone)-1 && $item['row'] == 1) {
            $item['background'] = 'orange';
            $item['opacity'] = '.' . $item['tone'];
          }
          $output .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' .$item['color'] . ' tdgrid ' .$item['background'] . '">' . $item['tone'] . '</td>';
          $count++;
        }
      }
    }
    $output .= '</tr>';
  }
  $output .= '</table><div><hr class="hr"></div></div>';

  return $output;
}

function jellomatrix_output_splicegrid_primes($spliced_matrix, $primes, $tone, $interval) {
  $output = '';

  // And then we create the spliced matrix grid.
  $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>HIGHLIGHTING PRIMES: The Spliced Matrix</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
  for ($i = 1; $i <= $interval; $i++) {
    $output .= '<tr>';
    $count = 1;
    foreach ($spliced_matrix as $spliced_row) {
      foreach ($spliced_row as $item) {
        if ($item['row'] == $i) {
          $prime = jellomatrix_primes($tone);
          if (($item['column'])%2 == 0) {
            $item['color'] = 'green-text';
          }
          if (($item['column'])%2 != 0) {
            $item['color'] = 'red-text';
          }
          if (in_array($item['tone'], $primes)) {
            $item['background'] = 'highlight';
            $item['opacity'] = '.' . $item['tone'];
          }
          if (!in_array($item['tone'], $primes)) {
            $item['background'] = 'white';
            $item['opacity'] = '.' . $item['tone'];
          }
          $output .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' .$item['color'] . ' tdgrid ' .$item['background'] . '">' . $item['tone'] . '</td>';
          $count++;
        }
      }
    }
    $output .= '</tr>';
  }
  $output .= '</table><div><hr class="hr"></div></div>';

  return $output;
}

function jellomatrix_output_splicegrid_evenodd($spliced_matrix, $primes, $tone, $interval) {
  $output = '';
  // And then we create the spliced matrix grid.
  $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>HIGHLIGHTING EVEN+ODD: The Spliced Matrix</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
  for ($i = 1; $i <= $interval; $i++) {
    $output .= '<tr>';
    $count = 1;
    foreach ($spliced_matrix as $spliced_row) {
      foreach ($spliced_row as $item) {
        if ($item['row'] == $i) {
          $prime = jellomatrix_primes($tone);
          if (($item['column'])%2 == 0) {
            $item['color'] = 'green-text';
          }
          if (($item['column'])%2 != 0) {
            $item['color'] = 'red-text';
          }
          if (($item['tone'])%2 == 0) {
            $item['background'] = 'white';
            $item['opacity'] = '.' . $item['tone'];
          }
          if (($item['tone'])%2 != 0) {
            $item['background'] = 'highlight';
            $item['opacity'] = '.' . $item['tone'];
          }
          $output .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' . $item['color'] . ' tdgrid  ' . $item['background'] . '">' . $item['tone'] . '</td>';
          $count++;
        }
      }
    }
    $output .= '</tr>';
  }
  $output .= '</table><div class="hr begintext"><p>Interstingly enough, the sections which seem to hold information about the vortec/ies they reflect seem to fall most often in the middle of the sine waves created by what appear to be very different "environments" or "gradients" between higher frequency oscillations of even and odd numbers (you might need to squint your eyes to see them), They are the waves defined by the more or less frequent oscillatory patterns taken as a whole.  More about this in the "Rows" calculations in the "Increments" section below.</p><hr class="hr"></div></div>';

  return $output;
}

function jellomatrix_output_splicegrid_waveforms($spliced_matrix, $splied_matrix_reversed, $primes, $tone, $interval) {
  $output = '';

  if (!empty($spliced_matrix_reversed)) {
    foreach ($spliced_matrix_reversed as $spliced_row_reversed) {
      foreach ($spliced_row_reversed as $item) {
        if (isset($item['phase_color']) && !isset($item['wave_limit'])) {
          $reversed = 'reversed';
        }
      }
    }
  }
  foreach ($spliced_matrix as $spliced_row) {
    foreach ($spliced_row as $item) {
      if (isset($item['phase_color']) && !isset($item['wave_limit'])) {
        $forward = 'forward';
      }
    }
  }

  if (isset($reversed) && isset($forward)) {
    $direction = 'Bi-directional';
    $output .= '<p><br/></p><h2>Prime Series of Matrix is Bi-directional</h2>';
  }

  if (!isset($reversed) && isset($forward)) {
    $direction = 'Forward Only';
    $output .= '<p><br/></p><h2>Prime Series of Matrix is Forward Only</h2>';
  }

  if (isset($reversed) && !isset($forward)) {
    $direction = 'Reversed Only';
    $spliced_matrix = $spliced_matrix_reversed;
    $output .= '<p><br/></p><h2>Prime Series of Matrix is Reversed Only</h2>';
  }


  // And then we create the spliced matrix grid using wave indicators for coloring
  // (we could use the rows at this point as well)...
  $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>WAVE FORM POLE SHIFT: Highlighting the adjacent equal values.</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
  for ($i = 1; $i <= $interval; $i++) {
    $output .= '<tr>';
    $count = 1;
    foreach ($spliced_matrix as $spliced_row) {
      foreach ($spliced_row as $item) {
        if ($item['row'] == $i) {
          $prime = jellomatrix_primes($tone);
          if (($item['column'])%2 == 0) {
            $item['color'] = 'green-text';
          }
          if (($item['column'])%2 != 0) {
            $item['color'] = 'red-text';
          }
          if (isset($item['pole_shift'])) {
            if ($item['pole_shift'] == 1) {
              $item['background'] = 'yellow-background';
              $item['opacity'] = 1;
            }
            if ($item['pole_shift'] == 2) {
              $item['background'] = 'torquoise-background';
              $item['opacity'] = 1;
            }
          }
          $output .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' .$item['color'] . ' tdgrid ' .$item['background'] . '">' . $item['tone'] . '</td>';
          $count++;
        }
      }
    }
    $output .= '</tr>';
  }
  $output .= '</table><div class="begintext endtable"></div><hr class="hr"><br></div>';

  // And then we create the spliced matric grid using wave indicators for coloring
  // (we could use the rows at this point as well)...
  $output_even = '<div class="begintext endtable"></div><div class="begingrid"><h3>WAVE FORM SCALES: The Waveform Scales: EVEN Rhythms</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
  for ($i = 1; $i <= $interval; $i++) {
    $output_even .= '<tr>';
    $count = 1;
    foreach ($spliced_matrix as $spliced_row) {
      foreach ($spliced_row as $item) {
        if ($item['row'] == $i) {
          $prime = jellomatrix_primes($tone);
          if (($item['column'])%2 == 0) {
            $item['color'] = 'green-text';
          }
          if (($item['column'])%2 != 0) {
            $item['color'] = 'red-text';
          }
          if (isset($item['wave_limit'])) {
            if ($item['pole_shift'] == 2) {
              $item['background'] = 'yellow-background-light';
              $item['opacity'] = 1;
            }
            if ($item['pole_shift'] == 1) {
              $item['background'] = 'torquoise-background-light';
              $item['opacity'] = 1;
            }
          }
          $even = 0;
          if (isset($item['rhythm'])) {
            foreach ($item['rhythm'] as $rhythm) {
              if ($rhythm%2 == 0) {
                $even = 1;
                $evengrid = 1;
              }
            }
          }
          if (isset($item['yellow']) && isset($even) && $even == 1) {
            $item['background'] = $item['yellow'];
            $item['opacity'] = 1;
            $item['br'] = 'border-radius';
          }


          $output_even .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' . $item['color'] . ' tdgrid ';
          if (isset($item['br'])) {
            $output_even .= $item['br'];
          }
          if (isset($item['scale'])) {
            $output_even .= ' ' .$item['background'] . '">';
            $output_even .= $item['tone'] . '</td>';
          }
          else {
            $output_even .= ' ' .$item['background'] . '">';
            $output_even .= $item['tone'] . '</td>';
          }
          $count++;

        }
      }
    }
    $output_even .= '</tr>';
  }
  $output_even .= '</table><div class="begintext endtable"></div><p></p><hr class="hr"></div>';

  if (isset($evengrid)) {
    $output .= $output_even;
    unset($evengrid);
  }

  // And then we create the spliced matric grid using wave indicators for coloring
  // (we could use the rows at this point as well)...
  $output_odd = '<div class="begintext endtable"></div><div class="begingrid"><h3>WAVE FORM SCALES: The Waveform Scales: ODD Rhythms</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
  for ($i = 1; $i <= $interval; $i++) {
    $output_odd .= '<tr>';
    $count = 1;
    foreach ($spliced_matrix as $spliced_row) {
      foreach ($spliced_row as $item) {
        if ($item['row'] == $i) {
          $prime = jellomatrix_primes($tone);
          if (($item['column'])%2 == 0) {
            $item['color'] = 'green-text';
          }
          if (($item['column'])%2 != 0) {
            $item['color'] = 'red-text';
          }
          if (isset($item['wave_limit'])) {
            if ($item['pole_shift'] == 2) {
              $item['background'] = 'yellow-background-light';
              $item['opacity'] = 1;
            }
            if ($item['pole_shift'] == 1) {
              $item['background'] = 'torquoise-background-light';
              $item['opacity'] = 1;
            }
          }
          $odd = 0;
          if (isset($item['rhythm'])) {
            foreach ($item['rhythm'] as $rhythm) {
              if ($rhythm%2 != 0) {
                $odd = 1;
                $oddgrid = 1;
              }
            }
          }
          if (isset($item['yellow']) && isset($odd) && $odd != 0) {
            $item['background'] = $item['yellow'];
            $item['opacity'] = 1;
            $item['br'] = 'border-radius';
          }
          $output_odd .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' . $item['color'] . ' tdgrid ';
          if (isset($item['br'])) {
            $output_odd .= $item['br'];
          }
          $output_odd .= ' ' .$item['background'] . '">';
          if (isset($item['scale'])) {
            $output_odd .= $item['tone'] . '</td>';
          }
          else {
            $output_odd .= $item['tone'] . '</td>';
          }
          $count++;
        }
      }
    }
    $output_odd .= '</tr>';
  }
  $output_odd .= '</table><div class="begintext endtable"></div><p></p><hr class="hr">';

  if (isset($oddgrid)) {
    //TODO: Bring back odd grid
    $output .= $output_odd;
    unset($oddgrid);
  }

  return $output;
}

function jellomatrix_output_splicegrid_scalepattern($scale_increments, $scaled, $primes, $tone, $interval) {
  $output = '';
  if (isset($scaled)) {
    $output .= '<div class="begintext"><p><h3>Scale Pattern:</h3></p><p><img src="/sites/default/files/voodoo-doll-penticle.png?t='. time().'" /></p><div class="endtext"><br></div>';
    $output .= '<p><strong>' . $scaled . '...</strong></p><div class="endtext"><br></div>';
    $output .= '<p><strong>Not all detected waveforms will render completely above.</strong></p><div class="endtext"><br></div>';
    $output .= '<p><strong>RED</strong> = Start of wave.</p>';
    $output .= '<p><strong>EVEN Waves</strong></p>';
    if (isset($scale_increments)) {
      foreach ($scale_increments as $i=>$increment) {
        $explode = explode(':', $increment);
        $t = $explode[0];
        $jump = $explode[1];
        $direction = $explode[2];
        $scale_direction = $explode[3];
        $color = $explode[4];
        if ($jump %2 == 0) {
          $output .= '<p><strong>Starting ' . $t . ':</strong> scale direction = ' . $scale_direction . ', rhythm = ' . $jump . ', initial vertical = ' . $direction . ', color = ' . $color . '.</p>';
        }
        if ($jump %2 != 0) {
          $odd_waves = 1;
        }
      }
    }
    if (isset($odd_waves)){
      $output .= '<p><strong>ODD Waves</strong></p>';
    }
    unset($odd_waves);
    if (isset($scale_increments)) {
      foreach ($scale_increments as $i=>$increment) {
        $explode = explode(':', $increment);
        $t = $explode[0];
        $jump = $explode[1];
        $direction = $explode[2];
        $scale_direction = $explode[3];
        $color = $explode[4];
        if ($jump %2 != 0) {
          $output .= '<p><strong>Starting ' . $t . ':</strong> scale direction = ' . $scale_direction . ', rhythm = ' . $jump . ', initial vertical = ' . $direction . ', color = ' . $color . '.</p>';
        }
      }
    }
  }
  $output .= '<p></p><br></div>';

  return $output;
}

function jellomatrix_output_splicegrid_harmonics($increment_original, $harmonics, $primes, $tone, $interval) {
  $output = '';
  // First output the original harmonics with $columns and $rows.
  // Now output the differences between different integers.
  $output .= '<div class="endtable begintext"><h2>ODD/EVEN: Differences and Harmonics</h2>';
  $output .= '<p>These increment calculations show the relationships of the numbers in the grid by relating them to the ones in front of them (forward) and behind them (backwards) using the "tone" value as the base in the numbering system.</p><div class="endtext"><br></div>';
  $output .= '<p>The diagonal increments still go down the row, but show the relationships between the number and the one diagonally above (forward) it and below it (backward).</p><div class="endtext"><br></div>';
  $output .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios the repeating increments create.</p><div class="endtext"><br></div>';
  foreach($increment_original as $key=>$increment) {
    if ($key == 'row') {
      $r = '<h3>Row</h3>';
      foreach ($increment as $key=>$direction) {
        if ($key == 'forward') {
          $r .= '<h4>Forward (Odd/Even) (x,y)|(x+1,y)</h4>';
          $r .= '<div class="begintext"><p>As alluded to above, if you look at the number grid below, what I have noticed is that I can usually find \'vortex activity\' starting and ending with rows that oscillate between \'0\' and another integer.  So in this section, the vortex arrays are between "zero" and "infinity". In addition, between these rows, it seems to be important to have the intervals mirror one another as you move towards the center.</p></div>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';

            foreach ($row as $key=>$item) {
              if (($item)%2 == 0) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'backward') {
          $r .= '<h4>Backward (Odd/Even) (x,y)|(x-1,y)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';

            foreach ($row as $key=>$item) {
              if (($item)%2 == 0) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
      }
    }
    if ($key == 'lrdiag') {
      $lr = '<h3>Left to Right Diagonals across a Row</h3>';
      foreach ($increment as $key=>$direction) {
        if ($key == 'forward') {
          $lr .= '<h4>Forward (Odd/Even) (x,y)|(x+1,y+1)</h4>';
          $count = 1;
          $lr .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $lr .= '<td class="tdgridlt">LR Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (($item)%2 == 0) {
                $lr .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $lr .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $lr .= '</tr>';
            $count++;
          }
          $lr .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'backward') {
          $lr .= '<h4>Backward (Odd/Even) (x,y)|(x-1,y-1)</h4>';
          $count = 1;
          $lr .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $lr .= '<td class="tdgridlt">LR Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (($item)%2 == 0) {
                $lr .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $lr .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $lr .= '</tr>';
            $count++;
          }
          $lr .= '</table><div class="endtext"><br></div>';
        }
      }
    }
    if ($key == 'rldiag') {
      $rl = '<h3>Right to Left Diagonals across a Row</h3>';
      foreach ($increment as $key=>$direction) {
        if ($key == 'forward') {
          $rl .= '<h4>Forward (Odd/Even) (x,y)|(x+1,y-1)</h4>';
          $count = 1;
          $rl .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $rl .= '<td class="tdgridlt">RL Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (($item)%2 == 0) {
                $rl .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $rl .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $rl .= '</tr>';
            $count++;
          }
          $rl .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'backward') {
          $rl .= '<h4>Backward (Odd/Even) (x,y)|(x-1,y+1)</h4>';
          $count = 1;
          $rl .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $rl .= '<td class="tdgridlt">RL Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (($item)%2 == 0) {
                $rl .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $rl .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $rl .= '</tr>';
            $count++;
          }
          $rl .= '</table><div class="endtext"><br></div>';
        }
      }
    }
  }
  $output .= $r;
  $output .= $rl;
  $output .= $lr;
  $output .= '<hr class="hr">';

  return $output;
}

function jellomatrix_output_splicegrid_derivatives($increments, $primes, $tone, $interval, $harmonics) {
  $output = '';
  // Now output the differences between different integers.
  $output .= '<div class="endtable begintext"><h2>ODD+EVEN: Derivatives</h2>';
  $output .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios of repeating increments create.</p><div class="endtext"><br></div>';

  foreach($increments as $k=>$increment) {
    if ($k == 'row') {
      $r = '<h3>ODD+EVEN: Original Matrix</h3>';
      foreach ($increment as $key => $direction) {
        if ($key == 'forward') {
          $r .= '';
          $r .= '<p></p><div class="endtext"><br></div>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $spliced_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count . ': </td>';
            foreach ($spliced_row as $key => $item) {
              if (($item) % 2 == 0) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item) % 2 != 0) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }

        if ($key == 'derivative') {
          $r .= '<h4>First Derivative (Odd/Even)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $spliced_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count . ': </td>';
            foreach ($spliced_row as $k => $item) {
              if (($item) % 2 == 0) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item) % 2 != 0) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }

            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }


        if ($key == 'derivative_2') {
          $r .= '<h4>Second Derivative (Odd/Even)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $spliced_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count . ': </td>';
            foreach ($spliced_row as $k => $item) {
              if (($item) % 2 == 0) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item) % 2 != 0) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
      }
    }
  }

  $output .= $r;
  $output .= '</div><hr class="hr"><br></div>';

  // Now output the differences between different integers.
  $output .= '<div class="endtable begintext"><h2>PRIMES: Derivatives</h2>';
  $output .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios of repeating increments create.</p><div class="endtext"><br></div>';

  foreach($increments as $k=>$increment) {
    if ($k == 'row') {
      $r = '<h3>PRIMES: Original Matrix</h3>';
      foreach ($increment as $key=>$direction) {
        if ($key == 'forward') {
          $r .= '';
          $r .= '<p></p><div class="endtext"><br></div>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $spliced_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($spliced_row as $k=>$item) {
              if (in_array($item, $primes)) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (!in_array($item, $primes)) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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

            //dpm($upper);
            if (isset($upper)) {
              foreach ($harmonics as $note) {
                $explode = explode(':', $note);
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'derivative') {
          $r .= '<h4>First Derivative (Primes)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $spliced_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($spliced_row as $k=>$item) {
              if (in_array($item, $primes)) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (!in_array($item, $primes)) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'derivative_2') {
          $r .= '<h4>Second Derivative (Primes)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $spliced_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($spliced_row as $k=>$item) {
              if (in_array($item, $primes)) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (!in_array($item, $primes)) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
      }
    }
  }
  $output .= $r;

  return $output;
}

function jellomatrix_output_splicegrid_derivative_harmonics($increment_original, $harmonics, $primes, $tone, $interval) {
  $output = '';

  // PRIMARY MATRIX DERIVATIVES.

  // Increment_PRIME: with the prime_matrix grid and the prime_increments variable.

  // First output the original harmonics with $columns and $rows.
  // Now output the differences between different integers.
  $output .= '<div class="endtable begintext"><h2>PRIMES: Differences and Harmonics</h2>';
  $output .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios of repeating increment_prime_original create.</p><div class="endtext"><br></div>';
  foreach($increment_original as $key=>$increment_prime) {
    if ($key == 'rldiag') {
      $r = '<h3>Row</h3>';
      foreach ($increment_prime as $key=>$direction) {
        if ($key == 'forward') {
          $r .= '<h4>Forward (Primes) (x,y)|(x+1,y)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (in_array($item, $primes)) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'backward') {
          $r .= '<h4>Backward (Primes) (x,y)|(x-1,y)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (in_array($item, $primes)) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
      }
    }
    if ($key == 'row') {
      $lr = '<h3>Left to Right Diagonals across a Row</h3>';
      foreach ($increment_prime as $key=>$direction) {
        if ($key == 'forward') {
          $lr .= '<h4>Forward (Odd/Even) (x,y)|(x+1,y+1)</h4>';
          $count = 1;
          $lr .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $lr .= '<td class="tdgridlt">LR Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (($item)%2 == 0) {
                $lr .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $lr .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $lr .= '</tr>';
            $count++;
          }
          $lr .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'backward') {
          $lr .= '<h4>Backward (Primes) (x,y)|(x-1,y-1)</h4>';
          $count = 1;
          $lr .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $lr .= '<td class="tdgridlt">LR Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (in_array($item, $primes)) {
                $lr .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (!in_array($item, $primes)) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $lr .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $lr .= '</tr>';
            $count++;
          }
          $lr .= '</table><div class="endtext"><br></div>';
        }
      }
    }
    if ($key == 'lrdiag') {
      $rl = '<h3>Right to Left Diagonals across a Row</h3>';
      foreach ($increment_prime as $key=>$direction) {
        if ($key == 'forward') {
          $rl .= '<h4>Forward (Primes) (x,y)|(x+1,y-1)</h4>';
          $count = 1;
          $rl .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $rl .= '<td class="tdgridlt">RL Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (in_array($item, $primes)) {
                $rl .= '<td class="tdgrid highlight">' . $item . '</td>';
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $rl .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $rl .= '</tr>';
            $count++;
          }
          $rl .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'backward') {
          $rl .= '<h4>Backward (Primes) (x,y)|(x-1,y+1)</h4>';
          $count = 1;
          $rl .= '<table class="table"><tr>';
          foreach ($direction as $row) {
            $rl .= '<td class="tdgridlt">RL Row ' . $count .': </td>';
            foreach ($row as $key=>$item) {
              if (in_array($item, $primes)) {
                $rl .= '<td class="tdgrid highlight">' . $item . '</td>';
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $rl .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $rl .= '</tr>';
            $count++;
          }
          $rl .= '</table><div class="endtext"><br></div>';
        }
      }
    }
  }
  $output .= $r;
  $output .= $rl;
  $output .= $lr;
  $output .= '<hr class="hr">';

  return $output;
}

function jellomatrix_output_splicegrid_derivative_oddeven($increments_prime, $primes, $tone, $interval, $harmonics) {
  $output = '';
  // Now output the differences between different integers.
  $output .= '<div class="endtable begintext"><h2>PRIMARY MATRIX DERIVATIVES ODD+EVEN: Derivatives</h2>';
  $output .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios of repeating PRIMARY MATRIX increments create.</p><div class="endtext"><br></div>';

  foreach($increments_prime as $k=>$increment_prime) {
    if ($k == 'row') {
      $r = '<h3>PRIME (Odd/Even): Original Matrix</h3>';
      foreach ($increment_prime as $key=>$direction) {
        if ($key == 'forward') {
          $r .= '';
          $r .= '<p></p><div class="endtext"><br></div>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $prime_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($prime_row as $k=>$item) {
              if (($item)%2 == 0) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'derivative') {
          $r .= '<h4>First Derivative: (Odd/Even)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $prime_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($prime_row as $k=>$item) {
              if (($item)%2 == 0) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'derivative_2') {
          $r .= '<h4>Second Derivative: (Odd/Even)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $prime_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($prime_row as $k=>$item) {
              if (($item)%2 == 0) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (($item)%2 != 0) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
      }
    }
  }
  $output .= $r;
  $output .= '</div><hr class="hr"><br>';

  return $output;
}

function jellomatrix_output_splicegrid_derivative_primes($increments_prime, $primes, $tone, $interval, $harmonics) {
  $output = '';
  // Now output the differences between different integers.
  $output .= '<div class="endtable begintext"><h2>PRIMARY MATRIX DERIVATIVES PRIMES: Derivatives</h2>';
  $output .= '<p>The bold letters at the end of each row represent the Lambdona Notes that the ratios of repeating PRIMARY MATRIX increments create.</p><div class="endtext"><br></div>';

  foreach($increments_prime as $k=>$increment_prime) {
    if ($k == 'row') {
      $r = '<h3>PRIMARY MATRIX: (Primes) Original Matrix</h3>';
      foreach ($increment_prime as $key=>$direction) {
        if ($key == 'forward') {
          $r .= '';
          $r .= '<p></p><div class="endtext"><br></div>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $prime_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($prime_row as $k=>$item) {
              if (in_array($item, $primes)) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (!in_array($item, $primes)) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'derivative') {
          $r .= '<h4>First Derivative: (Primes)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $prime_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($prime_row as $k=>$item) {
              if (in_array($item, $primes)) {
                $r .= '<td class="tdgrid highlight">' . $item . '</td>';
              }
              if (!in_array($item, $primes)) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
        if ($key == 'derivative_2') {
          $r .= '<h4>Second Derivative: (Primes)</h4>';
          $count = 1;
          $r .= '<table class="table"><tr>';
          foreach ($direction as $prime_row) {
            $r .= '<td class="tdgridltfirst">Row ' . $count .': </td>';
            foreach ($prime_row as $k=>$item) {
              if (in_array($item, $primes)) {
              }
              if (!in_array($item, $primes)) {
                $r .= '<td class="tdgrid">' . $item . '</td>';
              }
              if ($k == 1) {
                $one = $item;
              }
              if ($k == 2) {
                $two = $item;
              }
              if ($k == 3) {
                $three = $item;
              }
              if ($k == 4) {
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
                if (isset($lower) && $explode[0] == $upper && $explode[1] == $lower) {
                  $r .= '<td class="tdgrid">' . $explode[2] . '</td>';
                }
              }
            }
            $r .= '</tr>';
            $count++;
          }
          $r .= '</table><div class="endtext"><br></div>';
        }
      }
    }
  }
  $output .= $r;
  $output .= '</div><hr class="hr"><br>
  <div class="begintext"><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons License" class="commons" src="http://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">Matrix Tool</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://jellobrain.com" property="cc:attributionName" rel="cc:attributionURL">Ana Willem</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="http://jellobrain.com" rel="dct:source">http://jellobrain.com</a>.</div><hr class="hr">';

  return $output;
}

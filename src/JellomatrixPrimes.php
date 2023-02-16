<?php

namespace Drupal\jellomatrix;

/**
 * Description of JellomatrixPrimes
 *
 * @author eleven11
 */
class JellomatrixPrimes {
  
  /**
   * Returns the prime numbers.
   * name: jellomatrix_primes
   * @return = array()
   *
   **/
  public function getPrimes($tone) {

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
}

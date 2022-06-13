<?php

/**
  * Vessel
  * 
  * Description for the class
  * @property string $id used to identify the vessel
  * @property int $pairedVesselId used 
  * @property array $position Description for bar
*/
class Vessel
{
  public $id;
  public $pairedVesselId;
  public $position = [];

  public function __construct(string $id, array $position)
  {
    $this->id = $id;
    $this->position = $position;
  }
}

class OffensiveCraft extends Vessel
{
  public $numberOfCanons;
  public $shieldPower = 100;
  public $offensiveType = ['battleships', 'cruisers', 'destroyers'];
}

class SupportCraft extends Vessel
{
  public $name;
  public $medicUnit;
  public $supportType = ['refueling', 'mechanical assistance', 'cargo'];
}

/**
 * Fleet
 */
class Fleet
{
  public array $fleetLocationData = [];
  
  public int $offCrafts;
  public int $suppCrafts;
  public int $totalCrafts;
    
  /**
   * __construct
   *
   * @param  int $layoutSize
   * @param  int $offCrafts
   * @param  int $suppCrafts
   * @return void
  */
  public function __construct(int $layoutSize, int $offCrafts,  int $suppCrafts)
  {

    $this->offCrafts =  $offCrafts;
    $this->suppCrafts =  $suppCrafts;
    $this->totalCrafts = $offCrafts + $suppCrafts;

    $this->offCraftFleet = [];
    $this->suppCraftFleet = [];

    $layout = [];

    for ($i = 0; $i < $layoutSize; $i++) {
      array_push($layout, $i);
    }

    $random_x = array_rand($layout, $this->totalCrafts);
    $random_y = array_rand($layout, $this->totalCrafts);



    $offCraftCoordinates = [];
    $suppCraftCoordinates = [];

    for ($i = 0; $i < $this->totalCrafts/2; $i++) {

        $id = "OFF n°" . $i;
        $this->offCraftFleet[$i] = new OffensiveCraft($id, [$random_x[$i], $random_y[$i]]);
        array_push($offCraftCoordinates, ["id" => $id, "x" => $random_x[$i], "y" => $random_y[$i]]);
        
        $id = "SUPP n°" . $i;
        $this->suppCraftFleet[$i] = new SupportCraft($id, [$random_x[$i], $random_y[$i]]);
        array_push($suppCraftCoordinates, ["id" => $id, "x" => $random_x[$i], "y" => $random_y[$i]]);
      } 
    
    
    $this->fleetLocationData = array_merge($offCraftCoordinates, $suppCraftCoordinates);
    
    return $this->fleetLocationData;
    
  }

  /**
 * distance
 *
 * @param  array $v1
 * @param  array $v2
 * @return float
 */
public function distance(array $v1, array $v2) : float
{

  return sqrt(

    (($v1[0] - $v2[0]) * ($v1[0] - $v2[0])) + (($v1[1] - $v2[1]) * ($v1[1] - $v2[1]))

  );

  }

  //ensuite, on cherche le supp le plus proche
//on fait défiler tous les offCraft
//une fois trouvé, on le positionne à côté (si la position est à côté, distance() retourne 1 ou  racine de sqrt(2), les navires sont adjacents)
//on change la position du suppCraft en enlevant sa position de l'array $suppCraft et on le stocke 
// 

  public function pairing ()
  {
    $minDistance = null;
    $pair = null;

    for ($i = 0; $i < $this->offCrafts; $i++) {

      for ($j = 0; $j < $this->suppCrafts; $j++) {

        $distance = $this->distance($this->offCraftFleet[$i]->position, $this->suppCraftFleet[$j]->position);

        if ($distance == 1 || $distance == sqrt(2)) {

          $pair = $j;
          // $this->offCraft[$i]->pairedVesselId = $this->suppCraft[$j]->id;
          // $this->suppCraft[$j]->pairedVesselId = $this->offCraft[$i]->id;

        } else if ($j == 0){

          $pair = $j;
          $minDistance = $distance;
          
        } else if($distance < $minDistance) {

          $pair = $j;
          $minDistance = $distance;

        } 

      }

      $this->offCraftFleet[$i]->pairedVesselId = $this->suppCraftFleet[$pair]->id;
      $this->suppCraftFleet[$pair]->pairedVesselId = $this->offCraftFleet[$i]->id;  
    
    }
  }
}


$fleet = new Fleet (100, 5, 5);

$fleet->pairing();

 echo "<pre>";
 var_dump($fleet->suppCraftFleet);
 echo "</pre>";


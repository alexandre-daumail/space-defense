<?php

/**
 * Vessel
 *
 * This class is common to both Offensivecrafts and Supportcrafts classes because they share some properties.
 * 
 * @property string $id used to identify the differtent vessels
 * @property int $pairedVesselId stores the Vessel ID that each vessel has when we use the pairing method from Fleet class.
 * @property array $position stores the position from the vessel in the (x,y) layout.
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
  public $offensiveType ;
}

class SupportCraft extends Vessel
{
  public $name;
  public $medicUnit;
  public $supportType ;
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
   * 
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

    for ($i = 0; $i < $this->totalCrafts / 2; $i++) {

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
  public function distance(array $v1, array $v2): float
  {

    return sqrt(

      (($v1[0] - $v2[0]) * ($v1[0] - $v2[0])) + (($v1[1] - $v2[1]) * ($v1[1] - $v2[1]))

    );
  }

  /**
   * Pairing
   *
   * In a first loop, the algorithm goes through all the Offensivecrafts. The second loop processes first the distance between the current crafts if they are not paired yet. If distance() returns 1 or the square root of 2, the crafts are already positioned next to each other. If not, the algorithm continues to run and compares current offcraft to the other suppCrafts and stores the distance between them in $minDistance each time it's smaller then the existing $minDistance. During this process, the algo also store the index $j each time a new minDistance is stored.
   * 
   * @return void
   */
  public function pairing()
  {
    $minDistance = null;
    $pair = null;

    for ($i = 0; $i < $this->offCrafts; $i++) {

      for ($j = 0; $j < $this->suppCrafts; $j++) {

        if ($this->suppCraftFleet[$j]->pairedVesselId == null) {

          $distance = $this->distance($this->offCraftFleet[$i]->position, $this->suppCraftFleet[$j]->position);

          if ($distance == 1 || $distance == sqrt(2)) {

            $pair = $j;
          } else if ($j == 0) {

            $pair = $j;
            $minDistance = $distance;
          } else if ($distance < $minDistance) {

            $pair = $j;
            $minDistance = $distance;
          }
        

        $this->offCraftFleet[$i]->pairedVesselId = $this->suppCraftFleet[$pair]->id;
        $this->suppCraftFleet[$pair]->pairedVesselId = $this->offCraftFleet[$i]->id;
        }
      }
    }
  }
}


$fleet = new Fleet(100, 5, 5);

$fleet->pairing();

echo "<pre>";
var_dump($fleet->suppCraftFleet);
echo "</pre>";

<?php

namespace App\Controllers;

use App\Models\User;

class AddressUpdater extends BaseController
{
  private $googleApiKey = 'AIzaSyDTo-C-B9eHT9xgpyfc40tNuaT4gXx2eEA';

  public function updateAreas()
  {
    $userAddressModel = new User();
    $addresses = $userAddressModel->getAllAddresses();

    foreach ($addresses as $row) {
      $area = $this->getAreaFromGoogle($row['address']);
      if ($area) {
        $userAddressModel->updateArea($row['id'], ['area' => $area]);
        echo "Updated ID {$row['id']} with area: {$area}<br>";
      } else {
        echo "No area found for ID {$row['id']} ({$row['address']})<br>";
      }
    }
  }

  private function getAreaFromGoogle($address)
  {
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address="
      . urlencode($address) . "&key=" . $this->googleApiKey;

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!empty($data['results'][0]['address_components'])) {
      foreach ($data['results'][0]['address_components'] as $component) {
        if (in_array("sublocality_level_1", $component['types'])) {
          return $component['long_name'];
        }
      }
    }
    return null;
  }
}

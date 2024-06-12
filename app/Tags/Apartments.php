<?php
namespace App\Tags;
use App\Actions\GetData;
use Statamic\Tags\Tags;

class Apartments extends Tags
{
  public function index()
  {
  }

  public function get()
  {
    // get data from api or storage
    $data = (new GetData)->execute();

    // filter out items with "object_category":"APARTMENT"
    $data = collect($data)->filter(function ($item, $key) {
      return $item['object_category'] == 'APARTMENT';
    });

    // init array of apartments with buildings
    $apartments = ['building_1', 'building_2', 'building_3', 'building_4'];

    // group apartments by building
    $apartments = collect($data)->sortBy('floor')->groupBy(function ($item, $key) {
      $ref_house = substr($item['ref_house'], 0, 2);
      if (in_array($ref_house, ['01']))
      {
        return 'building_1';
      } 
      elseif (in_array($ref_house, ['02']))
      {
        return 'building_2';
      } 
    });

    // addresses
    $addresses = [
      'building_1' => 'Haus 1 – Rheinfelderstrasse 1',
      'building_2' => 'Haus 2 – Rheinfelderstrasse 3',
    ];

    $reference_date = [
      'building_1' => '1. September 2024',
      'building_2' => '1. Oktober 2024',
    ];

    return [
      'apartments' => $apartments,
      'addresses' => $addresses,
      'reference_date' => $reference_date,
    ];
  }
}

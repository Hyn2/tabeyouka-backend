<?php

// Load the Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Restaurant;
use Illuminate\Support\Facades\File;

// 이미지 다운로드 및 저장 함수
function download_and_save_image($url, $filename)
{
    $public_path = 'public/storage/restaurant_images';

    $image_content = file_get_contents($url);

    if (!File::exists($public_path)) {
        File::makeDirectory($public_path, 0755, true);
    }

    $full_path = $public_path . '/' . $filename;
    File::put($full_path, $image_content);

    return 'storage/restaurant_images/' . $filename;
}

// Get the contents of the restaurants.txt file
$contents = File::get('restaurant_data.txt');

// Split the contents into individual restaurant blocks
$restaurants = explode(PHP_EOL . PHP_EOL, $contents);

// Loop through each restaurant block
foreach ($restaurants as $restaurantData) {
  // Split the restaurant data into individual lines
  $restaurantLines = explode(PHP_EOL, $restaurantData);

  // Parse the restaurant data into a new restaurant model
  $restaurant = new Restaurant();
  foreach ($restaurantLines as $line) {
    $lineData = explode(': ', $line);
    $property = strtolower(str_replace(' ', '_', $lineData[0]));
    if (isset($lineData[1])) {
      $value = trim($lineData[1]);
      if ($property == "image_url" && filter_var($value, FILTER_VALIDATE_URL)) {
        // 이미지를 다운로드하고 저장한 후, 데이터베이스에 저장할 이미지 경로를 가져옵니다.
        $filename = basename($value);
        $image_path = download_and_save_image($value, $filename);
        $restaurant->image = $image_path;
      } elseif ($property != "image_url") {
        $restaurant->$property = $value;
      }
    }
  }

  // Save the new restaurant model to the database
  $restaurant->save();
}

echo "Data imported successfully!";

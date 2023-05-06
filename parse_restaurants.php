<?php

// Load the Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Restaurant;
use Illuminate\Support\Facades\File;

// Get the contents of the restaurants.txt file
$contents = File::get('restaurant_data.txt');

// Split the contetns into individual restaurant blocks
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
      $restaurant->$property = $value;
    }
  }

  // Save the new restaurant model to the database
  $restaurant->save();
}

echo "Data imported successfully!";
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Restaurant;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

function download_and_save_image($url, $filename)
{
    $folder_path = 'public/images/restaurant_images';
    $image_content = file_get_contents($url);

    Storage::put($folder_path . '/' . $filename, $image_content);

    // 백엔드의 도메인 또는 기본 URL 추가
    $baseURL = "http://localhost:8080";

    return $baseURL . Storage::url('images/restaurant_images/' . $filename);
}

$contents = File::get('restaurant_data.txt');
$restaurants = explode(PHP_EOL . PHP_EOL, $contents);

foreach ($restaurants as $restaurantData) {
    $restaurantLines = explode(PHP_EOL, $restaurantData);

    $restaurant = new Restaurant();
    foreach ($restaurantLines as $line) {
        $lineData = explode(': ', $line);
        $property = strtolower(str_replace(' ', '_', $lineData[0]));
        if (isset($lineData[1])) {
            $value = trim($lineData[1]);
            if ($property == "image_url" && filter_var($value, FILTER_VALIDATE_URL)) {
                $filename = basename($value);
                $image_path = download_and_save_image($value, $filename);
                $restaurant->image = $image_path;
            } elseif ($property != "image_url") {
                $restaurant->$property = $value;
            }
        }
    }

    $restaurant->save();
}

echo "Data imported successfully!";

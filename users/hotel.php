<?php
header('Content-Type: application/json');
require_once '../db.php';

$params = [
    'city' => strtolower($_GET['city'] ?? ''),
    'checkin' => $_GET['checkin'] ?? '',
    'checkout' => $_GET['checkout'] ?? '',
    'guests' => intval($_GET['guests'] ?? 2)
];

// In a real app, you would query your database
$hotels = [
    ['id' => 1, 'name' => 'Senggigi Beach Resort', 'stars' => 4, 'price' => 650000, 'city' => 'senggigi'],
    ['id' => 2, 'name' => 'Kuta Mandalika Inn', 'stars' => 3, 'price' => 420000, 'city' => 'kuta'],
    ['id' => 3, 'name' => 'Rinjani View Hotel', 'stars' => 5, 'price' => 880000, 'city' => 'senaru'],
    ['id' => 4, 'name' => 'Mataram City Hotel', 'stars' => 3, 'price' => 380000, 'city' => 'mataram']
];

// Filter hotels based on location
$filteredHotels = array_filter($hotels, fn($hotel) => 
    str_contains($hotel['city'], $params['city'])
);

echo json_encode(array_values($filteredHotels));
?>
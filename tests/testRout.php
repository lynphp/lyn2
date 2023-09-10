<?php
// Get the request URI path
$path = '/products/catalog/mens/shoes';

// Define the base directory of the routes subdirectory
$base_dir =  '../routes';

// Split the path into segments
$segments = explode('/', trim($path, '/'));

// Initialize the current directory as the base directory
$current_dir = $base_dir;

// Initialize an empty array to store the slug variables
$slug = array();

// Loop through the segments and check if they match any subdirectory or file
foreach ($segments as $segment) {
    // Get the full path of the current segment
    $full_path = $current_dir . '/' . $segment;

    // Check if the full path is a directory and if it exists
    if (is_dir($full_path) && file_exists($full_path)) {
        // Update the current directory to the full path
        $current_dir = $full_path;
    }
    // Check if the full path is a file and if it exists
    elseif (is_file($full_path) && file_exists($full_path)) {
        // Do something with the matching file, for example, include it
        echo $full_path;
        break;
    }
    // Otherwise, add the segment to the slug array
    else {
        $slug[] = $segment;
    }
}

// If no matching file was found, check if there is an mens.index.php file in the current directory
if (!isset($full_path) || !is_file($full_path)) {
    // Get the path of the mens.index.php file
    $index_path = $current_dir . '/mens.index.php';

    // Check if the mens.index.php file exists
    if (is_file($index_path) && file_exists($index_path)) {
        // Include the mens.index.php file and pass the slug array as a variable
        echo $index_path;
    }
}
var_dump($slug)
?>



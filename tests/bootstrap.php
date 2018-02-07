<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load test data to container

function loadTestData($dir = null)
{
    $index = [];
    $items = array_diff(scandir($dir), ['..', '.']);

    foreach ($items as $item) {
        if (is_dir($dir . DIRECTORY_SEPARATOR . $item)) {
            $children = loadTestData($dir . DIRECTORY_SEPARATOR . $item);
            foreach ($children as $key => $data) {
                $index[$item . '.' . $key] = $data;
            }
        } else {
            $path = pathinfo($item);
            $index[$path['filename']] = file_get_contents($dir . DIRECTORY_SEPARATOR . $item);
        }
    }
    return $index;
}

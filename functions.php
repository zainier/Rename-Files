<?php

/**
 * List all files in directory and subdirectories.
 *
 * @param  string  $dir_path  Path to directory
 *
 * @return array|false An array of files
 * @throws Exception If a path doesn't exist or isn't a readable directory
 */
function getListOfFiles(string $dir_path)
{
    if ( ! is_dir($dir_path) || ! is_readable($dir_path)) {
        throw new Exception("Failed to open " . "[" . $dir_path . "]" . " directory");
    }

    return getListOfFilesHelper($dir_path);
}

/**
 * Helper function for getListOfFiles.
 *
 * @param  string  $dir_path  Path to directory
 *
 * @return array An array of files
 */
function getListOfFilesHelper(string $dir_path): array
{
    static $files = [];
    $entries = scandir($dir_path);

    foreach ($entries as $entry) {
        if (in_array($entry, [".", ".."])) {
            continue;
        }

        $path_to_entry = $dir_path . DIRECTORY_SEPARATOR . $entry;

        if ( ! is_dir($path_to_entry)) {
            $files[] = $path_to_entry;
        } elseif (is_readable($path_to_entry)) {
            getListOfFilesHelper($path_to_entry);
        }
    }

    return $files;
}

/**
 * List all files in directory and subdirectories.
 * Use built-in RecursiveIteratorIterator.
 *
 * @param  string  $dir_path  Path to directory
 *
 * @return array An array of files
 * @throws Exception If a path doesn't exist or isn't a readable directory
 */
function getListOfFilesIter(string $dir_path): array
{
    if ( ! is_dir($dir_path) || ! is_readable($dir_path)) {
        throw new Exception("Failed to open " . "[" . $dir_path . "]" . " directory");
    }

    $dir      = new RecursiveDirectoryIterator($dir_path, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator(
            $dir,
            RecursiveIteratorIterator::LEAVES_ONLY,
            RecursiveIteratorIterator::CATCH_GET_CHILD
    );

    $files = [];

    foreach ($iterator as $item) {
        $files[] = $item->getPathname();
    }

    return $files;
}
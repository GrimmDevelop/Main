<?php

namespace Grimm\Controller\Admin\Files;

class Path {

    public function clean($path)
    {
        $path = str_replace('..', '', $path);
        $path = str_replace('./', '/', $path);
        while (strpos($path, '//') !== false)
        {
            $path = str_replace('//', '/', $path);
        }

        // Ensure that path begins with a slash
        if ($path[0] !== '/')
        {
            $path = '/' . $path;
        }

        $path = rtrim($path, '/');

        return $path;
    }

    /**
     * Creates an absoulte real path from the base path and the virtual path
     * @param  string $base Path to base directory
     * @param  string $path Virtual Path
     * @return string|bool       The generated absoulte path or false if file does not exist
     */
    public function resolveFilesystemPath($base, $path)
    {
        return realpath($base . $path);
    }
}
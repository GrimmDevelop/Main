<?php

namespace Grimm\Controller\Admin\Files;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class Storage {

    private $finder;

    private $basePath;
    private $baseUrl;

    private $path;
    private $fs;

    public function __construct(Finder $finder, Path $path, Filesystem $fs)
    {
        $this->finder = $finder;
        $this->path = $path;
        $this->fs = $fs;
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function setBaseUrl($baseURL)
    {
        $this->baseUrl = $baseURL;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Creates an array of all files and directories in $folder and collects
     * metadata
     * @param  string $folder
     * @return array
     */
    public function filesInFolder($folder)
    {
        $path = $this->buildPath($folder);

        if ($path === false)
        {
            throw new FileNotFoundException(sprintf('Folder "%s" could not be found.', $folder));
        }

        $files = $this->finder->in($path)->depth('<1');

        $finf = array();
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        foreach ($files as $file)
        {
            $type = "directory";
            if ($file->isFile())
            {
                $type = finfo_file($finfo, $file->getRealPath());
            }
            $finf[] = array(
                'filename' => $file->getFilename(),
                'path'     => $folder . '/' . $file->getFilename(),
                'url'      => $this->buildUrl($folder . '/' . $file->getFilename()),
                'isFile'   => $file->isFile(),
                'isDir'    => $file->isDir(),
                'size'     => $file->getSize(),
                'type'     => $type
            );
        }
        finfo_close($finfo);

        return $finf;
    }

    /**
     * Checks if a file does exist and returns the URL if it does
     * otherwise it fails with an exception
     * @param  string $file
     * @throws FileNotFoundException
     * @return string
     */
    public function lookupFile($file)
    {
        $path = $this->buildPath($file);

        if ($path === false)
        {
            throw new FileNotFoundException(sprintf('Folder "%s" could not be found.', $file));
        }

        return $this->buildUrl($file);
    }

    /**
     * Creates a directory, non recursively!
     * @param  string $path The path to the new directory
     * @throws Exception
     */
    public function mkdir($path)
    {
        $path = $this->basePath . $path;

        if (!mkdir($path))
        {
            throw new Exception("No Permissions to create directory!");
        }
    }

    /**
     * Moves src to dest without overriding existing contents
     * @param  string $src
     * @param  string $dest
     * @return bool
     */
    public function move($src, $dest)
    {
        $filename = $this->makeUniqueFilename(basename($src), $dest);

        return $this->fs->move($src, $dest . DIRECTORY_SEPARATOR . $filename);
    }

    public function rename($src, $dest)
    {
        return $this->fs->move($src, dirname($src) . DIRECTORY_SEPARATOR . $dest);
    }

    /**
     * Prefixes the given file name with the date if there is another file
     * with the same name at the given destination
     * @param  string $filename
     * @param  string $dest
     * @return string
     */
    public function makeUniqueFilename($filename, $dest)
    {
        if (file_exists($dest . DIRECTORY_SEPARATOR . $filename))
        {
            return date("dmy_His_") . $filename;
        }

        return $filename;
    }

    /**
     * Checks if a file does exist
     * @param  string $path Virtual path
     * @return bool
     */
    public function exists($path)
    {
        return $this->buildPath($path);
    }

    /**
     * Deletes the given file,
     * does not check for existence or path integrity!
     * Throws Exception on failure
     * @param  string $path path
     * @throws Exception
     */
    public function deleteFile($path)
    {
        if (!@unlink($path))
        {
            throw new Exception("Unable to delete file!");
        }
    }

    /**
     * Deletes the given folder and all its children,
     * does not check for existence or path integrity!
     * Throws Exception on failure
     * @param  string $path path
     * @throws Exception
     */
    public function deleteFolder($path)
    {
        if (!$this->fs->deleteDirectory($path, false))
        {
            throw new Exception("Unable to delete directory!");
        }
    }

    /**
     * Checks if the given file is in the virtual FS and exists
     * builds the absoulte path to the file
     * @param  string $file Virtual path
     * @return string|bool        absolute path to file, false if file does not exist
     */
    public function buildPath($file)
    {
        return $this->path->resolveFilesystemPath($this->basePath, $file);
    }

    /**
     * Builds an URL, which can be accessed via the browser to fetch an
     * asset in the virtual filesystem at $path
     * @param  string $path Virtual path
     * @return string       URL
     */
    public function buildUrl($path)
    {
        return $this->baseUrl . str_replace(" ", "%20", $path);
    }
}
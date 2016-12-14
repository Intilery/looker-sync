<?php namespace Intilery\Github;

/**
 * Class Copy
 * @package Intilery\Shell
 */
class Shell
{
    /**
     * @var array List of extensions to sync
     */
    private $extensions = [
        'lookml',
        'lkml'
    ];

    /**
     * @var array List of files to ignore
     */
    private $exclude = [
        'base_test.model.lkml'
    ];

    /**
     * @param $from
     * @param $to
     */
    public function flatCopy($from, $to)
    {
        $dir = new \DirectoryIterator($from);
        foreach ($dir as $file) {
            if (!$file->isDir() && 0 === stripos($file->getFilename(), 'base.') &&
                in_array($file->getExtension(), $this->extensions) &&
                !in_array($file->getFilename(), $this->exclude)
            ) {
                copy($file->getPathname(), $to . '/' . $file->getFilename());
            }
        }
    }

    /**
     * @param $dir
     */
    public function delete($dir)
    {
        if (!$this->exists($dir)) {
            return;
        } elseif (is_file($dir)) {
            unlink($dir);
        } else {
            rmdir($dir);
        }
    }

    /**
     * @param $dir
     * @return bool
     */
    public function exists($dir)
    {
        return file_exists($dir);
    }

    /**
     * @param $dirPath
     * @param array $replaces
     * @internal param $dir
     */
    public function findReplace($dirPath, $replaces = [])
    {
        $replaces = (array)$replaces;
        if (empty($replaces)) {
            return;
        }
        $dir = new \DirectoryIterator($dirPath);
        foreach ($dir as $file) {
            if (!$file->isDir() && 0 === stripos($file->getFilename(), 'base.') &&
                in_array($file->getExtension(), $this->extensions)
            ) {
                $contents = file_get_contents($file->getPathname());
                $contents = str_replace(array_keys($replaces), array_values($replaces), $contents);
                file_put_contents($file->getPathname(), $contents);
            }
        }
    }
}

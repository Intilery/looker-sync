<?php namespace Intilery\Github;

/**
 * Class Copy
 * @package Intilery\Shell
 * TODO Use the native PHP mechanisms for this, but this will do for now
 */
class Shell
{
    private $extensions = [
        'lookml',
        'lkml'
    ];

    private $exclude = [
    ];

    /**
     * @param $from
     * @param $to
     */
    public function flatCopy($from, $to)
    {
        exec('cp ' . $from . '\\* ' . $to . '/.');
    }

    /**
     * @param $dir
     */
    public function delete($dir)
    {
        exec('rm -rf ' . $dir);
    }

    /**
     * @param $dir
     * @param array $replaces
     */
    public function findReplace($dirPath, $replaces = [])
    {
	$replaces = (array) $replaces;
	if (empty($replaces)) {
		return;
	}
        $dir = new \DirectoryIterator($dirPath);
        foreach ($dir as $file) {
            if (!$file->isDir() && 0 === stripos($file->getFilename(), 'base.') && in_array($file->getExtension(), $this->extensions)) {
                $contents = file_get_contents($file->getPathname());
                $contents = str_replace(array_keys($replaces), array_values($replaces), $contents);
                file_put_contents($file->getPathname(), $contents);
            }
        }
    }
}

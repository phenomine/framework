<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Contracts\View;

use Latte\Strict;
use Phenomine\Support\File;

class Loader implements \Latte\Loader
{
    use Strict;

	protected ?string $baseDir = null;


	public function __construct(?string $baseDir = null)
	{
		$this->baseDir = $baseDir ? $baseDir : null;
	}


	/**
	 * Returns template source code.
	 */
	public function getContent(string $fileName): string
	{
		$file = $fileName;
		if (!is_file($file)) {
			throw new \Latte\RuntimeException("Missing template file '$file'.");

		} elseif ($this->isExpired($fileName, time())) {
			if (@touch($file) === false) {
				trigger_error("File's modification time is in the future. Cannot update it: " . error_get_last()['message'], E_USER_WARNING);
			}
		}

		return file_get_contents($file);
	}


	public function isExpired(string $file, int $time): bool
	{
		$mtime = @filemtime($this->baseDir . $file); // @ - stat may fail
		return !$mtime || $mtime > $time;
	}


	/**
	 * Returns referred template name.
	 */
	public function getReferredName(string $file, string $referringFile): string
	{
		if ($this->baseDir || !preg_match('#/|\\\\|[a-z][a-z0-9+.-]*:#iA', $file)) {
            $find = File::findFilesFromString($this->baseDir, $file, '.latte');
            if (!empty($find)) {
                throw new \Latte\RuntimeException("Template '$file' not found.");
            }
			//$file = $this->normalizePath($find);
            $file = $find;
		}

		return $file;
	}


	/**
	 * Returns unique identifier for caching.
	 */
	public function getUniqueId(string $file): string
	{
		return $this->baseDir . strtr($file, '/', DIRECTORY_SEPARATOR);
	}


	protected static function normalizePath(string $path): string
	{
		$res = [];
		foreach (explode('/', strtr($path, '\\', '/')) as $part) {
			if ($part === '..' && $res && end($res) !== '..') {
				array_pop($res);
			} elseif ($part !== '.') {
				$res[] = $part;
			}
		}

		return implode(DIRECTORY_SEPARATOR, $res);
	}
}

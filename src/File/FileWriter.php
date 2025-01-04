<?php
namespace plibv4\streams;
class FileWriter extends FileStream implements StreamWriter {
	const CREATE = 1;
	const APPEND = 2;
	CONST LAZY = 3;
	public function __construct(string $path, FWMode $mode) {
		if($mode === FWMode::APPEND) {
			if(!file_exists($path)) {
				throw new NoSuchFileException($path.": does not exist");
			}
			if(!is_writable($path)) {
				throw new NotWritableException(dirname($path).": not writable");
			}
			$this->open($path, "a");
		return;
		}
		if($mode === FWMode::LAZY) {
			if(file_exists($path) && !is_writable($path)) {
				throw new NotWritableException($path.": not writable");
			}
			$this->open($path, "a");
		return;
		}
		if(!file_exists(dirname($path))) {
			throw new NoSuchDirectoryException($path.": does not exist");
		}
		$this->open($path, "x");
	}
	
	public function write(string $data): int {
		$this->assertOpen();
		$written = fwrite($this->handle, $data);
		$this->checkError();
	return $written;
	}
}
<?php
namespace plibv4\streams;
class FileReader extends FileStream implements StreamReader {
	function __construct(string $path) {
		$this->usableFileCheck($path);
		if(!is_readable($path)) {
			throw new FileNotReadableException();
		}
		$this->open($path, "rb");
	}

	public function read(int $length): string {
		$this->assertOpen();
		return fread($this->handle, $length);
	}
}
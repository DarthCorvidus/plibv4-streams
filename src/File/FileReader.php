<?php
namespace plibv4\streams;
class FileReader extends FileStream implements StreamReader {
	function __construct(string $path) {
		parent::__construct($path);
		if(!is_readable($path)) {
			throw new FileNotReadableException();
		}
		$this->handle = fopen($path, "rb");
	}

	public function read(int $length): string {
		$this->assertOpen();
		return fread($this->handle, $length);
	}
}
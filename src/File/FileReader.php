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

	public function seek(int $offset, int $whence = self::SEEK_SET): void {
		$this->assertOpen();
		fseek($this->handle, $offset, $whence);
	}

	public function rewind(): void {
		$this->assertOpen();
		rewind($this->handle);
	}

	public function read(int $length): string {
		$this->assertOpen();
		return fread($this->handle, $length);
	}
}
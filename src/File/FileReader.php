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
		@fseek($this->handle, $offset, $whence);
		$this->checkError();
	}

	public function rewind(): void {
		$this->assertOpen();
		@rewind($this->handle);
		$this->checkError();
	}

	public function read(int $length): string {
		$this->assertOpen();
		$data =  @fread($this->handle, $length);
		$this->checkError();
	return $data;
	}
}
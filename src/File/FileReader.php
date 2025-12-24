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

	#[\Override]
	public function seek(int $offset, Seek $whence = Seek::SET): void {
		$this->assertOpen();
		@fseek($this->handle, $offset, $whence->value);
		$this->checkError();
	}

	#[\Override]
	public function rewind(): void {
		$this->assertOpen();
		@rewind($this->handle);
		$this->checkError();
	}

	#[\Override]
	public function read(int $length): string {
		$this->assertOpen();
		$data =  @fread($this->handle, $length);
		$this->checkError();
		/** @var string $data */
	return $data;
	}
}
<?php
namespace plibv4\streams;
abstract class FileStream implements Stream {
	/** 
	 * @var resource
	 * @psalm-suppress PropertyNotSetInConstructor
	 */
	protected mixed $handle;
	protected bool $closed = true;
	protected function usableFileCheck(string $path): void {
		if(!file_exists($path)) {
			throw new NoSuchFileException();
		}
		if(!is_file($path)) {
			throw new NotAFileException();
		}
	}
	
	protected function open(string $path, string $mode): void {
		$this->handle = fopen($path, $mode);
		$this->closed = false;
	}
	
	protected function assertOpen(): void {
		if($this->closed) {
			throw new StreamClosedException();
		}
	}
	
	public function isOpen(): bool {
		return !$this->closed;
	}

	public function close(): void {
		$this->assertOpen();
		/**
		 * @psalm-suppress InvalidPropertyAssignmentValue
		 */
		fclose($this->handle);
		$this->closed = true;
	}

	public function eof(): bool {
		$this->assertOpen();
		return feof($this->handle);
	}

	public function seek(int $offset, int $whence = self::SEEK_SET): void {
		$this->assertOpen();
		fseek($this->handle, $offset, $whence);
	}

	public function tell(): int {
		$this->assertOpen();
		return ftell($this->handle);
	}
	
	public function rewind(): void {
		$this->assertOpen();
		rewind($this->handle);
	}
	
	public function __destruct() {
		if(!$this->closed) {
			fclose($this->handle);
		}
	}
}
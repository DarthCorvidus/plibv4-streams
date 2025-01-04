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
	
	protected function checkError(): void {
		$error = error_get_last();
		if($error !== null) {
			error_clear_last();
			throw new StreamException($error["message"]);
		}
	}
	
	protected function open(string $path, string $mode): void {
		$this->handle = @fopen($path, $mode);
		$this->checkError();
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
		@fclose($this->handle);
		$this->checkError();
		$this->closed = true;
	}

	public function eof(): bool {
		$this->assertOpen();
		$feof = @feof($this->handle);
		$this->checkError();
	return $feof;
	}

	public function tell(): int {
		$this->assertOpen();
		$tell = @ftell($this->handle);
		$this->checkError();
	return $tell;
	}
	
	public function __destruct() {
		if(!$this->closed) {
			@fclose($this->handle);
			$this->checkError();
		}
	}
}
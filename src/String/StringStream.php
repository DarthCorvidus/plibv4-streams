<?php
namespace plibv4\streams;
abstract class StringStream implements Stream {
	protected string $string = "";
	protected int $pos = 0;
	protected bool $open = true;
	#[\Override]
	public function close(): void {
		$this->assertOpen();
		$this->open = false;
	}
	
	protected function assertOpen(): void {
		if($this->open) {
			return;
		}
	throw new StreamClosedException();
	}

	#[\Override]
	public function eof(): bool {
		$this->assertOpen();
		return strlen($this->string) === $this->pos;
	}

	#[\Override]
	public function tell(): int {
		$this->assertOpen();
		return $this->pos;
	}
}
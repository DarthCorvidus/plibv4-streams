<?php
namespace plibv4\streams;
class StringWriter extends StringStream implements StreamWriter {
	#[\Override]
	public function write(string $data): int {
		$this->assertOpen();
		$this->string .= $data;
		$written = strlen($data);
		$this->pos += $written;
	return $written;
	}
	
	public function getString(): string {
		return $this->string;
	}
	
	public function clear(): void {
		$this->pos = 0;
		$this->string = "";
	}
}
<?php
namespace plibv4\streams;
class StringReader extends StringStream implements StreamReader {
	function __construct(string $string) {
		$this->string = $string;
	}
	
	#[\Override]
	public function read(int $length): string {
		$this->assertOpen();
		$data = substr($this->string, $this->pos, $length);
		$this->pos += $length;
	return $data;
	}

	#[\Override]
	public function rewind(): void {
		$this->assertOpen();
		$this->pos = 0;
	}

	#[\Override]
	public function seek(int $offset, Seek $whence = Seek::SET): void {
		$this->assertOpen();
		if($whence === Seek::CUR) {
			$this->pos += $offset;
		return;
		}
	$this->pos = $offset;
	}
}
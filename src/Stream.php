<?php
namespace plibv4\streams;
interface Stream {
	const SEEK_CUR = SEEK_CUR;
	const SEEK_SET = SEEK_SET;
	const SEEK_END = SEEK_END;
	function seek(int $offset, int $whence = self::SEEK_SET): void;
	function tell(): int;
	function eof(): bool;
	function close(): void;
	function rewind(): void;
}

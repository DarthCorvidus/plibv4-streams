<?php
namespace plibv4\streams;
interface StreamReader extends Stream {
	const SEEK_CUR = SEEK_CUR;
	const SEEK_SET = SEEK_SET;
	const SEEK_END = SEEK_END;
	function read(int $length): string;
	function seek(int $offset, Seek $whence = Seek::CUR): void;
	function rewind(): void;
}
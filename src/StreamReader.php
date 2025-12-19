<?php
namespace plibv4\streams;
interface StreamReader extends Stream {
	function read(int $length): string;
	function seek(int $offset, Seek $whence = Seek::CUR): void;
	function rewind(): void;
}
<?php
namespace plibv4\streams;
interface StreamReader extends Stream {
	function read(int $length): string;
}
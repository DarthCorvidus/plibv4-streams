<?php
namespace plibv4\streams;
interface StreamWriter extends Stream {
	function write(string $string): int;
}
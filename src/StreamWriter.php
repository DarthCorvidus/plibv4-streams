<?php
namespace plibv4\streams;
interface StreamWriter extends Stream {
	function write(string $data): int;
}
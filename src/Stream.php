<?php
namespace plibv4\streams;
interface Stream {
	function tell(): int;
	function eof(): bool;
	function close(): void;
}

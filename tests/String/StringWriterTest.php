<?php
declare(strict_types=1);
namespace plibv4\streams;
use PHPUnit\Framework\TestCase;
class StringWriterTest extends TestCase {
	function testConstruct(): void {
		$sw = new StringWriter();
		$this->assertInstanceOf(StringWriter::class, $sw);
		$this->assertSame(0, $sw->tell());
	}
	
	function testWrite(): void {
		$sw = new StringWriter();
		$sw->write("Tom and Jerry");
		$this->assertSame(13, $sw->tell());
		$this->assertSame("Tom and Jerry", $sw->getString());
	}
	
	function testClear(): void {
		$sw = new StringWriter();
		$sw->write("Tom and Jerry");
		$sw->clear();
		$this->assertSame(0, $sw->tell());
		$this->assertSame("", $sw->getString());
	}

}

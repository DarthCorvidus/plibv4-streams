<?php
declare(strict_types=1);
namespace plibv4\streams;
use PHPUnit\Framework\TestCase;
class StringReaderTest extends TestCase {
	private string $example = "Tom and Jerry are merry, when the cat sleeps on a mat and the mouse wears a hat.";
	function testConstruct(): void {
		$sr = new StringReader($this->example);
		$this->assertInstanceOf(StringReader::class, $sr);
	}

	function testRead(): void {
		$sr = new StringReader($this->example);
		$string = $sr->read(13);
		$this->assertSame("Tom and Jerry", $string);
	}

	function testSeek(): void {
		$sr = new StringReader($this->example);
		$sr->seek(14);
		$this->assertEquals("are", $sr->read(3));
		$sr->seek(1, Seek::CUR);
		$this->assertEquals("merry", $sr->read(5));
	}
	
	function testTell(): void {
		$sr = new StringReader($this->example);
		$this->assertSame(0, $sr->tell());
		$sr->read(7);
		$this->assertSame(7, $sr->tell());
	}
	
	function testEof(): void {
		$sr = new StringReader("Tom");
		$this->assertSame(false, $sr->eof());
		$sr->read(3);
		$this->assertSame(true, $sr->eof());
	}
	
	function testRewind(): void {
		$sr = new StringReader($this->example);
		$sr->seek(15);
		$this->assertSame(15, $sr->tell());
		$sr->rewind();
		$this->assertSame(0, $sr->tell());
	}
	
	function testClosedRead(): void {
		$sr = new StringReader($this->example);
		$sr->close();
		$this->expectException(StreamClosedException::class);
		$sr->read(15);
	}

	function testClosedClose(): void {
		$sr = new StringReader($this->example);
		$sr->close();
		$this->expectException(StreamClosedException::class);
		$sr->close();
	}

	function testClosedEof(): void {
		$sr = new StringReader($this->example);
		$sr->close();
		$this->expectException(StreamClosedException::class);
		$sr->eof();
	}

	function testClosedSeek(): void {
		$sr = new StringReader($this->example);
		$sr->close();
		$this->expectException(StreamClosedException::class);
		$sr->seek(15);
	}

	function testClosedTell(): void {
		$sr = new StringReader($this->example);
		$sr->close();
		$this->expectException(StreamClosedException::class);
		$sr->tell();
	}
	
	function testClosedRewind(): void {
		$sr = new StringReader($this->example);
		$sr->close();
		$this->expectException(StreamClosedException::class);
		$sr->rewind();
	}
}

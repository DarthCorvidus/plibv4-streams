<?php
declare(strict_types=1);
namespace plibv4\streams;
use PHPUnit\Framework\TestCase;
final class FileReaderTest extends TestCase {
	private mixed $handle = null;
	#[\Override]
	static function setUpbeforeClass(): void {
		$dir = "/tmp/phpunit/plibv4/streams/";
		if(!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		file_put_contents($dir."/example01.txt", "The cat is on the mat and the mouse wears a hat.");
		file_put_contents($dir."/example02.txt", "Tom and Jerry today are merry.");
	}
	
	#[\Override]
	static function tearDownAfterClass(): void {
		$dir = "/tmp/phpunit/plibv4/streams/";
		if(file_exists($dir."/example01.txt")) {
			unlink($dir."/example01.txt");
		}

		if(file_exists($dir."/example02.txt")) {
			unlink($dir."/example02.txt");
		}
		rmdir($dir);
	}
	
	#[\Override]
	function tearDown(): void {
		if(is_resource($this->handle)) {
			fclose($this->handle);
		}
	}
	
	function testConstruct(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->close();
		$this->assertInstanceOf(FileReader::class, $fr);
	}

	function testConstructDirectory(): void {
		$this->expectException(NotAFileException::class);
		new FileReader("/tmp/phpunit/plibv4/streams/");
	}
	
	function testConstructMissing(): void {
		$this->expectException(NoSuchFileException::class);
		new FileReader("/tmp/phpunit/plibv4/streams/example03.txt");
	}
	
	function testConstructNotReadable(): void {
		$this->expectException(FileNotReadableException::class);
		new FileReader("/etc/shadow");
	}
	
	function testRead(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$string = $fr->read(7);
		$this->assertSame("The cat", $string);
	}

	function testSeek(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->seek(4);
		$string = $fr->read(3);
		$this->assertEquals("cat", $string);
		$fr->seek(4);
		$fr->seek(4, Seek::CUR);
		$string = $fr->read(2);
		$this->assertEquals("is", $string);
	}
	
	function testTell(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$string = $fr->read(7);
		$this->assertSame(7, $fr->tell());
	}
	
	function testEof(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$string = $fr->read(47);
		$this->assertSame("The cat is on the mat and the mouse wears a hat", $string);
		$this->assertSame(false, $fr->eof());
		$dot = $fr->read(2);
		$this->assertSame(".", $dot);
		$this->assertSame(true, $fr->eof());
	}
	
	function testRewind(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->seek(15);
		$this->assertSame(15, $fr->tell());
		$fr->rewind();
		$this->assertSame(0, $fr->tell());
		
	}
	
	function testClose(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$this->assertSame(true, $fr->isOpen());
		$fr->close();
		$this->assertSame(false, $fr->isOpen());
	}
	
	function testClosedRead(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->close();
		$this->expectException(StreamClosedException::class);
		$fr->read(15);
	}
	
	function testClosedClose(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->close();
		$this->expectException(StreamClosedException::class);
		$fr->close();
	}

	function testClosedEof(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->close();
		$this->expectException(StreamClosedException::class);
		$fr->eof();
	}

	function testClosedSeek(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->close();
		$this->expectException(StreamClosedException::class);
		$fr->seek(15);
	}

	function testClosedTell(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->close();
		$this->expectException(StreamClosedException::class);
		$fr->tell();
	}
	
	function testClosedRewind(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$fr->close();
		$this->expectException(StreamClosedException::class);
		$fr->rewind();
	}
	
	function testStreamExceptionRead(): void {
		$fr = new FileReader("/tmp/phpunit/plibv4/streams/example01.txt");
		$this->handle = fopen("/tmp/phpunit/plibv4/streams/example02.txt", "a");
		//fclose($this->handle);
		$reflection = new \ReflectionClass($fr);
		$property = $reflection->getProperty("handle");
		$property->setValue($fr, $this->handle);
		$this->expectException(StreamException::class);
		$fr->read(15);
	}
}

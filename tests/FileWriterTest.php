<?php
declare(strict_types=1);
namespace plibv4\streams;
use PHPUnit\Framework\TestCase;
final class FileWriterTest extends TestCase {
	#[\Override]
	static function setUpbeforeClass(): void {
		$dir = "/tmp/phpunit/plibv4/streams/";
		if(!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
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
		$dir = "/tmp/phpunit/plibv4/streams/";
		if(file_exists($dir."/example01.txt")) {
			unlink($dir."/example01.txt");
		}

		if(file_exists($dir."/example02.txt")) {
			unlink($dir."/example02.txt");
		}
	}
	
	function testConstructNew(): void {
		$fw = new FileWriter("/tmp/phpunit/plibv4/streams/example01.txt", FWMode::NEW);
		$this->assertInstanceOf(FileWriter::class, $fw);
		$this->assertFileExists("/tmp/phpunit/plibv4/streams/example01.txt");
	}

	function testConstructAppend(): void {
		touch("/tmp/phpunit/plibv4/streams/example01.txt");
		$fw = new FileWriter("/tmp/phpunit/plibv4/streams/example01.txt", FWMode::APPEND);
		$this->assertInstanceOf(FileWriter::class, $fw);
		$this->assertFileExists("/tmp/phpunit/plibv4/streams/example01.txt");
	}
	
	function testConstructLazyAppend(): void {
		touch("/tmp/phpunit/plibv4/streams/example01.txt");
		$fw = new FileWriter("/tmp/phpunit/plibv4/streams/example01.txt", FWMode::LAZY);
		$this->assertInstanceOf(FileWriter::class, $fw);
		$this->assertFileExists("/tmp/phpunit/plibv4/streams/example01.txt");
	}

	function testConstructLazyNew(): void {
		$fw = new FileWriter("/tmp/phpunit/plibv4/streams/example01.txt", FWMode::LAZY);
		$this->assertInstanceOf(FileWriter::class, $fw);
		$this->assertFileExists("/tmp/phpunit/plibv4/streams/example01.txt");
	}
	
	
	function testConstructNoDirectory():void {
		$this->expectException(NoSuchDirectoryException::class);
		new FileWriter("/tmp/phpunit/plibv4/streamx/example01.txt", FWMode::NEW);
	}
	

	function testConstructNoWritableFile(): void {
		$this->expectException(NotWritableException::class);
		touch("/tmp/phpunit/plibv4/streams/example01.txt");
		chmod("/tmp/phpunit/plibv4/streams/example01.txt", 0444);
		new FileWriter("/tmp/phpunit/plibv4/streams/example01.txt", FWMode::APPEND);
	}
	
	function testWrite(): void {
		$dir = "/tmp/phpunit/plibv4/streams/example01.txt";
		$fw = new FileWriter($dir, FWMode::LAZY);
		$expected = "The cat is on the mat.";
		$this->assertSame(4, $fw->write("The "));
		$this->assertSame(18, $fw->write("cat is on the mat."));
		$fw->close();
		$this->assertSame($expected, file_get_contents($dir));
	}
	
	function testWriteAfterClose(): void {
		$dir = "/tmp/phpunit/plibv4/streams/example01.txt";
		$fw = new FileWriter($dir, FWMode::LAZY);
		$expected = "The cat is on the mat.";
		$this->assertSame(4, $fw->write("The "));
		$fw->close();
		$this->expectException(StreamClosedException::class);
		$this->assertSame(18, $fw->write("cat is on the mat."));
	}
}

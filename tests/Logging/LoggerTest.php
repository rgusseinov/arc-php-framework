<?php

declare(strict_types=1);

namespace Tests\Logging;

use Arc\Framework\Logging\Logger;
use Arc\Framework\Logging\LogLevel;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;
use Stringable;

final class LoggerTest extends TestCase
{
    private string $logPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logPath = sys_get_temp_dir() . '/arc_logger_test_' . uniqid() . '.log';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->logPath)) {
            unlink($this->logPath);
        }

        parent::tearDown();
    }

    public function testMessageBelowMinLevelIsNotWritten(): void
    {
        $logger = new Logger($this->logPath, LogLevel::Warning);

        $logger->debug('debug message');
        $logger->info('info message');

        $this->assertFileDoesNotExist($this->logPath);
    }

    public function testMessageAtMinLevelAndAboveIsWritten(): void
    {
        $logger = new Logger($this->logPath, LogLevel::Warning);

        $logger->warning('warning message');
        $logger->critical('critical message');

        $content = file_get_contents($this->logPath);

        $this->assertStringContainsString('[warning]', $content);
        $this->assertStringContainsString('warning message', $content);
        $this->assertStringContainsString('[critical]', $content);
        $this->assertStringContainsString('critical message', $content);
    }

    public function testContextIsInterpolatedIntoMessage(): void
    {
        $logger = new Logger($this->logPath, LogLevel::Debug);

        $logger->error('User {id} not found', ['id' => 42]);

        $content = file_get_contents($this->logPath);

        $this->assertStringContainsString('User 42 not found', $content);
        $this->assertStringNotContainsString('{id}', $content);
    }

    public function testStringableContextValueIsInterpolated(): void
    {
        $logger = new Logger($this->logPath, LogLevel::Debug);

        $value = new class () implements Stringable {
            public function __toString(): string
            {
                return 'OBJ_VALUE';
            }
        };

        $logger->warning('Object: {val}', ['val' => $value]);

        $this->assertStringContainsString('Object: OBJ_VALUE', file_get_contents($this->logPath));
    }

    public function testLogWithValidLevelStringWritesMessage(): void
    {
        $logger = new Logger($this->logPath, LogLevel::Debug);

        $logger->log('notice', 'via log()');

        $content = file_get_contents($this->logPath);

        $this->assertStringContainsString('[notice]', $content);
        $this->assertStringContainsString('via log()', $content);
    }

    public function testLogWithUnknownLevelThrowsPsrException(): void
    {
        $logger = new Logger($this->logPath, LogLevel::Debug);

        $this->expectException(InvalidArgumentException::class);

        $logger->log('not_a_level', 'message');
    }
}

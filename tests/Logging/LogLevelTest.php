<?php

namespace Tests\Logging;

use Arc\Framework\Logging\LogLevel;
use PHPUnit\Framework\TestCase;

final class LogLevelTest extends TestCase
{
    public function testTryFromReturnsNullForUnknownLevel(): void
    {
        $this->assertNull(LogLevel::tryFrom('bug_level'));
        $this->assertNull(LogLevel::tryFrom('INFO'));
    }

    public function testTryFromMapsPsr3Strings(): void
    {
        $this->assertSame(LogLevel::Info, LogLevel::tryFrom('info'));
        $this->assertSame(LogLevel::Warning, LogLevel::tryFrom('warning'));
        $this->assertSame(LogLevel::Emergency, LogLevel::tryFrom('emergency'));
        $this->assertSame(LogLevel::Alert, LogLevel::tryFrom('alert'));
        $this->assertSame(LogLevel::Critical, LogLevel::tryFrom('critical'));
        $this->assertSame(LogLevel::Error, LogLevel::tryFrom('error'));
        $this->assertSame(LogLevel::Notice, LogLevel::tryFrom('notice'));
        $this->assertSame(LogLevel::Debug, LogLevel::tryFrom('debug'));
    }

    public function testPrioritiesIncreaseInPsr3Order(): void
    {
        $ordered = [
                LogLevel::Debug,
                LogLevel::Info,
                LogLevel::Notice,
                LogLevel::Warning,
                LogLevel::Error,
                LogLevel::Critical,
                LogLevel::Alert,
                LogLevel::Emergency,
        ];

        for ($i = 1; $i < count($ordered); $i++) {
            $this->assertLessThan(
                $ordered[$i]->getPriority(),
                $ordered[$i - 1]->getPriority()
            );
        }
    }

    public function testPriorityBoundaryValues(): void
    {
        $this->assertSame(0, LogLevel::Debug->getPriority());
        $this->assertSame(7, LogLevel::Emergency->getPriority());
    }
}

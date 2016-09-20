<?php

namespace Netpromotion\Profiler\Test;

use Netpromotion\Profiler\Adapter\TracyBarAdapter;
use Netpromotion\Profiler\Profiler;

class TracyBarAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testGetTabWorks()
    {
        $adapter = TracyBarAdapter::create();

        $this->assertContains("disabled", $adapter->getTab());

        Profiler::enable();
        $this->assertContains("0 profiles", $adapter->getTab());

        Profiler::start();
        Profiler::finish();
        $this->assertContains("1 profile", $adapter->getTab());

        Profiler::start();
        Profiler::finish();
        $this->assertContains("2 profiles", $adapter->getTab());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetPanelWorks()
    {
        $adapter = TracyBarAdapter::create();

        $this->assertContains("disabled", $adapter->getPanel());

        Profiler::enable();
        $this->assertEquals(1, substr_count($adapter->getPanel(), "<tr>"));

        Profiler::start();
        Profiler::finish();
        $this->assertEquals(2, substr_count($adapter->getPanel(), "<tr>"));

        Profiler::start();
        Profiler::finish();
        $this->assertEquals(3, substr_count($adapter->getPanel(), "<tr>"));
    }
}
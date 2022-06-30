<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Tests \Xylemical\Discovery\Discovery.
 */
class DiscoveryTest extends TestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $discovery = new Discovery();
    $this->assertEquals([], $discovery->discover());

    $d1 = $this->prophesize(DiscoveryInterface::class);
    $d1->discover()->willReturn([
      'A' => new ClassSource('A'),
    ]);
    $discovery->addDiscovery($d1->reveal());

    $d2 = $this->prophesize(DiscoveryInterface::class);
    $d2->discover()->willReturn([
      'A' => (new InterfaceSource('A')),
      'B' => new ClassSource('B'),
    ]);
    $discovery->addDiscovery($d2->reveal());

    $this->assertEquals([
      'A' => new ClassSource('A'),
      'B' => new ClassSource('B'),
    ], $discovery->discover());
  }

}

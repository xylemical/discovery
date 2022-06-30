<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Source;

use PHPUnit\Framework\TestCase;
use Xylemical\Discovery\Source;
use Xylemical\Discovery\SourceInterface;
use function array_keys;

/**
 * Tests \Xylemical\Discovery\Source\SourceDependencies.
 */
class SourceDependenciesTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $sources = [
      'CA' => (new Source(SourceInterface::TYPE_CLASS, 'CA'))
        ->setClasses(['CD'])
        ->setInterfaces(['IA', 'IB'])
        ->setTraits(['TA', 'TB']),
      'CB' => (new Source(SourceInterface::TYPE_CLASS, 'CB')),
      'CC' => (new Source(SourceInterface::TYPE_CLASS, 'CC')),
      'CD' => (new Source(SourceInterface::TYPE_CLASS, 'CD'))
        ->setClasses(['CB']),
      'CE' => (new Source(SourceInterface::TYPE_CLASS, 'CE')),
      'IA' => (new Source(SourceInterface::TYPE_INTERFACE, 'IA'))
        ->setInterfaces(['IC', 'ID']),
      'IB' => (new Source(SourceInterface::TYPE_INTERFACE, 'IB')),
      'IC' => (new Source(SourceInterface::TYPE_INTERFACE, 'IC')),
      'ID' => (new Source(SourceInterface::TYPE_INTERFACE, 'ID'))
        ->setInterfaces(['IB']),
      'IE' => (new Source(SourceInterface::TYPE_INTERFACE, 'IE')),
      'TA' => (new Source(SourceInterface::TYPE_TRAIT, 'TA'))
        ->setTraits(['TC', 'TD']),
      'TB' => (new Source(SourceInterface::TYPE_TRAIT, 'TB')),
      'TC' => (new Source(SourceInterface::TYPE_TRAIT, 'TC')),
      'TD' => (new Source(SourceInterface::TYPE_TRAIT, 'TD'))
        ->setTraits(['TB']),
      'TE' => (new Source(SourceInterface::TYPE_TRAIT, 'TE')),
    ];

    $dependencies = new SourceDependencies();
    $result = $dependencies->generate($sources);
    $this->assertEquals([
      'CB',
      'CD',
      'IA',
      'IB',
      'IC',
      'ID',
      'TA',
      'TB',
      'TC',
      'TD',
    ], array_keys($result));
    $this->assertEquals(['CD'], array_keys($result['CB']));
    $this->assertEquals(['CA'], array_keys($result['CD']));
    $this->assertEquals(['CA'], array_keys($result['IA']));
    $this->assertEquals(['CA', 'ID'], array_keys($result['IB']));
    $this->assertEquals(['IA'], array_keys($result['IC']));
    $this->assertEquals(['IA'], array_keys($result['ID']));
    $this->assertEquals(['CA'], array_keys($result['TA']));
    $this->assertEquals(['CA', 'TD'], array_keys($result['TB']));
    $this->assertEquals(['TA'], array_keys($result['TC']));
    $this->assertEquals(['TA'], array_keys($result['TD']));
  }

}

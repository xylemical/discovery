<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Source;

use PHPUnit\Framework\TestCase;
use Xylemical\Discovery\ClassSource;
use Xylemical\Discovery\InterfaceSource;
use Xylemical\Discovery\TraitSource;
use function array_keys;

/**
 * Tests \Xylemical\Discovery\Source\SourceDependents.
 */
class SourceDependentsTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $sources = [
      'CA' => (new ClassSource('CA'))
        ->setClasses(['CD'])
        ->setInterfaces(['IA', 'IB'])
        ->setTraits(['TA', 'TB']),
      'CB' => (new ClassSource('CB')),
      'CC' => (new ClassSource('CC')),
      'CD' => (new ClassSource('CD'))
        ->setClasses(['CB']),
      'CE' => (new ClassSource('CE')),
      'IA' => (new InterfaceSource('IA'))
        ->setInterfaces(['IC', 'ID']),
      'IB' => (new InterfaceSource('IB')),
      'IC' => (new InterfaceSource('IC')),
      'ID' => (new InterfaceSource('ID'))
        ->setInterfaces(['IB']),
      'IE' => (new InterfaceSource('IE')),
      'TA' => (new TraitSource('TA'))
        ->setTraits(['TC', 'TD']),
      'TB' => (new TraitSource('TB')),
      'TC' => (new TraitSource('TC')),
      'TD' => (new TraitSource('TD'))
        ->setTraits(['TB']),
      'TE' => (new TraitSource('TE')),
    ];

    $dependencies = new SourceDependents();
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

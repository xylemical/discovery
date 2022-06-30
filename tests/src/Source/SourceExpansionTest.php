<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Source;

use PHPUnit\Framework\TestCase;
use Xylemical\Discovery\ClassSource;
use Xylemical\Discovery\InterfaceSource;
use Xylemical\Discovery\TraitSource;

/**
 * Tests \Xylemical\Discovery\Source\SourceExpansion.
 */
class SourceExpansionTest extends TestCase {

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

    $expansion = new SourceExpansion();
    $results = $expansion->expand($sources);

    $result = $results['CA'];
    $this->assertEquals(['CD', 'CB'], $result->getClasses());
    $this->assertEquals(['IA', 'IB', 'IC', 'ID'], $result->getInterfaces());
    $this->assertEquals(['TA', 'TB', 'TC', 'TD'], $result->getTraits());
  }

}

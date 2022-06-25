<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Source;

use PHPUnit\Framework\TestCase;
use Xylemical\Discovery\Source;
use Xylemical\Discovery\SourceInterface;

/**
 * Tests \Xylemical\Discovery\Source\SourceExpansion.
 */
class SourceExpansionTest extends TestCase {

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

    $expansion = new SourceExpansion();
    $results = $expansion->expand($sources);

    $result = $results['CA'];
    $this->assertEquals(['CD', 'CB'], $result->getClasses());
    $this->assertEquals(['IA', 'IB', 'IC', 'ID'], $result->getInterfaces());
    $this->assertEquals(['TA', 'TB', 'TC', 'TD'], $result->getTraits());
  }

}

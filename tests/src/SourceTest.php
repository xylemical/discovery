<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

use PHPUnit\Framework\TestCase;

/**
 * Tests \Xylemical\Discovery\Source.
 */
class SourceTest extends TestCase {

  /**
   * Provides the test data for testParse().
   *
   * @return array
   *   The test data.
   */
  public function providerTestParse(): array {
    return [
      [
        "",
        [],
      ],
      [
        "{",
        [],
      ],
      [
        "class Test { public function test() { \$c = new class {}; } }",
        [
          'Test' => (new ClassSource('Test')),
        ],
      ],
      [
        "use X\Test as DefaultTest; class Test extends DefaultTest implements Source, Code { use Dummy; }",
        [
          'Test' => (new ClassSource('Test'))
            ->setClasses(['X\Test'])
            ->setInterfaces(['Source', 'Code'])
            ->setTraits(['Dummy']),
        ],
      ],
      [
        "namespace Foo; class Test { use \Foo; use \Bar; }",
        [
          'Foo\Test' => (new ClassSource('Foo\Test'))
            ->setTraits(['Foo', 'Bar']),
        ],
      ],
      [
        "namespace Foo; interface Test extends \Source, \Code { }",
        [
          'Foo\Test' => (new InterfaceSource('Foo\Test'))
            ->setInterfaces(['Source', 'Code']),
        ],
      ],
      [
        "namespace Foo; trait Test { use \Source; use \Code; }",
        [
          'Foo\Test' => (new TraitSource('Foo\Test'))
            ->setTraits(['Source', 'Code']),
        ],
      ],
    ];
  }

  /**
   * Tests the parse capabilities of the source.
   *
   * @dataProvider providerTestParse
   */
  public function testParse(string $contents, array $expected): void {
    $factory = new SourceFactory();
    $result = Source::parse($factory, '<?' . "php\n $contents");
    $this->assertEquals($expected, $result);
  }

}

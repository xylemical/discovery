<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Directory;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Xylemical\Discovery\ClassSource;
use Xylemical\Discovery\InterfaceSource;
use Xylemical\Discovery\SourceFactory;

/**
 * Tests \Xylemical\Discovery\Directory\NamespaceDiscovery.
 */
class NamespaceDiscoveryTest extends TestCase {

  /**
   * The root test.
   *
   * @var \org\bovigo\vfs\vfsStreamDirectory
   */
  protected vfsStreamDirectory $root;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    $this->root = vfsStream::setup('root', NULL, $this->getStructure());
  }

  /**
   * Test basic operations.
   */
  public function testSanity(): void {
    $factory = new SourceFactory();
    $discovery = new NamespaceDiscovery($factory, '', $this->root->url() . '/lib');
    $results = $discovery->discover();
    $this->assertEquals([
      'A\\A\\A' => (new ClassSource('A\\A\\A'))->setAbstract(TRUE),
      'A\\B\\A' => new InterfaceSource('A\\B\\A'),
    ], $results);
    // @phpstan-ignore-next-line
    $this->assertTrue($results['A\\A\\A']->isAbstract());

    $discovery = new NamespaceDiscovery($factory, '\\Prefix\\For\\', $this->root->url() . '/src');
    $results = $discovery->discover();
    $this->assertEquals([
      'Prefix\\For\\A\\A\\A' => (new ClassSource('Prefix\\For\\A\\A\\A'))->setAbstract(TRUE),
      'Prefix\\For\\A\\B\\A' => new InterfaceSource('Prefix\\For\\A\\B\\A'),
    ], $results);
    // @phpstan-ignore-next-line
    $this->assertTrue($results['Prefix\\For\\A\\A\\A']->isAbstract());
  }

  /**
   * Get the filesystem structure for testing.
   *
   * @return array
   *   The structure.
   */
  protected function getStructure(): array {
    return [
      'src' => [
        'A' => [
          'A' => [
            'A.php' => $this->getClass('abstract class', 'Prefix\\For\\A\\A', 'A'),
            'B.php' => $this->getClass('class', 'Prefix\\For\\A\\A', 'C'),
          ],
          'B' => [
            'A.php' => $this->getClass('interface', 'Prefix\\For\\A\\B', 'A'),
          ],
        ],
      ],
      'lib' => [
        'A' => [
          'A' => [
            'A.php' => $this->getClass('abstract class', 'A\\A', 'A'),
            'B.php' => $this->getClass('class', 'A\\A', 'C'),
          ],
          'B' => [
            'A.php' => $this->getClass('interface', 'A\\B', 'A'),
          ],
        ],
      ],
    ];
  }

  /**
   * Get the PHP file contents for an object.
   *
   * @param string $type
   *   The object type.
   * @param string $namespace
   *   The namespace.
   * @param string $name
   *   The object name.
   *
   * @return string
   *   The file contents.
   */
  protected function getClass(string $type, string $namespace, string $name): string {
    return '<?' . "php\nnamespace {$namespace};\n{$type} {$name} {  }\n";
  }

}

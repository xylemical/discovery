<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Directory;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Xylemical\Discovery\Source;
use Xylemical\Discovery\SourceFactory;
use Xylemical\Discovery\SourceInterface;

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
    $discovery = new NamespaceDiscovery($factory, '', $this->root->url());
    $results = $discovery->discover();
    $this->assertEquals([
      'A\A\A' => new Source(SourceInterface::TYPE_CLASS, 'A\A\A'),
      'A\B\A' => new Source(SourceInterface::TYPE_INTERFACE, 'A\B\A'),
    ], $results);
  }

  /**
   * Get the filesystem structure for testing.
   *
   * @return array
   *   The structure.
   */
  protected function getStructure(): array {
    return [
      'A' => [
        'A' => [
          'A.php' => $this->getClass('class', 'A\A', 'A'),
          'B.php' => $this->getClass('class', 'A\A', 'C'),
        ],
        'B' => [
          'A.php' => $this->getClass('interface', 'A\B', 'A'),
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

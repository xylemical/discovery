<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Directory;

use Xylemical\Discovery\DiscoveryInterface;
use Xylemical\Discovery\Source;
use Xylemical\Discovery\SourceFactoryInterface;
use Xylemical\Discovery\SourceInterface;
use function file_get_contents;
use function str_replace;
use function substr;

/**
 * Provides a namespace discover object.
 */
class NamespaceDiscovery implements DiscoveryInterface {

  /**
   * The source factory.
   *
   * @var \Xylemical\Discovery\SourceFactoryInterface
   */
  protected SourceFactoryInterface $factory;

  /**
   * The base namespace.
   *
   * @var string
   */
  protected string $namespace;

  /**
   * The base directory.
   *
   * @var string
   */
  protected string $directory;

  /**
   * NamespaceDiscover constructor.
   *
   * @param \Xylemical\Discovery\SourceFactoryInterface $factory
   *   The factory.
   * @param string $namespace
   *   The namespace.
   * @param string $directory
   *   The directory.
   */
  public function __construct(SourceFactoryInterface $factory, string $namespace, string $directory) {
    $this->factory = $factory;
    $this->namespace = trim($namespace, '\\');
    $this->directory = $directory;
  }

  /**
   * {@inheritdoc}
   */
  public function discover(): array {
    $results = [];

    $length = strlen($this->directory) + 1;

    $iterator = new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::CURRENT_AS_PATHNAME | \FilesystemIterator::UNIX_PATHS);
    $iterator = new \RecursiveIteratorIterator($iterator);
    $iterator = new \RegexIterator($iterator, '#.*?\.php$#');
    foreach ($iterator as $path) {
      if ($source = $this->getSource($length, $path)) {
        $results[$source->getName()] = $source;
      }
    }
    return $results;
  }

  /**
   * Get a source that matches the expected namespace and class path.
   *
   * @param int $length
   *   The root path length.
   * @param string $path
   *   The filename.
   *
   * @return \Xylemical\Discovery\SourceInterface|null
   *   The source or NULL.
   */
  protected function getSource(int $length, string $path): ?SourceInterface {
    $class = str_replace('/', '\\', substr($path, $length, -4));
    $expected = trim($this->namespace . '\\' . $class, '\\');

    $sources = Source::parse($this->factory, file_get_contents($path));
    foreach ($sources as $source) {
      if ($source->getName() === $expected) {
        return $source;
      }
    }
    return NULL;
  }

}

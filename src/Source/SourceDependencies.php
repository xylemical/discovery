<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Source;

/**
 * Generates the source dependency tree.
 */
class SourceDependencies {

  /**
   * Generate the source dependency tree.
   *
   * @param \Xylemical\Discovery\SourceInterface[] $sources
   *   The sources.
   *
   * @return \Xylemical\Discovery\SourceInterface[][]
   *   The tree, indexed by dependent values.
   */
  public function generate(array $sources): array {
    $tree = [];
    foreach ($sources as $source) {
      foreach ($source->getInterfaces() as $interface) {
        $tree[$interface][$source->getName()] = $source;
      }
      foreach ($source->getTraits() as $trait) {
        $tree[$trait][$source->getName()] = $source;
      }
      foreach ($source->getClasses() as $class) {
        $tree[$class][$source->getName()] = $source;
      }
    }
    ksort($tree);
    foreach ($tree as &$value) {
      ksort($value);
    }
    return $tree;
  }

}

<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Source;

use Xylemical\Discovery\SourceInterface;
use function array_merge;

/**
 * Expands all the sources to include entire object dependencies.
 */
class SourceExpansion {

  /**
   * Expands all the sources to include all children in hierarchy.
   *
   * @param \Xylemical\Discovery\SourceInterface[] $sources
   *   All the sources.
   *
   * @return \Xylemical\Discovery\SourceInterface[]
   *   Expanded sources.
   */
  public function expand(array $sources): array {
    $completed = [];
    foreach (array_keys($sources) as $source) {
      $this->doExpand($source, $sources, $completed);
    }
    return $sources;
  }

  /**
   * Expand an individual source.
   *
   * @param string $source
   *   The source name.
   * @param \Xylemical\Discovery\SourceInterface[] $sources
   *   The sources.
   * @param array $completed
   *   The completed sources.
   */
  protected function doExpand(string $source, array $sources, array &$completed): void {
    if (isset($completed[$source]) || !isset($sources[$source])) {
      return;
    }
    $completed[$source] = TRUE;

    $object = $sources[$source];
    foreach ($object->getClasses() as $item) {
      $this->doExpand($item, $sources, $completed);
      $this->merge($object, $sources[$item] ?? NULL);
    }
    foreach ($object->getInterfaces() as $item) {
      $this->doExpand($item, $sources, $completed);
      $this->merge($object, $sources[$item] ?? NULL);
    }
    foreach ($object->getTraits() as $item) {
      $this->doExpand($item, $sources, $completed);
      $this->merge($object, $sources[$item] ?? NULL);
    }
  }

  /**
   * Merge a dependency into the source.
   *
   * @param \Xylemical\Discovery\SourceInterface $original
   *   The original source.
   * @param \Xylemical\Discovery\SourceInterface|null $dependency
   *   The dependency.
   */
  protected function merge(SourceInterface $original, ?SourceInterface $dependency): void {
    if ($dependency) {
      $original->setClasses(
        $this->mergeArray($original->getClasses(), $dependency->getClasses())
      );
      $original->setInterfaces(
        $this->mergeArray($original->getInterfaces(), $dependency->getInterfaces())
      );
      $original->setTraits(
        $this->mergeArray($original->getTraits(), $dependency->getTraits())
      );
    }
  }

  /**
   * Merge two string arrays and maintain uniqueness.
   *
   * @param string[] $a
   *   Source string array.
   * @param string[] $b
   *   Dependency string array.
   *
   * @return string[]
   *   The merged string array.
   */
  protected function mergeArray(array $a, array $b): array {
    return array_unique(array_merge($a, $b));
  }

}

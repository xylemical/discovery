<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

/**
 * Provides a generic source factory.
 */
class SourceFactory implements SourceFactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function create(string $type, string $name): SourceInterface {
    return match ($type) {
      'interface' => new InterfaceSource($name),
      'trait' => new TraitSource($name),
      default => new ClassSource($name),
    };
  }

}

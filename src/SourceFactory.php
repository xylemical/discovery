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
    return new Source($type, $name);
  }

}

<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

/**
 * Provides the trait source.
 */
class TraitSource extends Source implements TraitSourceInterface {

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return 'trait';
  }

}

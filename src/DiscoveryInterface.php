<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

/**
 * Provides the result of the DiscoverInterface.
 */
interface DiscoveryInterface {

  /**
   * Perform discovery for all sources.
   *
   * @return \Xylemical\Discovery\SourceInterface[]
   *   The discovered classes.
   */
  public function discover(): array;

}

<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

/**
 * Provides factory mechanism for sources.
 */
interface SourceFactoryInterface {

  /**
   * Create a source object.
   *
   * @param string $type
   *   The source type.
   * @param string $name
   *   The source name.
   *
   * @return \Xylemical\Discovery\SourceInterface
   *   The source.
   */
  public function create(string $type, string $name): SourceInterface;

}

<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

/**
 * Provides a source which is specifically a class.
 */
interface ClassSourceInterface extends SourceInterface {

  /**
   * Check whether the class is abstract.
   *
   * @return bool
   *   The result.
   */
  public function isAbstract(): bool;

  /**
   * Set the abstract flag.
   *
   * @param bool $flag
   *   The flag.
   *
   * @return $this
   */
  public function setAbstract(bool $flag): static;

}

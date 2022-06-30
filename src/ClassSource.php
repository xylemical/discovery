<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

/**
 * Provides a generic class source.
 */
class ClassSource extends Source implements ClassSourceInterface {

  /**
   * The abstract flag.
   *
   * @var bool
   */
  protected bool $abstract = FALSE;

  /**
   * {@inheritdoc}
   */
  public function isAbstract(): bool {
    return $this->abstract;
  }

  /**
   * {@inheritdoc}
   */
  public function setAbstract(bool $flag): static {
    $this->abstract = $flag;
    return $this;
  }

}

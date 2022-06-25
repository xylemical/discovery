<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

/**
 * Provides description of an object.
 */
interface SourceInterface {

  /**
   * The constant for discovery of classes.
   */
  public const TYPE_CLASS = 'class';

  /**
   * The constant for discovery of interfaces.
   */
  public const TYPE_INTERFACE = 'interface';

  /**
   * The constant for discovery of traits.
   */
  public const TYPE_TRAIT = 'trait';

  /**
   * Get the type.
   *
   * @return string
   *   The type - one of the constants.
   */
  public function getType(): string;

  /**
   * Get the name.
   *
   * @return string
   *   The name.
   */
  public function getName(): string;

  /**
   * Get the object parent classes.
   *
   * @return string[]
   *   The usages.
   */
  public function getClasses(): array;

  /**
   * Set the source classes.
   *
   * @param string[] $classes
   *   The classes.
   *
   * @return $this
   */
  public function setClasses(array $classes): static;

  /**
   * Get the source interfaces.
   *
   * @return string[]
   *   The usages.
   */
  public function getInterfaces(): array;

  /**
   * Set the interface usages.
   *
   * @param string[] $interfaces
   *   The usages.
   *
   * @return $this
   */
  public function setInterfaces(array $interfaces): static;

  /**
   * Get the trait usages.
   *
   * @return string[]
   *   The usages.
   */
  public function getTraits(): array;

  /**
   * Set the trait usages.
   *
   * @param string[] $traits
   *   The usages.
   *
   * @return $this
   */
  public function setTraits(array $traits): static;

}

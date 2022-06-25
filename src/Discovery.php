<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

/**
 * Provides a generic discovery object.
 */
class Discovery implements DiscoveryInterface {

  /**
   * The discoveries.
   *
   * @var \Xylemical\Discovery\DiscoveryInterface[]
   */
  protected array $discoveries = [];

  /**
   * {@inheritdoc}
   */
  public function discover(): array {
    $results = [];
    foreach ($this->discoveries as $discovery) {
      $results += $discovery->discover();
    }
    return $results;
  }

  /**
   * Add a discovery.
   *
   * @param \Xylemical\Discovery\DiscoveryInterface $discovery
   *   A discovery.
   *
   * @return $this
   */
  public function addDiscovery(DiscoveryInterface $discovery): static {
    $this->discoveries[] = $discovery;
    return $this;
  }

}

<?php

declare(strict_types=1);

namespace Xylemical\Discovery;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use Xylemical\Discovery\Source\SourceVisitor;

/**
 * Provides a generic source object.
 */
abstract class Source implements SourceInterface {

  /**
   * The name of the source object.
   *
   * @var string
   */
  protected string $name;

  /**
   * The classes used by the source.
   *
   * @var string[]
   */
  protected array $classes = [];

  /**
   * The interfaces used by the source.
   *
   * @var string[]
   */
  protected array $interfaces = [];

  /**
   * The traits used by the source.
   *
   * @var string[]
   */
  protected array $traits = [];

  /**
   * Source constructor.
   *
   * @param string $name
   *   The name.
   */
  public function __construct(string $name) {
    $this->name = $name;
  }

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return 'class';
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getClasses(): array {
    return $this->classes;
  }

  /**
   * {@inheritdoc}
   */
  public function setClasses(array $classes): static {
    if ($this instanceof ClassSourceInterface) {
      $this->classes = array_unique($classes);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getInterfaces(): array {
    return $this->interfaces;
  }

  /**
   * {@inheritdoc}
   */
  public function setInterfaces(array $interfaces): static {
    if ($this instanceof ClassSourceInterface || $this instanceof InterfaceSourceInterface) {
      $this->interfaces = array_unique($interfaces);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTraits(): array {
    return $this->traits;
  }

  /**
   * {@inheritdoc}
   */
  public function setTraits(array $traits): static {
    if ($this instanceof ClassSourceInterface || $this instanceof TraitSourceInterface) {
      $this->traits = array_unique($traits);
    }
    return $this;
  }

  /**
   * Parse the contents of a source file.
   *
   * @param \Xylemical\Discovery\SourceFactoryInterface $factory
   *   The source factory.
   * @param string $contents
   *   The source file contents.
   *
   * @return \Xylemical\Discovery\SourceInterface[]
   *   The source objects.
   */
  public static function parse(SourceFactoryInterface $factory, string $contents): array {
    $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    try {
      $ast = $parser->parse($contents);
    }
    catch (Error) {
      return [];
    }

    $visitor = new SourceVisitor($factory);
    $traverser = new NodeTraverser();
    $traverser->addVisitor(new NameResolver());
    $traverser->addVisitor($visitor);
    $traverser->traverse($ast);

    return $visitor->getSources();
  }

}

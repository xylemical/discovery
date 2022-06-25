<?php

declare(strict_types=1);

namespace Xylemical\Discovery\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Name;
use PhpParser\NodeVisitorAbstract;
use Xylemical\Discovery\SourceFactoryInterface;
use Xylemical\Discovery\SourceInterface;
use function array_unique;
use function ltrim;

/**
 * Constructs objects from the given source.
 */
class SourceVisitor extends NodeVisitorAbstract {

  /**
   * The source factory.
   *
   * @var \Xylemical\Discovery\SourceFactoryInterface
   */
  protected SourceFactoryInterface $factory;

  /**
   * The source objects.
   *
   * @var array
   */
  protected array $sources = [];

  /**
   * The namespace containing the object.
   *
   * @var string
   */
  protected string $namespace = '';

  /**
   * The target node for an object.
   *
   * @var \PhpParser\Node|null
   */
  protected ?Node $target = NULL;

  /**
   * The current source.
   *
   * @var \Xylemical\Discovery\SourceInterface|null
   */
  protected ?SourceInterface $source = NULL;

  /**
   * SourceVisitor constructor.
   *
   * @param \Xylemical\Discovery\SourceFactoryInterface $factory
   *   The source factory.
   */
  public function __construct(SourceFactoryInterface $factory) {
    $this->factory = $factory;
  }

  /**
   * Get the sources.
   *
   * @return \Xylemical\Discovery\SourceInterface[]
   *   The sources.
   */
  public function getSources(): array {
    return $this->sources;
  }

  /**
   * {@inheritdoc}
   */
  public function enterNode(Node $node) {
    if ($node instanceof Class_ && $node->name) {
      $this->doClass($node);
    }
    elseif ($node instanceof Interface_) {
      $this->doInterface($node);
    }
    elseif ($node instanceof Trait_) {
      $this->doTrait($node);
    }
    elseif ($node instanceof TraitUse && $this->source) {
      $this->doTraits($node);
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function leaveNode(Node $node) {
    if ($node === $this->target) {
      $this->doObject();
    }
    return NULL;
  }

  /**
   * Get fully namespaced name without preceding slash.
   *
   * @param \PhpParser\Node\Name $name
   *   The name.
   *
   * @return string
   *   The normalized name.
   */
  protected function getName(Name $name): string {
    return ltrim($name->toCodeString(), '\\');
  }

  /**
   * Process class node.
   *
   * @param \PhpParser\Node\Stmt\Class_ $node
   *   The node.
   */
  protected function doClass(Class_ $node): void {
    $this->target = $node;
    $this->source = $this->factory->create(
      SourceInterface::TYPE_CLASS,
      $this->getName($node->namespacedName),
    );
    if ($node->extends) {
      $this->source->setClasses([
        $this->getName($node->extends),
      ]);
    }
    $implements = [];
    foreach ($node->implements as $implement) {
      $implements[] = $this->getName($implement);
    }
    $this->source->setInterfaces($implements);
  }

  /**
   * Process interface node.
   *
   * @param \PhpParser\Node\Stmt\Interface_ $node
   *   The interface.
   */
  protected function doInterface(Interface_ $node): void {
    $this->target = $node;
    $this->source = $this->factory->create(
      SourceInterface::TYPE_INTERFACE,
      $this->getName($node->namespacedName),
    );
    $implements = [];
    foreach ($node->extends as $implement) {
      $implements[] = $this->getName($implement);
    }
    $this->source->setInterfaces($implements);
  }

  /**
   * Process trait node.
   *
   * @param \PhpParser\Node\Stmt\Trait_ $node
   *   The trait.
   */
  protected function doTrait(Trait_ $node): void {
    $this->target = $node;
    $this->source = $this->factory->create(
      SourceInterface::TYPE_TRAIT,
      $this->getName($node->namespacedName),
    );
  }

  /**
   * Processes the uses for a source.
   *
   * @param \PhpParser\Node\Stmt\TraitUse $node
   *   The uses.
   */
  protected function doTraits(TraitUse $node): void {
    $traits = $this->source->getTraits();
    foreach ($node->traits as $name) {
      $traits[] = $this->getName($name);
    }
    $this->source->setTraits(array_unique($traits));
  }

  /**
   * Save updated source object.
   */
  protected function doObject(): void {
    $this->sources[$this->source->getName()] = $this->source;
    $this->target = NULL;
    $this->source = NULL;
  }

}

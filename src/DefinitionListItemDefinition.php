<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\AbstractStringContainerBlock;
use League\CommonMark\Block\Element\InlineContainerInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

class DefinitionListItemDefinition extends AbstractStringContainerBlock implements InlineContainerInterface
{
    public function __construct(array $contents)
    {
        parent::__construct();

        foreach ($contents as $line) {
            $this->addLine($line);
        }
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        $this->finalStringContents = \implode("\n", $this->strings->toArray());
    }

    public function canContain(AbstractBlock $block): bool
    {
        return false;
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        if ($cursor->isBlank()) {
            return false;
        }

        if ($cursor->peek() === ":") {
            return false;
        }

        return true;
    }

    public function handleRemainingContents(ContextInterface $context, Cursor $cursor)
    {
        // nothing to do; contents were already added via the constructor.
    }
}

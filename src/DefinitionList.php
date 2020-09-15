<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Node\Node;

/**
 * @method children() DefinitionListItem[]
 */
class DefinitionList extends AbstractBlock
{
    // Tight by default, until proven loose
    private bool $isTight = true;

    public function canContain(AbstractBlock $block): bool
    {
        return $this->canPermanentlyContain($block) || $this->canTemporarilyContain($block);
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        return true;
    }

    public function markAsLoose(): void
    {
        $this->isTight = false;
    }

    public function isTight(): bool
    {
        return $this->isTight;
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        // There might be blocks (e.g. paragraphs) that we have temporarily accepted
        // as a child, just to see if they turn out to contain definition terms. If
        // the definition list finalizes we know for sure that they don't, so we move
        // them after the definition list.
        while (!$this->canPermanentlyContain($this->lastChild)) {
            $this->insertAfter($this->lastChild);
        }
    }

    private function canPermanentlyContain(Node $node): bool
    {
        return $node instanceof DefinitionListItemTerm || $node instanceof DefinitionListItemDefinition;
    }

    private function canTemporarilyContain(Node $node): bool
    {
        // Paragraphs are temporarily allowed, but removed when finalizing
        return $node instanceof Paragraph;
    }
}

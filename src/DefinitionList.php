<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

/**
 * @method children() DefinitionListItem[]
 */
class DefinitionList extends AbstractBlock
{
    // Tight by default, until proven loose
    private bool $isTight = true;

    public function canContain(AbstractBlock $block): bool
    {
        // Paragraphs are temporarily allowed, but removed when finalizing
        return $block instanceof DefinitionListItemTerm || $block instanceof DefinitionListItemDefinition || $block instanceof Paragraph;
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

        while (!($this->lastChild instanceof DefinitionListItemTerm || $this->lastChild instanceof DefinitionListItemDefinition)) {
            $this->insertAfter($this->lastChild);
        }
    }
}

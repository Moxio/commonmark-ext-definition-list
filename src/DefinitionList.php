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
    public function canContain(AbstractBlock $block): bool
    {
        // Paragraphs are temporarily allowed, but removed when finalizing
        return $block instanceof DefinitionListItem || $block instanceof Paragraph;
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        return true;
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        $removedChildren = [];
        while (!($this->lastChild instanceof DefinitionListItem)) {
            $removedChildren[] = $this->lastChild;
            $this->lastChild->detach();
        }

        foreach ($removedChildren as $removedChild) {
            $this->parent->appendChild($removedChild);
        }
    }
}

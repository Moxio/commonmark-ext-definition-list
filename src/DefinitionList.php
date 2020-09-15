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
        return $block instanceof DefinitionListItem;
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

        $lastItem = $this->lastChild;
        $lastItemLastComponent = $lastItem->lastChild;
        if ($lastItemLastComponent instanceof DefinitionListItemTerm) {
            $replacementParagraph = new Paragraph();
            $replacementParagraph->addLine($lastItemLastComponent->getStringContent());
            $lastItem->detach();
            $context->addBlock($replacementParagraph);
        }
    }
}

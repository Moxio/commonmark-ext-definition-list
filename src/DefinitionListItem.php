<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Cursor;

/**
 * @method children() (DefinitionListItemTerm|DefinitionListItemDefinition)[]
 */
class DefinitionListItem extends AbstractBlock
{
    public function canContain(AbstractBlock $block): bool
    {
        return $block instanceof DefinitionListItemTerm || $block instanceof DefinitionListItemDefinition;
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        return !$cursor->isBlank();
    }
}

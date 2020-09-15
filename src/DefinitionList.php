<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\AbstractBlock;
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
}

<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\AbstractStringContainerBlock;
use League\CommonMark\Block\Element\InlineContainerInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

/**
 * @method children() AbstractBlock[]
 */
class DefinitionListItemDefinition extends AbstractBlock
{
    public function canContain(AbstractBlock $block): bool
    {
        return !($block instanceof DefinitionListItemTerm || $block instanceof DefinitionListItemDefinition);
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

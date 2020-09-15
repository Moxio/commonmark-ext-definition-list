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
    private bool $containsBlankLines = false;

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
        if ($this->containsBlankLines) {
            if ($cursor->isIndented()) {
                $cursor->advanceToNextNonSpaceOrTab();
            } else {
                return false;
            }
        }

        if ($cursor->isBlank()) {
            $this->containsBlankLines = true;
        }

        return true;
    }
}

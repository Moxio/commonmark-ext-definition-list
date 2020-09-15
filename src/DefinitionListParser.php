<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

class DefinitionListParser implements BlockParserInterface
{
    public function parse(ContextInterface $context, Cursor $cursor): bool
    {
        if ($cursor->isBlank()) {
            return false;
        }

        $originalContainer = $context->getContainer();
        if ($cursor->peek(0) !== ":") {
            if ($originalContainer instanceof DefinitionListItemDefinition) {
                $cursor->advanceToNextNonSpaceOrTab();
                $originalContainer->addLine($cursor->getRemainder());
                $cursor->advanceToEnd();
                return true;
            } else {
                return false;
            }
        }

        if (!($originalContainer instanceof Paragraph || $originalContainer instanceof DefinitionList || $originalContainer instanceof DefinitionListItem || $originalContainer instanceof DefinitionListItemDefinition)) {
            return false;
        }

        if ($originalContainer instanceof Paragraph && !($originalContainer->parent() instanceof DefinitionListItemDefinition)) {
            if ($originalContainer->parent() instanceof DefinitionList) {
                $context->replaceContainerBlock(new DefinitionListItem());
            } else {
                $context->replaceContainerBlock(new DefinitionList());
                $context->addBlock(new DefinitionListItem());
            }

            $strings = $originalContainer->getStrings();
            foreach ($strings as $string) {
                $context->addBlock(new DefinitionListItemTerm([ $string ]));
            }
        }

        $cursor->advanceBy(1);
        $cursor->advanceToNextNonSpaceOrTab();
        $context->addBlock(new DefinitionListItemDefinition());
        $context->addBlock(new Paragraph());

        return true;
    }
}

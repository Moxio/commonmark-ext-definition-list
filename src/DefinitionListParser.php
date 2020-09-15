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
            if ($originalContainer instanceof DefinitionList) {
                $context->addBlock(new DefinitionListItem());
                $context->addBlock(new DefinitionListItemTerm([$cursor->getRemainder()]));
                $cursor->advanceToEnd();
                return true;
            } else if ($originalContainer instanceof DefinitionListItemDefinition) {
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

        if ($context->getContainer() instanceof Paragraph) {
            $context->replaceContainerBlock(new DefinitionList());

            $context->addBlock(new DefinitionListItem());

            $strings = $originalContainer->getStrings();
            $context->addBlock(new DefinitionListItemTerm($strings));
        }

        $cursor->advanceBy(1);
        $cursor->advanceToNextNonSpaceOrTab();
        $context->addBlock(new DefinitionListItemDefinition([ $cursor->getRemainder() ]));
        $cursor->advanceToEnd();

        return true;
    }
}

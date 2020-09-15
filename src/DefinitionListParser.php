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

        if ($cursor->peek(0) . $cursor->peek(1) !== ": ") {
            if ($context->getContainer() instanceof DefinitionList) {
                $context->addBlock(new DefinitionListItem());
                $context->addBlock(new DefinitionListItemTerm([ $cursor->getRemainder() ]));
                $cursor->advanceToEnd();
                return true;
            } else {
                return false;
            }
        }

        $originalContainer = $context->getContainer();
        if (!($originalContainer instanceof Paragraph || $originalContainer instanceof DefinitionList || $originalContainer instanceof DefinitionListItem)) {
            return false;
        }

        if ($context->getContainer() instanceof Paragraph) {
            $context->replaceContainerBlock(new DefinitionList());

            $context->addBlock(new DefinitionListItem());

            $strings = $originalContainer->getStrings();
            $context->addBlock(new DefinitionListItemTerm($strings));
        }

        $cursor->advanceBy(2);
        $context->addBlock(new DefinitionListItemDefinition([ $cursor->getRemainder() ]));
        $cursor->advanceToEnd();

        return true;
    }
}

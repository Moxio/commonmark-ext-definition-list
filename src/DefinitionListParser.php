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
            return false;
        }

        if (!($originalContainer instanceof Paragraph || $originalContainer instanceof DefinitionList || $originalContainer instanceof DefinitionListItemDefinition)) {
            return false;
        }

        if ($originalContainer instanceof Paragraph && !($originalContainer->parent() instanceof DefinitionListItemDefinition)) {
            $originalContainerParent = $originalContainer->parent();
            if ($originalContainerParent instanceof DefinitionList) {
                $originalContainer->detach();
                $context->setContainer($originalContainerParent);
                $context->setTip($originalContainerParent);
            } else {
                $context->replaceContainerBlock(new DefinitionList());
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

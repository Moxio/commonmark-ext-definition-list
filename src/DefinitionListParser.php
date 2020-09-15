<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\Document;
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

        if ($cursor->peek(0) !== ":") {
            return false;
        }

        $originalContainer = $context->getContainer();
        if ($originalContainer instanceof Document) {
            $lastContainerChild = $originalContainer->lastChild();
            if ($lastContainerChild !== null && $lastContainerChild instanceof Paragraph) {
                // Turn the previous paragraph into definition term(s)
                $definitionList = new DefinitionList();
                $lastContainerChild->replaceWith($definitionList);
                $this->switchContextToList($context, $definitionList);
                $this->addTermsFromParagraph($context, $lastContainerChild);

                // Because the paragraph was finished, there was a blank line before the definition,
                // which means the definition list must be loose.
                $definitionList->markAsLoose();

                $this->startDefinition($context, $cursor);

                return true;
            } else {
                // There is no previous top-level paragraph that can contain the definition term(s).
                return false;
            }
        } else if ($originalContainer instanceof Paragraph) {
            $originalContainerParent = $originalContainer->parent();
            if ($originalContainerParent instanceof DefinitionListItemDefinition) {
                // This paragraph is part of a previous definition, so we shouldn't convert it into terms.
                // We can start a new definition right away. It will automatically be placed after the current
                // definition, as a definition block cannot contain another definition block.
            } else if ($originalContainerParent instanceof DefinitionList) {
                // Remove the paragraph and turn it into terms within the definition list
                $originalContainer->detach();
                $this->switchContextToList($context, $originalContainerParent);
                $this->addTermsFromParagraph($context, $originalContainer);
            } else {
                // Start a new definition list and turn the paragraph into terms within that list
                $context->replaceContainerBlock(new DefinitionList());
                $this->addTermsFromParagraph($context, $originalContainer);
            }

            $this->startDefinition($context, $cursor);

            return true;
        } else if ($originalContainer instanceof DefinitionList) {
            $lastContainerChild = $originalContainer->lastChild();
            if ($lastContainerChild !== null && $lastContainerChild instanceof Paragraph) {
                // Remove the paragraph and turn it into terms within the definition list
                $lastContainerChild->detach();
                $this->addTermsFromParagraph($context, $lastContainerChild);

                // Because the paragraph was finished, there was a blank line before the definition,
                // which means the definition list must be loose.
                $originalContainer->markAsLoose();
            }

            $this->startDefinition($context, $cursor);

            return true;
        } else if ($originalContainer instanceof DefinitionListItemDefinition) {
            $this->startDefinition($context, $cursor);

            return true;
        } else {
            return false;
        }
    }

    private function switchContextToList(ContextInterface $context, DefinitionList $definitionList): void
    {
        $context->setContainer($definitionList);
        $context->setTip($definitionList);
    }

    private function addTermsFromParagraph(ContextInterface $context, Paragraph $paragraph): void
    {
        foreach ($paragraph->getStrings() as $term) {
            $context->addBlock(new DefinitionListItemTerm([ $term ]));
        }
    }

    private function startDefinition(ContextInterface $context, Cursor $cursor): void
    {
        $cursor->advanceBy(1);
        $cursor->advanceToNextNonSpaceOrTab();

        $context->addBlock(new DefinitionListItemDefinition());
        $context->addBlock(new Paragraph());
    }
}

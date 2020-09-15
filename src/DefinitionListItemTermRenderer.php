<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;

class DefinitionListItemTermRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (!($block instanceof DefinitionListItemTerm)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        return new HtmlElement('dt', [], $htmlRenderer->renderInlines($block->children()));
    }
}

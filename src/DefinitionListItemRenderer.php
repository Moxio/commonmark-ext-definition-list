<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;

class DefinitionListItemRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (!($block instanceof DefinitionListItem)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        return $htmlRenderer->renderBlocks($block->children(), $inTightList);
    }
}

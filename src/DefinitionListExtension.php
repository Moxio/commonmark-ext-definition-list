<?php
namespace Moxio\CommonMark\Extension\DefinitionList;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;

class DefinitionListExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment
            ->addBlockParser(new DefinitionListParser())
            ->addBlockRenderer(DefinitionList::class, new DefinitionListRenderer())
            ->addBlockRenderer(DefinitionListItem::class, new DefinitionListItemRenderer())
            ->addBlockRenderer(DefinitionListItemTerm::class, new DefinitionListItemTermRenderer())
            ->addBlockRenderer(DefinitionListItemDefinition::class, new DefinitionListItemDefinitionRenderer());
    }
}

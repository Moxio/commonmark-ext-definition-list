<?php
namespace Moxio\CommonMark\Extension\DefinitionList\Test;

use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Moxio\CommonMark\Extension\DefinitionList\DefinitionList;
use Moxio\CommonMark\Extension\DefinitionList\DefinitionListExtension;
use Moxio\CommonMark\Extension\DefinitionList\DefinitionListItemDefinition;
use Moxio\CommonMark\Extension\DefinitionList\DefinitionListItemTerm;
use PHPUnit\Framework\TestCase;

class AttributeRenderingTest extends TestCase
{
    private HtmlRenderer $renderer;

    protected function setUp(): void
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new DefinitionListExtension());
        $this->renderer = new HtmlRenderer($environment);
    }

    public function testCorrectlyRendersAttributesOfDefinitionList(): void
    {
        $block = new DefinitionList();
        $block->data['attributes'] = ['id' => 'foo'];
        $actualOutput = $this->renderer->renderBlock($block);

        $this->assertXmlStringEqualsXmlString("<html><dl id=\"foo\"></dl></html>", "<html>$actualOutput</html>");
    }

    public function testCorrectlyRendersAttributesOfDefinitionListItemDefinition(): void
    {
        $block = new DefinitionListItemDefinition();
        $block->data['attributes'] = ['id' => 'foo'];
        $actualOutput = $this->renderer->renderBlock($block);

        $this->assertXmlStringEqualsXmlString("<html><dd id=\"foo\"></dd></html>", "<html>$actualOutput</html>");
    }

    public function testCorrectlyRendersAttributesOfDefinitionListItemTerm(): void
    {
        $block = new DefinitionListItemTerm([]);
        $block->data['attributes'] = ['id' => 'foo'];
        $actualOutput = $this->renderer->renderBlock($block);

        $this->assertXmlStringEqualsXmlString("<html><dt id=\"foo\"></dt></html>", "<html>$actualOutput</html>");
    }
}

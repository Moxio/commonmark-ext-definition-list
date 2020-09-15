<?php
namespace Moxio\CommonMark\Extension\DefinitionList\Test;

use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Moxio\CommonMark\Extension\DefinitionList\DefinitionListExtension;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function testParsesAndRendersDefinitionList(): void
    {
        $source = <<<MD
First Term
: This is the definition of the first term.

Second Term
: This is one definition of the second term.
: This is another definition of the second term.
MD;
        $expectedOutput = <<<HTML
<dl>
  <dt>First Term</dt>
  <dd>This is the definition of the first term.</dd>
  <dt>Second Term</dt>
  <dd>This is one definition of the second term.</dd>
  <dd>This is another definition of the second term.</dd>
</dl>
HTML;

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new DefinitionListExtension());

        $parser = new DocParser($environment);
        $renderer = new HtmlRenderer($environment);
        $actualOutput = $renderer->renderBlock($parser->parse($source));

        $this->assertXmlStringEqualsXmlString($expectedOutput, $actualOutput);
    }
}

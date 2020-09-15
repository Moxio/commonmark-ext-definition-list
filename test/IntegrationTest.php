<?php
namespace Moxio\CommonMark\Extension\DefinitionList\Test;

use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Moxio\CommonMark\Extension\DefinitionList\DefinitionListExtension;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    // Example based on https://www.markdownguide.org/extended-syntax#definition-lists
    public function testParsesAndRendersSimpleDefinitionList(): void
    {
        $markdown = <<<MD
First Term
: This is the definition of the first term.

Second Term
: This is one definition of the second term.
MD;
        $expectedHtml = <<<HTML
<dl>
  <dt>First Term</dt>
  <dd>This is the definition of the first term.</dd>
  <dt>Second Term</dt>
  <dd>This is one definition of the second term.</dd>
</dl>
HTML;

        $this->assertMarkdownIsConvertedTo($expectedHtml, $markdown);
    }

    // Example based on https://michelf.ca/projects/php-markdown/extra/#def-list
    public function testSupportsArbitraryIndentsAfterTheColon(): void
    {
        $markdown = <<<MD
Apple
:   Pomaceous fruit of plants of the genus Malus in the family Rosaceae.

Orange
:   The fruit of an evergreen tree of the genus Citrus.
MD;
        $expectedHtml = <<<HTML
<dl>
  <dt>Apple</dt>
  <dd>Pomaceous fruit of plants of the genus Malus in the family Rosaceae.</dd>
  <dt>Orange</dt>
  <dd>The fruit of an evergreen tree of the genus Citrus.</dd>
</dl>
HTML;

        $this->assertMarkdownIsConvertedTo($expectedHtml, $markdown);
    }

    // Example from https://michelf.ca/projects/php-markdown/extra/#def-list
    public function testSupportsMultilineDefintionsWithIndentation(): void
    {
        $markdown = <<<MD
Apple
:   Pomaceous fruit of plants of the genus Malus in
    the family Rosaceae.

Orange
:   The fruit of an evergreen tree of the genus Citrus.
MD;
        $expectedHtml = <<<HTML
<dl>
  <dt>Apple</dt>
  <dd>Pomaceous fruit of plants of the genus Malus in
the family Rosaceae.</dd>
  <dt>Orange</dt>
  <dd>The fruit of an evergreen tree of the genus Citrus.</dd>
</dl>
HTML;

        $this->assertMarkdownIsConvertedTo($expectedHtml, $markdown);
    }

    // Example from https://michelf.ca/projects/php-markdown/extra/#def-list
    public function testSupportsMultilineDefintionsWithoutIndentation(): void
    {
        $markdown = <<<MD
Apple
:   Pomaceous fruit of plants of the genus Malus in
the family Rosaceae.

Orange
:   The fruit of an evergreen tree of the genus Citrus.
MD;
        $expectedHtml = <<<HTML
<dl>
  <dt>Apple</dt>
  <dd>Pomaceous fruit of plants of the genus Malus in
the family Rosaceae.</dd>
  <dt>Orange</dt>
  <dd>The fruit of an evergreen tree of the genus Citrus.</dd>
</dl>
HTML;

        $this->assertMarkdownIsConvertedTo($expectedHtml, $markdown);
    }

    // Example from https://www.markdownguide.org/extended-syntax#definition-lists
    public function testSupportsMultipleDefinitionsForOneTerm(): void
    {
        $markdown = <<<MD
First Term
: This is the definition of the first term.

Second Term
: This is one definition of the second term.
: This is another definition of the second term.
MD;
        $expectedHtml = <<<HTML
<dl>
  <dt>First Term</dt>
  <dd>This is the definition of the first term.</dd>
  <dt>Second Term</dt>
  <dd>This is one definition of the second term.</dd>
  <dd>This is another definition of the second term.</dd>
</dl>
HTML;

        $this->assertMarkdownIsConvertedTo($expectedHtml, $markdown);
    }

    public function testSupportsParagraphsBeforeTheDefinitionList(): void
    {
        $markdown = <<<MD
Introductory paragraph.

Apple
: Pomaceous fruit of plants of the genus Malus in the family Rosaceae.

Orange
: The fruit of an evergreen tree of the genus Citrus.
MD;
        $expectedHtml = <<<HTML
<p>Introductory paragraph.</p>
<dl>
  <dt>Apple</dt>
  <dd>Pomaceous fruit of plants of the genus Malus in the family Rosaceae.</dd>
  <dt>Orange</dt>
  <dd>The fruit of an evergreen tree of the genus Citrus.</dd>
</dl>
HTML;

        $this->assertMarkdownIsConvertedTo($expectedHtml, $markdown);
    }

    public function testSupportsParagraphsAfterTheDefinitionList(): void
    {
        $markdown = <<<MD
Apple
: Pomaceous fruit of plants of the genus Malus in the family Rosaceae.

Orange
: The fruit of an evergreen tree of the genus Citrus.

Concluding paragraph.
MD;
        $expectedHtml = <<<HTML
<dl>
  <dt>Apple</dt>
  <dd>Pomaceous fruit of plants of the genus Malus in the family Rosaceae.</dd>
  <dt>Orange</dt>
  <dd>The fruit of an evergreen tree of the genus Citrus.</dd>
</dl>
<p>Concluding paragraph.</p>
HTML;

        $this->assertMarkdownIsConvertedTo($expectedHtml, $markdown);
    }

    public function assertMarkdownIsConvertedTo($expectedHtml, $markdown): void
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new DefinitionListExtension());

        $parser = new DocParser($environment);
        $renderer = new HtmlRenderer($environment);
        $actualOutput = $renderer->renderBlock($parser->parse($markdown));

        $this->assertXmlStringEqualsXmlString("<html>$expectedHtml</html>", "<html>$actualOutput</html>");
    }
}

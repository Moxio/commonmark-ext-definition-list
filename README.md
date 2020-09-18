[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/Moxio/commonmark-ext-definition-list)

moxio/commonmark-ext-definition-list
====================================
Extension for the `league/commonmark` Markdown parser to support definition lists.

Uses unofficial markdown syntax based on the syntax supported by
[PHP Markdown Extra](https://michelf.ca/projects/php-markdown/extra/#def-list),
[Pandoc](https://pandoc.org/MANUAL.html#definition-lists) and
[markdown-it](https://github.com/markdown-it/markdown-it-deflist). See the
section [Syntax](#syntax) below for details.

Requirements
------------
This library requires PHP version 7.4 or higher and a `1.x` release of
`league/commonmark`.

Installation
------------
Install as a dependency using composer:
```
$ composer require --dev moxio/commonmark-ext-definition-list
```

Usage
-----
Add `DefinitionListExtension` as an extension to your CommonMark environment
instance and you're good to go:
```php
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use Moxio\CommonMark\Extension\DefinitionList\DefinitionListExtension;

$environment = Environment::createCommonMarkEnvironment();
$environment->addExtension(new DefinitionListExtension());

// Use $environment when building your CommonMarkConverter
$converter = new CommonMarkConverter([], $environment);
echo $converter->convertToHtml('
Term 1
: Definition of term 1.

Term 2
: Definition of term 2.
');
```
See the [CommonMark documentation](https://commonmark.thephpleague.com/1.5/extensions/overview/#usage)
for more information about using extensions.

Syntax
------
The supported markdown syntax is based on the one used by
[PHP Markdown Extra](https://michelf.ca/projects/php-markdown/extra/#def-list),
[Pandoc](https://pandoc.org/MANUAL.html#definition-lists) and
[markdown-it](https://github.com/markdown-it/markdown-it-deflist).
Since there are subtle differences between the syntaxes understood by these
libraries and there is no formally defined standard, 100% compatibility
with any of the aforementioned libraries cannot be guaranteed. The use
of the tilde (`~`) as a definition list marker (as understood by Pandoc)
is not supported yet.

A simple example:
```markdown
Apple
:   Pomaceous fruit of plants of the genus Malus in
the family Rosaceae.

Orange
:   The fruit of an evergreen tree of the genus Citrus.
```
The will yield HTML output like:
```html
<dl>
  <dt>Apple</dt>
  <dd>Pomaceous fruit of plants of the genus Malus in
the family Rosaceae.</dd>
  <dt>Orange</dt>
  <dd>The fruit of an evergreen tree of the genus Citrus.</dd>
</dl>
```

A more complex example:
```markdown
Term 1

:   This is a definition with two paragraphs. Lorem ipsum
    dolor sit amet, consectetuer adipiscing elit. Aliquam
    hendrerit mi posuere lectus.

    Vestibulum enim wisi, viverra nec, fringilla in, laoreet
    vitae, risus.

:   Second definition for term 1, also wrapped in a paragraph
    because of the blank line preceding it.

Term 2

:   This definition has a code block, a blockquote and a list.

        code block.

    > block quote
    > on two lines.

    1.  first list item
    2.  second list item
```

Versioning
----------
This project adheres to [Semantic Versioning](http://semver.org/).

Contributing
------------
Contributions to this project are more than welcome. When reporting an issue,
please include the input to reproduce the issue, along with the expected
output. When submitting a PR, please include tests with your changes.

License
-------
This project is released under the MIT license.

Treeware
--------
This package is [Treeware](https://treeware.earth/). If you use it in production,
then we'd appreciate it if you [**buy the world a tree**](https://plant.treeware.earth/Moxio/commonmark-ext-definition-list)
to thank us for our work. By contributing to the Treeware forest you'll be creating
employment for local families and restoring wildlife habitats.

---
Made with love, coffee and fun by the [Moxio](https://www.moxio.com) team from
Delft, The Netherlands. Interested in joining our awesome team? Check out our
[vacancies](https://werkenbij.moxio.com/) (in Dutch).

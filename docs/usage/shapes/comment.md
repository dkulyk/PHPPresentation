# Comments

To create a comment, create an object `Comment`.

``` php
<?php

use PhpOffice\PhpPresentation\Shape\Comment;

$comment = new Comment();
$slide->addShape($comment);
```

You can define text and date with setters.

Example:

``` php
<?php

use PhpOffice\PhpPresentation\Shape\Comment;

$comment = new Comment();
$comment->setText('Text of the Comment');
$comment->setDate(time());
$slide->addShape($comment);
```

## Author

For a comment, you can define the author.

Example:

``` php
<?php

use PhpOffice\PhpPresentation\Shape\Comment;
use PhpOffice\PhpPresentation\Shape\Comment\Author;

$author = new Author();
$comment = new Comment();
$comment->setAuthor($author);
$slide->addShape($comment);
```

You can define name and initials with setters.

Example:

``` php
<?php

use PhpOffice\PhpPresentation\Shape\Comment;
use PhpOffice\PhpPresentation\Shape\Comment\Author;

$author = new Author();
$author->setName('Name of the author');
$author->setInitals('Nota');
$comment = new Comment();
$comment->setAuthor($author);
$slide->addShape($comment);
```

## Reading

The PowerPoint2007 Reader reads the comments of a slide (`p:cmLst`, which is what the PowerPoint2007 Writer and LibreOffice write) into `Comment` shapes of that slide: the text, the date, the position and the author, which the comments of one author share.

A comment that names no date keeps the date it was read at. The file has to name an author for every comment, so the PowerPoint2007 Writer writes the comments without an author, as LibreOffice does, with an author that has no name and no initials; the Reader reads such an author as no author. The threaded comments of recent versions of PowerPoint (`p188:cmLst`) are stored in another part and are not read.

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

A comment that names no date keeps the date it was read at. A comment written without an author is read back with the first author of the presentation when there is one, because the file has to name an author for every comment. The threaded comments of recent versions of PowerPoint (`p188:cmLst`) are stored in another part and are not read.

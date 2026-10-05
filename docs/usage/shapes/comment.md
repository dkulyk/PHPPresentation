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
$author->setInitials('Nota');
$comment = new Comment();
$comment->setAuthor($author);
$slide->addShape($comment);
```

## Reading

The ODPresentation Reader reads the comments of a slide, as LibreOffice Impress writes them:
the position, the date, the text with a line for each paragraph, and the name and the
initials of the author. Comments whose authors have the same name and initials share one
`Author` object.

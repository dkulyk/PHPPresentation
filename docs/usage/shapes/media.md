# Media

To create a video, create an object `Media`.

Example:

``` php
<?php

use PhpOffice\PhpPresentation\Shape\Media;

$media = new Media();
$media->setPath('file.wmv');
// $media->setPath('file.ogv');
$slide->addShape($media);
```

You can define text and date with setters.

Example:

``` php
<?php

use PhpOffice\PhpPresentation\Shape\Media;

$media = new Media();
$media->setName('Name of the Media');
$slide->addShape($media);
```

## Quirks

For Windows readers, the prefered file format is WMV.
For Linux readers, the prefered file format is OGV.

## Reading

The ODPresentation and PowerPoint2007 Readers read a video or a sound embedded in the presentation as a `Media`.

The media is not copied out of the file it is read from: the path of the shape points into that file (`zip://presentation.odp#Media/video.mp4`, `zip://presentation.pptx#ppt/media/media1.mp4`), and its contents are taken from there when the presentation is written.
That file must still be there at that time, or the Writer throws a `FileNotFoundException`. It can be the file being written, once: the media is then no longer where the shape points, and the presentation has to be read again before it is written another time.

A video or a sound that is not in the file, but linked to at an address or beside the presentation, is not read as a `Media`: when a picture is shown for it, that picture is read.
A picture shown for a `Media` that is read is not.

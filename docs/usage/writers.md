# Writers

## HTML
The name of the writer is `HTML`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpPresentation, 'HTML');
$writer->save(__DIR__ . '/sample.html');
```

## Keynote
The name of the writer is `Keynote`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpPresentation, 'Keynote');
$writer->save(__DIR__ . '/sample.key');
```

The writer writes the text of every slide, its speaker note and the images it uses, as the
`index.apxl` package Keynote '09 reads. The `Index/*.iwa` shape of Keynote '13 and later is not
written.

## ODPresentation
The name of the writer is `ODPresentation`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpPresentation, 'PowerPoint2007');
$writer->save(__DIR__ . '/sample.pptx');
```

## PDF
The name of the writer is `PDF`.

``` php
<?php

use PhpOffice\PhpPresentation\Writer\PDF\DomPDF;

$writer = IOFactory::createWriter($oPhpPresentation, 'PDF');
$writer->setPDFAdapter(new DomPDF());
$writer->save(__DIR__ . '/sample.pdf');
```

## PowerPoint2007
The name of the writer is `PowerPoint2007`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpPresentation, 'PowerPoint2007');
$writer->save(__DIR__ . '/sample.pptx');
```

You can change the ZIP Adapter for the writer. By default, the ZIP Adapter is `ZipArchiveAdapter`.

``` php
<?php

use PhpOffice\Common\Adapter\Zip\PclZipAdapter;
use PhpOffice\Common\Adapter\Zip\ZipArchiveAdapter;

$writer = IOFactory::createWriter($oPhpPresentation, 'PowerPoint2007');
$writer->setZipAdapter(new PclZipAdapter());
$writer->save(__DIR__ . '/sample.pptx');
```

## Serialized
The name of the writer is `Serialized`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpPresentation, 'Serialized');
$writer->save(__DIR__ . '/sample.phppt');
```

You can change the ZIP Adapter for the writer. By default, the ZIP Adapter is `ZipArchiveAdapter`.

``` php
<?php

use PhpOffice\Common\Adapter\Zip\PclZipAdapter;
use PhpOffice\Common\Adapter\Zip\ZipArchiveAdapter;

$writer = IOFactory::createWriter($oPhpPresentation, 'Serialized');
$writer->setZipAdapter(new PclZipAdapter());
$writer->save(__DIR__ . '/sample.phppt');
```

## Supported features

Keynote is Keynote '09 (APXL). PDF is the HTML output rendered by DomPDF (`dompdf/dompdf`, a suggested
dependency). WMF and EMF (EMF+ too) images need `phpoffice/wmf` (a suggested dependency) and GD: the PPTX
and ODP Writers keep them as they are, and the HTML, PDF and Keynote Writers write them as PNG.
Serialized keeps the whole object graph, so it has every feature of the model, with the exceptions its
notes list.

:material-check: supported, :material-alert-outline: partly supported, :material-close: not supported, empty: the format has no such feature. A number points to its note under the table.

| Features |  | HTML | Keynote | ODP | PDF | PPTX | Serialized |
| --- | --- | --- | --- | --- | --- | --- | --- |
| **Document** | Standard properties (title, author, subject, keywords, dates...) | :material-close: | :material-close: | :material-alert-outline:<sup>1</sup> | :material-close: | :material-check: | :material-check: |
|  | Custom properties |  |  | :material-check: |  | :material-check: | :material-check: |
|  | Mark as final |  |  |  |  | :material-check: | :material-check: |
|  | Slide size / layout | :material-close: | :material-check: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Default language | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Presentation properties (slideshow type, loop, zoom, last view) |  | :material-close: | :material-alert-outline:<sup>2</sup> |  | :material-check: | :material-check: |
| **Slides** | Slides | :material-alert-outline:<sup>3</sup> | :material-check: | :material-check: | :material-check: | :material-check: | :material-check: |
|  | Slide name | :material-close: |  | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Hidden slide | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Background (colour, image) | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-alert-outline:<sup>4</sup> |
|  | Transition | :material-close: | :material-close: | :material-alert-outline:<sup>5</sup> | :material-close: | :material-check: | :material-check: |
|  | Animation | :material-close: | :material-close: | :material-close: | :material-close: | :material-check: | :material-check: |
|  | Speaker notes | :material-close: | :material-alert-outline:<sup>6</sup> | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Masters and layouts | :material-close: | :material-close: | :material-alert-outline:<sup>7</sup> | :material-close: | :material-check: | :material-alert-outline:<sup>8</sup> |
|  | Placeholders (title, body, date, footer, slide number) | :material-alert-outline:<sup>9</sup> | :material-alert-outline:<sup>10</sup> | :material-check: | :material-alert-outline:<sup>11</sup> | :material-check: | :material-check: |
| **Shape (all)** | Name | :material-close: |  | :material-alert-outline:<sup>12</sup> |  | :material-check: | :material-check: |
|  | Description (alternative text) | :material-alert-outline:<sup>13</sup> | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Decorative flag | :material-close: |  | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Hyperlink on the shape | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Hyperlink tooltip | :material-close: |  | :material-close: | :material-close: | :material-check: | :material-check: |
|  | Position and size | :material-check: | :material-check: | :material-check: | :material-check: | :material-check: | :material-check: |
|  | Rotation | :material-close: | :material-close: | :material-alert-outline:<sup>14</sup> | :material-close: | :material-alert-outline:<sup>15</sup> | :material-check: |
|  | Fill | :material-close: | :material-close: | :material-alert-outline:<sup>16</sup> | :material-close: | :material-check: | :material-check: |
|  | Border / outline | :material-close: | :material-close: | :material-alert-outline:<sup>17</sup> | :material-close: | :material-check: | :material-check: |
|  | Shadow | :material-alert-outline:<sup>18</sup> | :material-close: | :material-alert-outline:<sup>19</sup> | :material-close: | :material-check: | :material-check: |
|  | Order (z-order) of shapes on a slide | :material-check: | :material-check: | :material-check: | :material-check: | :material-check: | :material-check: |
| **Shape types** | AutoShape | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Image (file, Base64, ZIP, GD / memory) | :material-check: | :material-check: | :material-check: | :material-check: | :material-check: | :material-alert-outline:<sup>20</sup> |
|  | Media (audio, video) | :material-alert-outline:<sup>21</sup> | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Line | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Group | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-alert-outline:<sup>22</sup> |
|  | RichText (text box) | :material-check: | :material-alert-outline:<sup>23</sup> | :material-check: | :material-check: | :material-check: | :material-check: |
|  | Table | :material-alert-outline:<sup>24</sup> | :material-close: | :material-check: | :material-alert-outline:<sup>25</sup> | :material-check: | :material-check: |
|  | Chart | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Comment | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
| **Text** | Paragraph alignment and indent | :material-alert-outline:<sup>26</sup> | :material-close: | :material-alert-outline:<sup>27</sup> | :material-alert-outline:<sup>28</sup> | :material-check: | :material-check: |
|  | Line spacing, spacing before/after | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Bullets (character, colour, size) | :material-close: | :material-close: | :material-alert-outline:<sup>29</sup> | :material-close: | :material-check: | :material-check: |
|  | Numbered lists (format, start) | :material-close: | :material-close: | :material-check:<sup>30</sup> | :material-close: | :material-check: | :material-check: |
|  | Font: name, size, bold, italic, colour | :material-alert-outline:<sup>31</sup> | :material-close: | :material-check: | :material-alert-outline:<sup>32</sup> | :material-check: | :material-check: |
|  | Font: underline, strikethrough, superscript/subscript, caps | :material-close: | :material-close: | :material-alert-outline:<sup>33</sup> | :material-close: | :material-check: | :material-check: |
|  | Language of a run | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Hyperlink on text | :material-check: | :material-close: | :material-check: | :material-check: | :material-check: | :material-check: |
|  | Fields (date/time, slide number) | :material-alert-outline:<sup>34</sup> | :material-close: | :material-alert-outline:<sup>35</sup> | :material-alert-outline:<sup>36</sup> | :material-check: | :material-check: |
|  | Text box: insets, autofit, wrap, columns, vertical/RTL | :material-close: | :material-close: | :material-alert-outline:<sup>37</sup> | :material-close: | :material-check: | :material-check: |
| **Table** | Merged cells (column span, row span) | :material-alert-outline:<sup>38</sup> | :material-close: | :material-alert-outline:<sup>39</sup> | :material-alert-outline:<sup>40</sup> | :material-alert-outline:<sup>41</sup> | :material-check: |
|  | Cell borders and fill | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Header row | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Column widths, row heights | :material-close: | :material-close: | :material-alert-outline:<sup>42</sup> | :material-close: | :material-check: | :material-check: |
| **Charts** | Area | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Bar | :material-close: | :material-close: | :material-alert-outline:<sup>43</sup> | :material-close: | :material-check: | :material-check: |
|  | Bar3D | :material-close: | :material-close: | :material-alert-outline:<sup>44</sup> | :material-close: | :material-check: | :material-check: |
|  | Doughnut | :material-close: | :material-close: | :material-alert-outline:<sup>45</sup> | :material-close: | :material-check: | :material-check: |
|  | Line | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Pie | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Pie3D | :material-close: | :material-close: | :material-alert-outline:<sup>46</sup> | :material-close: | :material-check: | :material-check: |
|  | Radar | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Scatter | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Chart title | :material-close: | :material-close: | :material-check: | :material-close: | :material-check: | :material-check: |
|  | Legend | :material-close: | :material-close: | :material-alert-outline:<sup>47</sup> | :material-close: | :material-check: | :material-check: |
|  | Axes (title, min/max, gridlines, format) | :material-close: | :material-close: | :material-alert-outline:<sup>48</sup> | :material-close: | :material-check: | :material-check: |
|  | Series: labels, colours, markers | :material-close: | :material-close: | :material-alert-outline:<sup>49</sup> | :material-close: | :material-check: | :material-check: |
|  | Chart language | :material-close: |  | :material-close: | :material-close: | :material-check: | :material-check: |

1. Category, company, revision, status not written.
2. Loop and browse type only.
3. Only first 5 slides reachable.
4. Background image kept as external path.
5. 13 transition types written as none.
6. Plain text, all note boxes merged.
7. First master background only.
8. Master/layout images kept as external paths.
9. Slide text only, no master inheritance.
10. Every text box written as body placeholder.
11. Slide text only, no master inheritance.
12. Images, media, AutoShapes; chart gets title.
13. Images only (alt/title).
14. Text boxes and AutoShapes only.
15. Not tables.
16. Images/AutoShapes solid only; group gradients undefined.
17. Not images; lines without dash.
18. 8 directions; alpha fades whole shape.
19. Not charts or groups.
20. Base64 and GD throw TypeError.
21. Always <video>, also for audio.
22. Child images kept as external paths.
23. Plain text; formatting dropped.
24. Cell text and colspan only.
25. Cell text and colspan only.
26. Left/center/right only; no justify, indent.
27. Horizontal and RTL; indent only in lists.
28. Left/center/right only; no justify, indent.
29. No bullet colour.
30. Impress continues the numbering of a new list after a plain paragraph ([tdf#173721](https://bugs.documentfoundation.org/show_bug.cgi?id=173721)); PowerPoint and LibreOffice Writer restart it as written.
31. No italic; pt size written as px.
32. No italic; pt size written as px.
33. Small caps written as lowercase.
34. Static placeholder text, not computed.
35. Date/time format not written.
36. Static placeholder text, not computed.
37. No vertical text; autofit as auto-grow.
38. Colspan only; spanned cells still emitted.
39. Column span only.
40. Colspan only; spanned cells still emitted.
41. Corner of two-way span written unmerged.
42. Row heights only.
43. Gap width not written; overlap from grouping.
44. View3D, gap width not written.
45. Hole size, first slice angle not written.
46. View3D not written.
47. No fill or border.
48. No number format, tick marks, reverse order.
49. No label position or series-name label.

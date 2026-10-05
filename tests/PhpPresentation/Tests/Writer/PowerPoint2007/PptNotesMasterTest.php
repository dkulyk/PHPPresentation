<?php

/**
 * This file is part of PHPPresentation - A pure PHP library for reading and writing
 * presentations documents.
 *
 * PHPPresentation is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPPresentation/contributors.
 *
 * @see        https://github.com/PHPOffice/PHPPresentation
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

declare(strict_types=1);

namespace PhpPresentation\Tests\Writer\PowerPoint2007;

use PhpOffice\PhpPresentation\Tests\PhpPresentationTestCase;

class PptNotesMasterTest extends PhpPresentationTestCase
{
    protected $writerName = 'PowerPoint2007';

    public function testNoNote(): void
    {
        $this->assertZipFileNotExists('ppt/notesMasters/notesMaster1.xml');
        $this->assertZipFileNotExists('ppt/theme/theme2.xml');
        $this->assertZipXmlElementNotExists('ppt/presentation.xml', '/p:presentation/p:notesMasterIdLst');
        $this->assertZipXmlElementNotExists('ppt/_rels/presentation.xml.rels', '/Relationships/Relationship[@Target="notesMasters/notesMaster1.xml"]');
        $this->assertZipXmlElementNotExists('[Content_Types].xml', '/*/*[local-name()="Override"][@PartName="/ppt/notesMasters/notesMaster1.xml"]');
        $this->assertIsSchemaECMA376Valid();
    }

    public function testNote(): void
    {
        // A note on the second slide only
        $this->oPresentation->createSlide()->getNote()->createRichTextShape()->createTextRun('Note');

        $relType = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/';

        // The notes master, and the theme it takes after the one of the slide master
        $this->assertZipXmlElementExists('ppt/notesMasters/notesMaster1.xml', '/p:notesMaster/p:cSld/p:spTree/p:sp/p:nvSpPr/p:nvPr/p:ph[@type="sldImg"]');
        $this->assertZipXmlElementExists('ppt/notesMasters/notesMaster1.xml', '/p:notesMaster/p:cSld/p:spTree/p:sp/p:nvSpPr/p:nvPr/p:ph[@type="body"]');
        $this->assertZipXmlElementExists('ppt/notesMasters/notesMaster1.xml', '/p:notesMaster/p:clrMap');
        $this->assertZipXmlElementExists('ppt/notesMasters/_rels/notesMaster1.xml.rels', '/Relationships/Relationship[@Type="' . $relType . 'theme"][@Target="../theme/theme2.xml"]');
        $this->assertZipXmlElementExists('ppt/theme/theme2.xml', '/a:theme/a:themeElements');
        $this->assertZipXmlElementExists('[Content_Types].xml', '/*/*[local-name()="Override"][@PartName="/ppt/notesMasters/notesMaster1.xml"][@ContentType="application/vnd.openxmlformats-officedocument.presentationml.notesMaster+xml"]');
        $this->assertZipXmlElementExists('[Content_Types].xml', '/*/*[local-name()="Override"][@PartName="/ppt/theme/theme2.xml"][@ContentType="application/vnd.openxmlformats-officedocument.theme+xml"]');

        // The presentation lists it, and the identifiers of the slides still name the slides
        $this->assertZipXmlElementExists('ppt/_rels/presentation.xml.rels', '/Relationships/Relationship[@Id="rId3"][@Type="' . $relType . 'notesMaster"][@Target="notesMasters/notesMaster1.xml"]');
        $this->assertZipXmlElementExists('ppt/presentation.xml', '/p:presentation/p:sldMasterIdLst/following-sibling::*[1][self::p:notesMasterIdLst]/p:notesMasterId[@r:id="rId3"]');
        $this->assertZipXmlElementExists('ppt/_rels/presentation.xml.rels', '/Relationships/Relationship[@Id="rId4"][@Target="slides/slide1.xml"]');
        $this->assertZipXmlElementExists('ppt/_rels/presentation.xml.rels', '/Relationships/Relationship[@Id="rId5"][@Target="slides/slide2.xml"]');
        $this->assertZipXmlAttributeEquals('ppt/presentation.xml', '/p:presentation/p:sldIdLst/p:sldId[1]', 'r:id', 'rId4');
        $this->assertZipXmlAttributeEquals('ppt/presentation.xml', '/p:presentation/p:sldIdLst/p:sldId[2]', 'r:id', 'rId5');

        // The notes slide names its slide and the notes master
        $this->assertZipFileNotExists('ppt/notesSlides/_rels/notesSlide1.xml.rels');
        $this->assertZipXmlElementExists('ppt/notesSlides/_rels/notesSlide2.xml.rels', '/Relationships/Relationship[@Type="' . $relType . 'slide"][@Target="../slides/slide2.xml"]');
        $this->assertZipXmlElementExists('ppt/notesSlides/_rels/notesSlide2.xml.rels', '/Relationships/Relationship[@Type="' . $relType . 'notesMaster"][@Target="../notesMasters/notesMaster1.xml"]');
        $this->assertIsSchemaECMA376Valid();
    }

    public function testNoteWithTwoSlideMasters(): void
    {
        $this->oPresentation->createMasterSlide();
        $this->oPresentation->getActiveSlide()->getNote()->createRichTextShape()->createTextRun('Note');

        $this->assertZipXmlElementExists('ppt/notesMasters/_rels/notesMaster1.xml.rels', '/Relationships/Relationship[@Target="../theme/theme3.xml"]');
        $this->assertZipXmlElementExists('ppt/theme/theme3.xml', '/a:theme');
        $this->assertZipXmlElementExists('[Content_Types].xml', '/*/*[local-name()="Override"][@PartName="/ppt/theme/theme3.xml"]');
        $this->assertZipXmlElementExists('ppt/_rels/presentation.xml.rels', '/Relationships/Relationship[@Id="rId4"][@Target="notesMasters/notesMaster1.xml"]');
        $this->assertZipXmlAttributeEquals('ppt/presentation.xml', '/p:presentation/p:notesMasterIdLst/p:notesMasterId', 'r:id', 'rId4');
        $this->assertZipXmlAttributeEquals('ppt/presentation.xml', '/p:presentation/p:sldIdLst/p:sldId', 'r:id', 'rId5');
        $this->assertIsSchemaECMA376Valid();
    }
}

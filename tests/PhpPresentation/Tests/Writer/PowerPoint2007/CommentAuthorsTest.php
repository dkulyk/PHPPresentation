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

use PhpOffice\PhpPresentation\Shape\Comment;
use PhpOffice\PhpPresentation\Tests\PhpPresentationTestCase;

class CommentAuthorsTest extends PhpPresentationTestCase
{
    protected $writerName = 'PowerPoint2007';

    public function testComments(): void
    {
        $expectedElement = '/p:cmAuthorLst/p:cmAuthor';
        $expectedName = 'Name';
        $expectedInitials = 'Initials';

        $oAuthor = new Comment\Author();
        $oAuthor->setName($expectedName);
        $oAuthor->setInitials($expectedInitials);
        $oComment = new Comment();
        $oComment->setAuthor($oAuthor);
        $this->oPresentation->getActiveSlide()->addShape($oComment);

        $this->assertZipFileExists('ppt/commentAuthors.xml');
        $this->assertZipXmlElementExists('ppt/commentAuthors.xml', $expectedElement);
        $this->assertZipXmlAttributeEquals('ppt/commentAuthors.xml', $expectedElement, 'id', 0);
        $this->assertZipXmlAttributeEquals('ppt/commentAuthors.xml', $expectedElement, 'name', $expectedName);
        $this->assertZipXmlAttributeEquals('ppt/commentAuthors.xml', $expectedElement, 'initials', $expectedInitials);
        $this->assertIsSchemaECMA376Valid();
    }

    public function testWithoutComment(): void
    {
        $this->assertZipFileNotExists('ppt/commentAuthors.xml');
        $this->assertIsSchemaECMA376Valid();
    }

    public function testWithoutCommentAuthor(): void
    {
        $oAuthor = (new Comment\Author())->setName('Name')->setInitials('Initials');
        $this->oPresentation->getActiveSlide()->addShape((new Comment())->setAuthor($oAuthor));
        $this->oPresentation->getActiveSlide()->addShape(new Comment());

        // The comment without an author is written with an author without a name, which is not the first one
        $this->assertZipXmlElementCount('ppt/commentAuthors.xml', '/p:cmAuthorLst/p:cmAuthor', 2);
        $this->assertZipXmlElementExists('ppt/commentAuthors.xml', '/p:cmAuthorLst/p:cmAuthor[@id="0"][@name=""][@initials=""][@lastIdx="1"]');
        $this->assertZipXmlElementExists('ppt/commentAuthors.xml', '/p:cmAuthorLst/p:cmAuthor[@id="1"][@name="Name"][@initials="Initials"][@lastIdx="1"]');
        $this->assertZipXmlAttributeEquals('ppt/comments/comment1.xml', '/p:cmLst/p:cm[1]', 'authorId', 1);
        $this->assertZipXmlAttributeEquals('ppt/comments/comment1.xml', '/p:cmLst/p:cm[2]', 'authorId', 0);
        $this->assertIsSchemaECMA376Valid();
    }

    public function testWithoutAnyCommentAuthor(): void
    {
        $this->oPresentation->getActiveSlide()->addShape(new Comment());

        // The content types name the part, so it has to be there and to be related
        $this->assertZipFileExists('ppt/commentAuthors.xml');
        $this->assertZipXmlElementExists('ppt/_rels/presentation.xml.rels', '/Relationships/Relationship[@Target="commentAuthors.xml"]');
        $this->assertZipXmlElementExists('ppt/commentAuthors.xml', '/p:cmAuthorLst/p:cmAuthor[@id="0"][@name=""]');
        $this->assertIsSchemaECMA376Valid();
    }

    public function testWithAnAuthorWithoutNameAndInitials(): void
    {
        // An author with neither a name nor initials is the author of the comments without one
        $this->oPresentation->getActiveSlide()->addShape((new Comment())->setAuthor(new Comment\Author()));
        $this->oPresentation->getActiveSlide()->addShape(new Comment());
        $this->oPresentation->getActiveSlide()->addShape(new Comment());

        $this->assertZipXmlElementCount('ppt/commentAuthors.xml', '/p:cmAuthorLst/p:cmAuthor', 1);
        $this->assertZipXmlElementExists('ppt/commentAuthors.xml', '/p:cmAuthorLst/p:cmAuthor[@id="0"][@name=""][@initials=""][@lastIdx="3"]');
        $this->assertZipXmlAttributeEquals('ppt/comments/comment1.xml', '/p:cmLst/p:cm[1]', 'authorId', 0);
        $this->assertZipXmlAttributeEquals('ppt/comments/comment1.xml', '/p:cmLst/p:cm[2]', 'authorId', 0);
        $this->assertIsSchemaECMA376Valid();
    }

    public function testWithEqualAuthors(): void
    {
        // Two authors of one name and initials are one author, and both carry its id
        $oSlide = $this->oPresentation->createSlide();
        $this->oPresentation->getActiveSlide()->addShape((new Comment())->setAuthor((new Comment\Author())->setName('Other')));
        $this->oPresentation->getActiveSlide()->addShape((new Comment())->setAuthor((new Comment\Author())->setName('Name')));
        $oSlide->addShape((new Comment())->setAuthor((new Comment\Author())->setName('Name')));

        $this->assertZipXmlElementCount('ppt/commentAuthors.xml', '/p:cmAuthorLst/p:cmAuthor', 2);
        $this->assertZipXmlElementExists('ppt/commentAuthors.xml', '/p:cmAuthorLst/p:cmAuthor[@id="1"][@name="Name"][@lastIdx="2"][@clrIdx="1"]');
        $this->assertZipXmlAttributeEquals('ppt/comments/comment1.xml', '/p:cmLst/p:cm[2]', 'authorId', 1);
        $this->assertZipXmlAttributeEquals('ppt/comments/comment2.xml', '/p:cmLst/p:cm', 'authorId', 1);
        $this->assertIsSchemaECMA376Valid();
    }

    public function testWithSameAuthor(): void
    {
        $expectedElement = '/p:cmAuthorLst/p:cmAuthor';

        $oAuthor = new Comment\Author();

        $oComment1 = new Comment();
        $oComment1->setAuthor($oAuthor);
        $this->oPresentation->getActiveSlide()->addShape($oComment1);
        $oComment2 = new Comment();
        $oComment2->setAuthor($oAuthor);
        $this->oPresentation->getActiveSlide()->addShape($oComment2);

        $this->assertZipFileExists('ppt/commentAuthors.xml');
        $this->assertZipXmlElementExists('ppt/commentAuthors.xml', $expectedElement);
        $this->assertZipXmlElementCount('ppt/commentAuthors.xml', $expectedElement, 1);
        $this->assertIsSchemaECMA376Valid();
    }
}

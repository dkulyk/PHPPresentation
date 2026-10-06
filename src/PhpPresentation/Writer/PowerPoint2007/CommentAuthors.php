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

namespace PhpOffice\PhpPresentation\Writer\PowerPoint2007;

use PhpOffice\Common\Adapter\Zip\ZipInterface;
use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpPresentation\Shape\Comment;
use PhpOffice\PhpPresentation\Shape\Comment\Author;

class CommentAuthors extends AbstractDecoratorWriter
{
    public function render(): ZipInterface
    {
        // A comment has to name an author: those without one share an author without a name, as in
        // LibreOffice. It comes first, since `PptComments` writes the comments without an author with the id 0
        $oNoAuthor = new Author();
        /**
         * @var array<string, array<int, Author>>
         */
        $arrayAuthors = [$oNoAuthor->getHashCode() => []];
        foreach ($this->getPresentation()->getAllSlides() as $oSlide) {
            foreach ($this->flattenShapes($oSlide->getShapeCollection()) as $oShape) {
                if (!($oShape instanceof Comment)) {
                    continue;
                }
                $oAuthor = $oShape->getAuthor() ?? $oNoAuthor;
                $arrayAuthors[$oAuthor->getHashCode()][] = $oAuthor;
            }
        }
        $arrayAuthors = array_filter($arrayAuthors);
        if (!empty($arrayAuthors)) {
            $this->getZip()->addFromString('ppt/commentAuthors.xml', $this->writeCommentsAuthors($arrayAuthors));
        }

        return $this->getZip();
    }

    /**
     * @param array<string, array<int, Author>> $arrayAuthors the author of every comment, by author
     *
     * @return string
     */
    protected function writeCommentsAuthors($arrayAuthors)
    {
        $objWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // p:cmAuthorLst
        $objWriter->startElement('p:cmAuthorLst');
        $objWriter->writeAttribute('xmlns:p', 'http://schemas.openxmlformats.org/presentationml/2006/main');

        foreach (array_values($arrayAuthors) as $idxAuthor => $arrayAuthor) {
            // Two authors of one name and initials are one author
            foreach ($arrayAuthor as $oAuthor) {
                $oAuthor->setIndex($idxAuthor);
            }

            // p:cmAuthor
            $objWriter->startElement('p:cmAuthor');
            $objWriter->writeAttribute('id', $idxAuthor);
            $objWriter->writeAttribute('name', (string) $arrayAuthor[0]->getName());
            $objWriter->writeAttribute('initials', (string) $arrayAuthor[0]->getInitials());
            // The comments of an author are numbered from 1
            $objWriter->writeAttribute('lastIdx', count($arrayAuthor));
            $objWriter->writeAttribute('clrIdx', $idxAuthor);
            $objWriter->endElement();
        }

        // ## p:cmAuthorLst
        $objWriter->endElement();

        return $objWriter->getData();
    }
}

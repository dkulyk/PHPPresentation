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
use PhpOffice\Common\Drawing as CommonDrawing;
use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpPresentation\Shape\Comment;
use PhpOffice\PhpPresentation\Slide;

class PptComments extends AbstractDecoratorWriter
{
    /**
     * The number of comments written so far, by the id of their author.
     *
     * @var array<int, int>
     */
    protected $arrayLastIdx = [];

    public function render(): ZipInterface
    {
        foreach ($this->getPresentation()->getAllSlides() as $numSlide => $oSlide) {
            $contentXml = $this->writeSlideComments($oSlide);
            if (empty($contentXml)) {
                continue;
            }
            $this->getZip()->addFromString('ppt/comments/comment' . ($numSlide + 1) . '.xml', $contentXml);
        }

        return $this->getZip();
    }

    protected function writeSlideComments(Slide $oSlide): string
    {
        /**
         * @var Comment[]
         */
        $arrayComment = [];
        foreach ($this->flattenShapes($oSlide->getShapeCollection()) as $oShape) {
            if ($oShape instanceof Comment) {
                $arrayComment[] = $oShape;
            }
        }

        if (empty($arrayComment)) {
            return '';
        }

        $objWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // p:cmLst
        $objWriter->startElement('p:cmLst');
        $objWriter->writeAttribute('xmlns:p', 'http://schemas.openxmlformats.org/presentationml/2006/main');

        foreach ($arrayComment as $oComment) {
            // `CommentAuthors` has given every author its id, and 0 to the author of the comments that have none
            $oAuthor = $oComment->getAuthor();
            $authorId = $oAuthor instanceof Comment\Author ? $oAuthor->getIndex() : 0;
            $this->arrayLastIdx[$authorId] = ($this->arrayLastIdx[$authorId] ?? 0) + 1;

            // p:cmLst > p:cm
            $objWriter->startElement('p:cm');
            $objWriter->writeAttribute('authorId', $authorId);
            $objWriter->writeAttribute('dt', date('Y-m-d\TH:i:s.000000000', $oComment->getDate()));
            // The comments of an author are numbered from 1 through the presentation
            $objWriter->writeAttribute('idx', $this->arrayLastIdx[$authorId]);

            // p:cmLst > p:cm > p:pos
            // Uses 1/8pt for positionning
            // @link : https://social.msdn.microsoft.com/Forums/fr-FR/ebdc12f2-0cff-4fa8-b901-fa6e3198364e/comment-position-units
            $objWriter->startElement('p:pos');
            $objWriter->writeAttribute('x', (int) CommonDrawing::pixelsToPoints($oComment->getOffsetX() * 8));
            $objWriter->writeAttribute('y', (int) CommonDrawing::pixelsToPoints($oComment->getOffsetY() * 8));
            $objWriter->endElement();

            // p:cmLst > p:cm > p:text
            $objWriter->writeElement('p:text', $oComment->getText());

            // p:cmLst > ## p:cm
            $objWriter->endElement();
        }

        // ## p:cmLst
        $objWriter->endElement();

        return $objWriter->getData();
    }
}

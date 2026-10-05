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

class PptNotesMaster extends AbstractDecoratorWriter
{
    /**
     * Add the notes master the notes slides are based on, when a slide has a note.
     */
    public function render(): ZipInterface
    {
        if ($this->hasNotes()) {
            $this->getZip()->addFromString('ppt/notesMasters/_rels/notesMaster1.xml.rels', $this->writeNotesMasterRelationships());
            $this->getZip()->addFromString('ppt/notesMasters/notesMaster1.xml', $this->writeNotesMaster());
        }

        return $this->getZip();
    }

    /**
     * Write notes master relationships to XML format.
     *
     * @return string XML Output
     */
    protected function writeNotesMasterRelationships(): string
    {
        // Create XML writer
        $objWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $objWriter->startElement('Relationships');
        $objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');
        // Relationship theme/themeX.xml: the theme after those of the slide masters
        $this->writeRelationship($objWriter, 1, 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme', '../theme/theme' . (count($this->oPresentation->getAllMasterSlides()) + 1) . '.xml');
        $objWriter->endElement();

        // Return
        return $objWriter->getData();
    }

    /**
     * Write notes master to XML format: the image of the slide and the text of the notes,
     * on the portrait page `p:notesSz` gives.
     *
     * @return string XML Output
     */
    protected function writeNotesMaster(): string
    {
        // Create XML writer
        $objWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // p:notesMaster
        $objWriter->startElement('p:notesMaster');
        $objWriter->writeAttribute('xmlns:a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
        $objWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $objWriter->writeAttribute('xmlns:p', 'http://schemas.openxmlformats.org/presentationml/2006/main');

        $objWriter->writeRaw('<p:cSld>
  <p:spTree>
   <p:nvGrpSpPr>
    <p:cNvPr id="1" name=""/>
    <p:cNvGrpSpPr/>
    <p:nvPr/>
   </p:nvGrpSpPr>
   <p:grpSpPr>
    <a:xfrm>
     <a:off x="0" y="0"/>
     <a:ext cx="0" cy="0"/>
     <a:chOff x="0" y="0"/>
     <a:chExt cx="0" cy="0"/>
    </a:xfrm>
   </p:grpSpPr>
   <p:sp>
    <p:nvSpPr>
     <p:cNvPr id="2" name="Slide Image Placeholder 1"/>
     <p:cNvSpPr>
      <a:spLocks noGrp="1" noRot="1" noChangeAspect="1"/>
     </p:cNvSpPr>
     <p:nvPr>
      <p:ph type="sldImg" idx="2"/>
     </p:nvPr>
    </p:nvSpPr>
    <p:spPr>
     <a:xfrm>
      <a:off x="685800" y="1143000"/>
      <a:ext cx="5486400" cy="3086100"/>
     </a:xfrm>
     <a:prstGeom prst="rect">
      <a:avLst/>
     </a:prstGeom>
     <a:noFill/>
     <a:ln w="12700">
      <a:solidFill>
       <a:prstClr val="black"/>
      </a:solidFill>
     </a:ln>
    </p:spPr>
   </p:sp>
   <p:sp>
    <p:nvSpPr>
     <p:cNvPr id="3" name="Notes Placeholder 2"/>
     <p:cNvSpPr>
      <a:spLocks noGrp="1"/>
     </p:cNvSpPr>
     <p:nvPr>
      <p:ph type="body" idx="1"/>
     </p:nvPr>
    </p:nvSpPr>
    <p:spPr>
     <a:xfrm>
      <a:off x="685800" y="4400550"/>
      <a:ext cx="5486400" cy="3600450"/>
     </a:xfrm>
     <a:prstGeom prst="rect">
      <a:avLst/>
     </a:prstGeom>
    </p:spPr>
    <p:txBody>
     <a:bodyPr/>
     <a:lstStyle/>
     <a:p>
      <a:endParaRPr/>
     </a:p>
    </p:txBody>
   </p:sp>
  </p:spTree>
 </p:cSld>
 <p:clrMap bg1="lt1" tx1="dk1" bg2="lt2" tx2="dk2" accent1="accent1" accent2="accent2" accent3="accent3" accent4="accent4" accent5="accent5" accent6="accent6" hlink="hlink" folHlink="folHlink"/>');

        // p:notesMaster
        $objWriter->endElement();

        // Return
        return $objWriter->getData();
    }
}

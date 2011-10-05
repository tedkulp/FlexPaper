<?php // -*- mode:php; c-file-style:linux; tab-width:4; indent-tabs-mode:t; c-basic-offset: 4; -*-
#-------------------------------------------------------------------------
# Module: FlexPaper -= display PDFs in the browser via smarty tag
# Version: 1.0
#
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2005 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http:	//www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------

if (!isset($gCms)) exit;

$pdf_filename = isset($params['filename']) ? $params['filename'] : '';
$width = isset($params['width']) ? $params['width'] : '660';
$height = isset($params['height']) ? $params['height'] : '480';

//Have we already converted it?
if (!$this->checkConvertedSwfExists($pdf_filename))
{
	//Run the conversion process
	$this->convertPdfToSwf($pdf_filename, $this->createSwfFilename($pdf_filename));
}

//Now check again and display it for reals
if ($this->checkConvertedSwfExists($pdf_filename))
{
	$swf_url = $this->createSwfUrl($pdf_filename);
	echo <<<PDF
		<div>
			<a id="viewerPlaceHolder" style="width:{$width}px;height:{$height}px;display:block"></a>

			<script type="text/javascript"> 
				var fp = new FlexPaperViewer(
					 'modules/FlexPaper/FlexPaperViewer',
					 'viewerPlaceHolder', {
						config : {
							 SwfFile : escape('{$swf_url}'),
							 Scale : 0.6, 
							 ZoomTransition : 'easeOut',
							 ZoomTime : 0.5,
							 ZoomInterval : 0.2,
							 FitPageOnLoad : true,
							 FitWidthOnLoad : false,
							 PrintEnabled : true,
							 FullScreenAsMaxWindow : false,
							 ProgressiveLoading : false,
							 MinZoomSize : 0.2,
							 MaxZoomSize : 5,
							 SearchMatchAll : false,
							 InitViewMode : 'Portrait',

							 ViewModeToolsVisible : true,
							 ZoomToolsVisible : true,
							 NavToolsVisible : true,
							 CursorToolsVisible : true,
							 SearchToolsVisible : true,

							 localeChain: 'en_US'
						}
					}
				);
			</script>
		</div>
PDF;
}

# vim:ts=4 sw=4 noet

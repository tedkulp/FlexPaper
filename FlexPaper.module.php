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

$cgextensions = cms_join_path($gCms->config['root_path'], 'modules', 'CGExtensions', 'CGExtensions.module.php');
if (!is_readable($cgextensions))
{
	echo '<h1><font color="red">ERROR: The CGExtensions module could not be found.</font></h1>';
	return;
}
require_once($cgextensions);

class FlexPaper extends CGExtensions
{
	public function __construct()
	{
		parent::__construct();
	}

	public function GetName()
	{
		return 'FlexPaper';
	}

	public function GetFriendlyName()
	{
		return $this->Lang('friendlyname');
	}

	public function GetVersion()
	{
		return '1.0';
	}

	public function GetHelp()
	{
		return $this->Lang('help');
	}

	public function GetAuthor()
	{
		return 'Ted Kulp';
	}

	public function GetAuthorEmail()
	{
		return 'ted@cmsmadesimple.org';
	}

	public function GetChangeLog()
	{
		$fn = dirname(__FILE__).'/changelog.inc';
		return @file_get_contents($fn);
	}

	public function IsPluginModule()
	{
		return TRUE;
	}

	public function HasAdmin()
	{
		return FALSE;
	}

	public function MinimumCMSVersion()
	{
		return '1.9.4';
	}

	public function AllowAutoInstall()
	{
		return FALSE;
	}

	public function AllowAutoUpgrade()
	{
		return FALSE;
	}

	public function GetDependencies()
	{
		return array('CGExtensions'=>'1.24');
	}

	public function SetParameters()
	{
		parent::SetParameters();

		$this->RegisterModulePlugin();
		$this->RestrictUnknownParams();

		$this->CreateParameter('filename', '', $this->Lang('param_filename'));
		$this->SetParameterType('filename', CLEAN_STRING);
	}

	public function getConfig()
	{
		global $gCms;
		return $gCms->GetConfig();
	}

	public function getCommandLineLocation()
	{
		return '/usr/local/bin/pdf2swf';
	}

	public function createSwfFilename($pdf_filename, $page = '')
	{
		$config = $this->getConfig();
		$filename = $config['previews_path'] . '/' . md5($pdf_filename);
		if (!empty($page))
		{
			$filename .= '-' . $page;
		}
		$filename .= '.swf';
		return $filename;
	}

	public function createSwfUrl($pdf_filename)
	{
		$config = $this->getConfig();
		$filename = $config['root_url'] . '/tmp/cache/' . md5($pdf_filename);
		if (!empty($page))
		{
			$filename .= '-' . $page;
		}
		$filename .= '.swf';
		return $filename;
	}

	public function checkConvertedSwfExists($pdf_filename, $page = '')
	{
		return is_readable($this->createSwfFilename($pdf_filename, $page));
	}

	public function convertPdfToSwf($pdf_filename, $swf_filename)
	{
		$result = false;

		if (is_readable($pdf_filename))
		{
			$command = $this->getCommandLineLocation() . " {$pdf_filename} -o {$swf_filename} -f -T 9 -t -s storeallcharacters";
			$output = array();
			$return_var = 0;
			exec($command, $output, $return_var);
			if ($return_var == 0)
			{
				$result = true;
			}
			else
			{
				$result = false;
			}
		}

		return $result;
	}
}

# vim:ts=4 sw=4 noet

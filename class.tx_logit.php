<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Kasper Skaarhoj <kasper@typo3.com>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Class for simple logging
 *
 * $Id$
 *
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   64: class tx_logit 
 *   95:     function doLog($param,$ref)	
 *  112:     function writeLog()	
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */










/**
 * Class for simple developer logging to file
 *
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 * @package TYPO3
 * @subpackage tx_logit
 */
class tx_logit {

		// External:
	var $append = 1;		// If set, the logfile is not overridden but appended to for each run.
	var $logFile = 'typo3temp/logit.log';	// Log file, must be in typo3temp/
	var $filterKey = '';		// Set to extension key output to log only.
	var $filterSev = 0;				// Set to severity value (-1 - 3) to log only.
	var $html = FALSE;				// Set true, and the log file is written as HTML in <pre> tags.

		// Internal:
	var $counter = 0;
	var $logData = array();

	var $stateTable = array(
		-1 => 'OK',
		1 => 'info',
		2 => 'warning',
		3 => 'fatal!',
	);





	/**
	 * Log function, called from t3lib_div::devLog via. hook configuration.
	 *
	 * @param	[type]		$param: ...
	 * @param	[type]		$ref: ...
	 * @return	void
	 */
	function doLog($param,$ref)	{
		$this->counter++;
		$this->logData[] = $param;

		if ($param['dataVar']['_FLUSH'])	{
			#t3lib_div::debug($this->logData,'logit output');

			$this->writeLog();
			$this->logData = array();
		}
	}

	/**
	 * Writes log to log file in typo3temp/ dir.
	 *
	 * @return	void
	 */
	function writeLog()	{

			// Create log file output:
		$lines = array();
		$lines[] = 'SESSION BEGIN: '.date('Y-m-d H:i:s',$GLOBALS['EXEC_TIME']);
		foreach($this->logData as $dat)	{
			if (!$this->filterKey || $dat['extKey']==$this->filterKey)	{
				if (!$this->filterSev || $dat['severity']>=$this->filterSev)	{
					$lines[] = $dat['extKey'].'['.$dat['severity'].'] : '.$dat['msg'];
				}
			}
		}

			// write:
		if (t3lib_div::isFirstPartOfStr($this->logFile,'typo3temp/'))	{
			$file = t3lib_div::getFileAbsFileName($this->logFile);

			if ($file)	{
					// Loading current file content if applicable:
				$fileContent = ($this->append && @is_file($file)) ? trim(t3lib_div::getUrl($file)).chr(10) : '';

					// Adding new content:
				$fileContent.= implode(chr(10),$lines).chr(10);

					// Format in HTML?
				if ($this->html)	{
					$fileContent = '<pre>'.htmlspecialchars($fileContent).'</pre>';
				}

					// Write to file:
				t3lib_div::writeFile($file,$fileContent);
			} else debug('No log file: ');
		}
	}
}

?>

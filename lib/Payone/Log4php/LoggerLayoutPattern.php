<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package log4php
 */

/**
 * A flexible layout configurable with pattern string.
 *
 * @version $Revision: 1059292 $
 * @package log4php
 * @subpackage layouts
 */
class Payone_Log4php_LoggerLayoutPattern extends Payone_Log4php_LoggerLayout {
	
	/** Default conversion pattern */
	const DEFAULT_CONVERSION_PATTERN = '%m%n';

	/** Default conversion TTCC Pattern */
	const TTCC_CONVERSION_PATTERN = '%d [%t] %p %c %x - %m%n';

	/** The conversion pattern. */ 
	protected $pattern = self::DEFAULT_CONVERSION_PATTERN;
	
	/** Maps conversion keywords to the relevant converter. */
	protected static $defaultConverterMap = array(
		'c' => 'LoggerPatternConverterLogger',
		'lo' => 'LoggerPatternConverterLogger',
		'logger' => 'LoggerPatternConverterLogger',
		
		'C' => 'LoggerPatternConverterClass',
		'class' => 'LoggerPatternConverterClass',
		
		'cookie' => 'LoggerPatternConverterCookie',
		
		'd' => 'LoggerPatternConverterDate',
		'date' => 'LoggerPatternConverterDate',
		
		'e' => 'LoggerPatternConverterEnvironment',
		'env' => 'LoggerPatternConverterEnvironment',
		
		'ex' => 'LoggerPatternConverterThrowable',
		'throwable' => 'LoggerPatternConverterThrowable',
		
		'F' => 'LoggerPatternConverterFile',
		'file' => 'LoggerPatternConverterFile',
		
		'L' => 'LoggerPatternConverterLine',
		'line' => 'LoggerPatternConverterLine',
		
		'm' => 'LoggerPatternConverterMessage',
		'msg' => 'LoggerPatternConverterMessage',
		'message' => 'LoggerPatternConverterMessage',
		
		'M' => 'LoggerPatternConverterMethod',
		'method' => 'LoggerPatternConverterMethod',
		
		'n' => 'LoggerPatternConverterNewLine',
		'newline' => 'LoggerPatternConverterNewLine',
		
		'p' => 'LoggerPatternConverterLevel',
		'le' => 'LoggerPatternConverterLevel',
		'level' => 'LoggerPatternConverterLevel',
	
		'r' => 'LoggerPatternConverterRelative',
		'relative' => 'LoggerPatternConverterRelative',
		
		'req' => 'LoggerPatternConverterRequest',
		'request' => 'LoggerPatternConverterRequest',
		
		's' => 'LoggerPatternConverterServer',
		'server' => 'LoggerPatternConverterServer',
		
		'ses' => 'LoggerPatternConverterSession',
		'session' => 'LoggerPatternConverterSession',
		
		'sid' => 'LoggerPatternConverterSessionID',
		'sessionid' => 'LoggerPatternConverterSessionID',
	
		't' => 'LoggerPatternConverterProcess',
		'pid' => 'LoggerPatternConverterProcess',
		'process' => 'LoggerPatternConverterProcess',
		
		'x' => 'LoggerPatternConverterNDC',
		'ndc' => 'LoggerPatternConverterNDC',
			
		'X' => 'LoggerPatternConverterMDC',
		'mdc' => 'LoggerPatternConverterMDC',
	);

	protected $converterMap = array();
	
	/** 
	 * Head of a chain of Converters.
	 * @var Payone_Log4php_LoggerPatternConverter
	 */
	private $head;

	public static function getDefaultConverterMap() {
		return self::$defaultConverterMap;
	}
	
	public function __construct() {
		$this->converterMap = self::$defaultConverterMap;
	}
	
	/**
	 * Set the <b>ConversionPattern</b> option. This is the string which
	 * controls formatting and consists of a mix of literal content and
	 * conversion specifiers.
	 */
	public function setConversionPattern($conversionPattern) {
		$this->pattern = $conversionPattern;
	}
	
	public function activateOptions() {
		if (!isset($this->pattern)) {
			throw new Payone_Log4php_LoggerException("Mandatory parameter 'conversionPattern' is not set.");
		}
		
		$parser = new Payone_Log4php_LoggerPatternParser($this->pattern, $this->converterMap);
		$this->head = $parser->parse();
	}
	
	/**
	 * Produces a formatted string as specified by the conversion pattern.
	 *
	 * @param Payone_Log4php_LoggerLoggingEvent $event
	 * @return string
	 */
	public function format(Payone_Log4php_LoggerLoggingEvent $event) {
		$sbuf = '';
		$converter = $this->head;
		while ($converter !== null) {
			$converter->format($sbuf, $event);
			$converter = $converter->next;
		}
		return $sbuf;
	}
}
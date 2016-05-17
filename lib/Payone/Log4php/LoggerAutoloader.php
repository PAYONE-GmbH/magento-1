<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * 
 *		http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @package log4php
 */

if (function_exists('__autoload')) {
	//trigger_error("log4php: It looks like your code is using an __autoload() function. log4php uses spl_autoload_register() which will bypass your __autoload() function and may break autoloading.", E_USER_WARNING);
}

spl_autoload_register(array('Payone_Log4php_LoggerAutoloader', 'autoload'));

/**
 * Class autoloader.
 * 
 * @package log4php
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version $Revision$
 */
class Payone_Log4php_LoggerAutoloader {
	
	/** Maps classnames to files containing the class. */
	private static $classes = array(
	
		// Base
		'Payone_Log4php_LoggerAppender' => '/LoggerAppender.php',
		'Payone_Log4php_LoggerAppenderPool' => '/LoggerAppenderPool.php',
		'Payone_Log4php_LoggerConfigurable' => '/LoggerConfigurable.php',
		'Payone_Log4php_LoggerConfigurator' => '/LoggerConfigurator.php',
		'Payone_Log4php_LoggerException' => '/LoggerException.php',
		'Payone_Log4php_LoggerFilter' => '/LoggerFilter.php',
		'Payone_Log4php_LoggerHierarchy' => '/LoggerHierarchy.php',
		'Payone_Log4php_LoggerLevel' => '/LoggerLevel.php',
		'Payone_Log4php_LoggerLocationInfo' => '/LoggerLocationInfo.php',
		'Payone_Log4php_LoggerLoggingEvent' => '/LoggerLoggingEvent.php',
		'Payone_Log4php_LoggerMDC' => '/LoggerMDC.php',
		'Payone_Log4php_LoggerNDC' => '/LoggerNDC.php',
		'Payone_Log4php_LoggerLayout' => '/LoggerLayout.php',
		'Payone_Log4php_LoggerReflectionUtils' => '/LoggerReflectionUtils.php',
		'Payone_Log4php_LoggerRoot' => '/LoggerRoot.php',
		'Payone_Log4php_LoggerThrowableInformation' => '/LoggerThrowableInformation.php',
		
		// Appenders
		'Payone_Log4php_LoggerAppenderConsole' => '/LoggerAppenderConsole.php',
		'Payone_Log4php_LoggerAppenderDailyFile' => '/LoggerAppenderDailyFile.php',
		'Payone_Log4php_LoggerAppenderEcho' => '/LoggerAppenderEcho.php',
		'Payone_Log4php_LoggerAppenderFile' => '/LoggerAppenderFile.php',
		'Payone_Log4php_LoggerAppenderMail' => '/LoggerAppenderMail.php',
		'Payone_Log4php_LoggerAppenderMailEvent' => '/LoggerAppenderMailEvent.php',
		'Payone_Log4php_LoggerAppenderMongoDB' => '/LoggerAppenderMongoDB.php',
		'Payone_Log4php_LoggerAppenderNull' => '/LoggerAppenderNull.php',
		'Payone_Log4php_LoggerAppenderPDO' => '/LoggerAppenderPDO.php',
		'Payone_Log4php_LoggerAppenderPhp' => '/LoggerAppenderPhp.php',
		'Payone_Log4php_LoggerAppenderRollingFile' => '/LoggerAppenderRollingFile.php',
		'Payone_Log4php_LoggerAppenderSocket' => '/LoggerAppenderSocket.php',
		'Payone_Log4php_LoggerAppenderSyslog' => '/LoggerAppenderSyslog.php',
		
		// Configurators
		'Payone_Log4php_LoggerConfigurationAdapter' => '/LoggerConfigurationAdapter.php',
		'Payone_Log4php_LoggerConfigurationAdapterINI' => '/LoggerConfigurationAdapterINI.php',
		'Payone_Log4php_LoggerConfigurationAdapterPHP' => '/LoggerConfigurationAdapterPHP.php',
		'Payone_Log4php_LoggerConfigurationAdapterXML' => '/LoggerConfigurationAdapterXML.php',
		'Payone_Log4php_LoggerConfiguratorDefault' => '/LoggerConfiguratorDefault.php',

		// Filters
		'Payone_Log4php_LoggerFilterDenyAll' => '/LoggerFilterDenyAll.php',
		'Payone_Log4php_LoggerFilterLevelMatch' => '/LoggerFilterLevelMatch.php',
		'Payone_Log4php_LoggerFilterLevelRange' => '/LoggerFilterLevelRange.php',
		'Payone_Log4php_LoggerFilterStringMatch' => '/LoggerFilterStringMatch.php',

		// Helpers
		'Payone_Log4php_LoggerFormattingInfo' => '/LoggerFormattingInfo.php',
		'Payone_Log4php_LoggerOptionConverter' => '/LoggerOptionConverter.php',
		'Payone_Log4php_LoggerPatternParser' => '/LoggerPatternParser.php',
		'Payone_Log4php_LoggerUtils' => '/LoggerUtils.php',
	
		// Pattern converters
		'Payone_Log4php_LoggerPatternConverter' => '/LoggerPatternConverter.php',
		'Payone_Log4php_LoggerPatternConverterClass' => '/LoggerPatternConverterClass.php',
		'Payone_Log4php_LoggerPatternConverterCookie' => '/LoggerPatternConverterCookie.php',
		'Payone_Log4php_LoggerPatternConverterDate' => '/LoggerPatternConverterDate.php',
		'Payone_Log4php_LoggerPatternConverterEnvironment' => '/LoggerPatternConverterEnvironment.php',
		'Payone_Log4php_LoggerPatternConverterFile' => '/LoggerPatternConverterFile.php',
		'Payone_Log4php_LoggerPatternConverterLevel' => '/LoggerPatternConverterLevel.php',
		'Payone_Log4php_LoggerPatternConverterLine' => '/LoggerPatternConverterLine.php',
		'Payone_Log4php_LoggerPatternConverterLiteral' => '/LoggerPatternConverterLiteral.php',
		'Payone_Log4php_LoggerPatternConverterLogger' => '/LoggerPatternConverterLogger.php',
		'Payone_Log4php_LoggerPatternConverterMDC' => '/LoggerPatternConverterMDC.php',
		'Payone_Log4php_LoggerPatternConverterMessage' => '/LoggerPatternConverterMessage.php',
		'Payone_Log4php_LoggerPatternConverterMethod' => '/LoggerPatternConverterMethod.php',
		'Payone_Log4php_LoggerPatternConverterNDC' => '/LoggerPatternConverterNDC.php',
		'Payone_Log4php_LoggerPatternConverterNewLine' => '/LoggerPatternConverterNewLine.php',
		'Payone_Log4php_LoggerPatternConverterProcess' => '/LoggerPatternConverterProcess.php',
		'Payone_Log4php_LoggerPatternConverterRelative' => '/LoggerPatternConverterRelative.php',
		'Payone_Log4php_LoggerPatternConverterRequest' => '/LoggerPatternConverterRequest.php',
		'Payone_Log4php_LoggerPatternConverterServer' => '/LoggerPatternConverterServer.php',
		'Payone_Log4php_LoggerPatternConverterSession' => '/LoggerPatternConverterSession.php',
		'Payone_Log4php_LoggerPatternConverterSessionID' => '/LoggerPatternConverterSessionID.php',
		'Payone_Log4php_LoggerPatternConverterSuperglobal' => '/LoggerPatternConverterSuperglobal.php',
		'Payone_Log4php_LoggerPatternConverterThrowable' => '/LoggerPatternConverterThrowable.php',
		
		// Layouts
		'Payone_Log4php_LoggerLayoutHtml' => '/LoggerLayoutHtml.php',
		'Payone_Log4php_LoggerLayoutPattern' => '/LoggerLayoutPattern.php',
		'Payone_Log4php_LoggerLayoutSerialized' => '/LoggerLayoutSerialized.php',
		'Payone_Log4php_LoggerLayoutSimple' => '/LoggerLayoutSimple.php',
		'Payone_Log4php_LoggerLayoutTTCC' => '/LoggerLayoutTTCC.php',
		'Payone_Log4php_LoggerLayoutXml' => '/LoggerLayoutXml.php',
		
		// Renderers
		'Payone_Log4php_LoggerRendererDefault' => '/LoggerRendererDefault.php',
		'Payone_Log4php_LoggerRendererException' => '/LoggerRendererException.php',
		'Payone_Log4php_LoggerRendererMap' => '/LoggerRendererMap.php',
		'Payone_Log4php_LoggerRendererObject' => '/LoggerRendererObject.php',
	);
	
	/**
	 * Loads a class.
	 * @param string $className The name of the class to load.
	 */
	public static function autoload($className) {
		if(isset(self::$classes[$className])) {
			require_once dirname(__FILE__) . self::$classes[$className];
		}
	}
}

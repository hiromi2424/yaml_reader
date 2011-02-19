<?php
/**
 * YamlReader file
 *
 * PHP 5
 *
 *
 * Licensed under The MIT License
 *
 * @copyright     Copyright 2010, hiromi (https://github.com/hiromi2424)
 * @link          https://github.com/hiromi2424/yaml_reader
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Yaml Reader allows Configure to load configuration values from 
 * files that YAML formatted.
 *
 */
class YamlReader implements ConfigReaderInterface {

/**
 * The extension of files to be loaded, without period.
 *
 * @var string
 */
	public $ext = 'yml';

/**
 * The path this reader finds files on.
 *
 * @var string
 * @access protected
 */
	protected $_path = null;

/**
 * If true, assoc key was applied to parsed array with specific key.
 *
 * @var boolean
 */
	public $baseKey = true;

/**
 * Constructor for YAML Config file reading.
 *
 * @param string $path The path to read config files from. Defaults to CONFIGS
 * @param string $baseKey If true, assoc key was applied to parsed array with specific key.
 * @throws ConfigureException when Spyc class doesn't exsist or specified path was wrong.
 */
	public function __construct($path = CONFIGS, $baseKey = null) {

		if (is_array($path)) {
			extract($path);
		}
		if (empty($path) || is_array($path)) {
			$path = CONFIGS;
		}
		$this->_path = $path;

		if (isset($baseKey)) {
			$this->baseKey = $baseKey;
		}

	}

/**
 * Read a config file and return its contents.
 *
 * Keys with `.` will be treated as values in plugins.  Instead of reading from
 * the initialized path, plugin keys will be located using App::pluginPath().
 *
 *
 * @param string $key The identifier to read from.  If the key has a . it will be treated
 *   as a plugin prefix.
 * @return array Parsed configuration values.
 * @throws ConfigureException when files doesn't exist or when files contain '..' as this could lead to abusive reads.
 */
	public function read($key) {

		if (!class_exists('Spyc')) {
			if (!App::import('Vendor', 'Spyc')) {
				App::import('Vendor', 'YamlReader.Spyc');
			}
		}

		if (strpos($key, '..') !== false) {
			throw new ConfigureException(__('Cannot load configuration files with ../ in them.'));
		}
		list($plugin, $key) = pluginSplit($key);

		$path = $plugin ? App::pluginPath($plugin) . 'config' . DS : $this->_path;
		$file = "$key.$this->ext";
		$filePath = $path . $file;

		if (!file_exists($filePath)) {
			throw new ConfigureException(__('Could not load configuration file: ') . $filePath);
		}

		$config = Spyc::YAMLLoad($filePath);

		if ($this->baseKey) {
			$config = array($key => $config);
		}

		return $config;

	}

}
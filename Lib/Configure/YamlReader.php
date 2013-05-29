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
	public function __construct($path = null, $baseKey = null) {

		if (is_array($path)) {
			extract($path);
		}
		if (empty($path) || is_array($path)) {
			$path = APP . 'Config' . DS;
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
 *   as a plugin prefix. path extension is not needed by default, but possible to specify
 *   your own extension, e.g. Configure::load('Hoge.yaml', new YamlReader);
 * @return array Parsed configuration values.
 * @throws ConfigureException when files doesn't exist or when files contain '..' as this could lead to abusive reads.
 */
	public function read($key) {

		App::uses('Spyc', 'vendors');
		if (!class_exists('Spyc')) {
			App::uses('Spyc', 'YamlReader.vendors');
		}

		$filePath = $this->_getFilePath($key);

		if (!file_exists($filePath)) {
			$filePath .= ".$this->ext";
			if (!file_exists($filePath)) {
				throw new ConfigureException(__('Could not load configuration file: ') . Debugger::trimPath($filePath));
			}
		}

		$config = Spyc::YAMLLoad($filePath);

		if ($this->baseKey) {
			$config = array($key => $config);
		}

		return $config;

	}

/**
 * Get file path
 *
 * @param string $key The identifier to write to. If the key has a . it will be treated
 *  as a plugin prefix.
 * @return string Full file path
 */
	protected function _getFilePath($key) {
		if (strpos($key, '..') !== false) {
			throw new ConfigureException(__('Cannot load configuration files with ../ in them.'));
		}
		list($plugin, $key) = pluginSplit($key);

		$path = $plugin ? App::pluginPath($plugin) . 'Config' . DS : $this->_path;
		$filePath = $path . $key;

		return $filePath;
	}

/**
 * Converts the provided $data into a string of PHP code that can
 * be used saved into a file and loaded later.
 *
 * @param string $key The identifier to write to. If the key has a . it will be treated
 *  as a plugin prefix.
 * @param array $data Data to dump.
 * @return int Bytes saved.
 */
	public function dump($key, $data) {
		$contents = Spyc::YAMLDump($data);

		$filename = $this->_getFilePath($key);
		if (substr($filename, -4) !== ".$this->ext") {
			$filename .= ".$this->ext";
		}
		return file_put_contents($filename, $contents);
	}

}
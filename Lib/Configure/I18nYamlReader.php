<?php

App::uses('YamlReader', 'YamlReader.Configure');

/**
 * I18nYamlReader file
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
 * I18n Yaml Reader is
 *
 */
class I18nYamlReader extends YamlReader {

/**
 * The domain this reader use for i18n.
 *
 * @var mixed null or string domain name
 */
	public $domain = null;

/**
 * Constructor for i18n'ed Yaml.
 *
 * @param mixed $domain null or string domain name
 * @param string $path The path to read config files from.  Defaults to CONFIGS
 * @param string $baseKey If true, assoc key was applied to parsed array with specific key.
 * @throws ConfigureException when Spyc class doesn't exsist or specified path was wrong.
 */
	public function __construct($domain = null, $path = null, $baseKey = null) {

		if (is_array($domain)) {
			extract($domain);
		}

		parent::__construct($path, $baseKey);

		if (isset($domain) && !is_array($domain)) {
			$this->domain = $domain;
		}

	}

/**
 * Returns config applied gettext.
 *
 * @param string $key The identifier to read from.  If the key has a . it will be treated
 *   as a plugin prefix.
 * @return array Parsed configuration values.
 * @throws ConfigureException when files doesn't exist or when files contain '..' as this could lead to abusive reads.
 */
	public function read($key) {

		$config = parent::read($key);

		return $this->applyGettext($config, $this->domain);

	}

/**
 * Returns array applied gettext.
 *
 * @param string $key The identifier to read from.  If the key has a . it will be treated
 *   as a plugin prefix.
 * @return array Parsed configuration values.
 * @throws ConfigureException when files doesn't exist or when files contain '..' as this could lead to abusive reads.
 */
	public function applyGettext($config, $domain = null) {

		if (is_array($config)) {
			foreach ($config as $key => $value) {
				if (is_array($value)) {
					$config[$key] = $this->applyGettext($value, $domain);
				} elseif (is_string($value)) {
					$config[$key] = $domain ? __d($domain, $value) : __($value);
				}
			}
		}

		return $config;

	}

}
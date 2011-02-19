<?php

App::import('Lib', 'YamlReader.I18nYamlReader');

class I18nYamlReaderTestCase extends CakeTestCase {

	protected $_config;

	public function startTest() {

		$this->_config = Configure::read();

		Configure::write('Config.language', 'ja');
		Cache::drop('_cake_core_');
		I18n::clear();
		App::build(array(
			'locales' => array(App::pluginPath('YamlReader') . 'tests' . DS . 'files' . DS . 'locale' . DS),
		), true);

	}

	public function endTest() {

		foreach (Configure::configured() as $config) {
			if (strpos($config, 'I18nYamlTestConfig') !== false) {
				Configure::drop($config);
			}
		}
		I18n::clear();
		App::build();

		foreach (array_keys(Configure::read()) as $key) {
			Configure::delete($key);
		}
		Configure::write($this->_config);

	}

	protected function _configTestFile($domain = null, $path = null, $baseKey = null) {

		if ($path === null) {
			$path = App::pluginPath('YamlReader') . 'tests' . DS . 'files' . DS;
		}

		Configure::config('I18nYamlTestConfig', new I18nYamlReader($domain, $path, $baseKey));

	}

	public function testRead() {

		$this->_configTestFile('yaml_reader');
		Configure::load('i18n', 'I18nYamlTestConfig');

		$expected = 'パスが指定されていないか不正です。';
		$result = Configure::read('i18n.hoge.fuga');
		$this->assertEqual($expected, $result);

		$this->_configTestFile(array('path' => App::pluginPath('YamlReader') . 'tests' . DS . 'files' . DS, 'domain' => 'yaml_reader'));
		Configure::load('i18n', 'I18nYamlTestConfig');

		$expected = 'パスが指定されていないか不正です。';
		$result = Configure::read('i18n.hoge.fuga');
		$this->assertEqual($expected, $result);

	}

	public function testApplyGettext() {

		$Reader = new I18nYamlReader;

		$expected = array(
			'パスが指定されていないか不正です。',
		);
		$result = $Reader->applyGettext(array(
			'The path was not specified or is wrong.',
		), 'yaml_reader');
		$this->assertEqual($expected, $result);

		$expected = array(
			'categoly' => array(
				'パスが指定されていないか不正です。',
			),
		);
		$result = $Reader->applyGettext(array(
			'categoly' => array(
				'The path was not specified or is wrong.',
			),
		), 'yaml_reader');
		$this->assertEqual($expected, $result);


		$expected = array(
			'I18nYamlReaderテストロケール',
		);
		$result = $Reader->applyGettext(array(
			'The I18nYamlReader Test Locale',
		));
		$this->assertEqual($expected, $result);

		$expected = array(
			'categoly' => array(
				'I18nYamlReaderテストロケール',
			),
		);
		$result = $Reader->applyGettext(array(
			'categoly' => array(
				'The I18nYamlReader Test Locale',
			),
		));
		$this->assertEqual($expected, $result);

	}


}
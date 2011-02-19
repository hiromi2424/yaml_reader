<?php

App::import('Lib', 'YamlReader.YamlReader');

class YamlReaderTestCase extends CakeTestCase {

	public function startTest() {

		$this->_config = Configure::read();

	}

	public function endTest() {

		foreach (Configure::configured() as $config) {
			if (strpos($config, 'YamlTestConfig') !== false) {
				Configure::drop($config);
			}
		}

		Configure::write($this->_config);

	}

	protected function _configTestFile($path = null, $baseKey = null) {
		if ($path === null) {
			$path = App::pluginPath('YamlReader') . 'tests' . DS . 'files' . DS;
		}
		Configure::config('YamlTestConfig', new YamlReader($path, $baseKey));
	}

	public function testRead() {
		$this->_configTestFile();
		Configure::load('spyc', 'YamlTestConfig');
		$expected = array(
			array(
				'name' => 'spartan',
				'notes' => array(
					'Needs to be backed up',
					'Needs to be normalized',
				),
				'type' => 'mysql',
			),
		);
		$this->assertIdentical(Configure::read('spyc.databases'), $expected);
		Configure::drop('YamlTestConfig');

		$this->_configTestFile(array('path' => App::pluginPath('YamlReader') . 'tests' . DS . 'files' . DS));
		Configure::load('spyc', 'YamlTestConfig');
		$this->assertIdentical(Configure::read('spyc.databases'), $expected);

		$this->_configTestFile(null, false);
		Configure::load('spyc', 'YamlTestConfig');
		$this->assertIdentical(Configure::read('databases'), $expected);
	}

	public function testErrors() {

		$this->_configTestFile();
		try {
			Configure::load('not_exists', 'YamlTestConfig');
			$this->fail('Exception was not thrown');
		} catch (Exception $e) {
			$this->assertIsA($e, 'ConfigureException');
			$this->assertPattern('|' . __('Could not load configuration file: ') . '|', $e->getMessage());
		}

		try {
			Configure::load('..' . DS . 'test', 'YamlTestConfig');
			$this->fail('Exception was not thrown');
		} catch (Exception $e) {
			$this->assertIsA($e, 'ConfigureException');
			$this->assertIdentical($e->getMessage(), __('Cannot load configuration files with ../ in them.'));
		}
	}
}
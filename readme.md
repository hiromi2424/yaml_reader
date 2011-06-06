# Yaml Reader for CakePHP 2.x

## Installation

in your plugins directory,

	git clone git://github.com/hiromi2424/yaml_reader.git YamlReader

or in current directory of your repository,

	git submodule add git://github.com/hiromi2424/yaml_reader.git plugins/YamlReader

This contains Spyc verstion 0.4.5, but you want to use any version of Spyc, install it to your vendors directory(app/Vendor/Spyc.php).

## Usage


### Create YAML file

Create `App/config/your_config_name.yml` for configure. this ext 'yml' is default, you can change this to set `$ext` property.
or create `plugins/your_plugin/config/your_config_name.yml` if you wanna use this in your plugin.

### Configure

in `App/config/bootstrap.php`:

	App::uses('YamlReader', 'YamlReader.Configure');
	Configure::config('your_config_name', new YamlReader);

or

	App::uses('YamlReader', 'YamlReader.Configure');
	Configure::config('your_config_name', new YamlReader('/your/path/for/config/'));

then,

	Configure::load('config_file_name_without_extension', 'your_config_name');

By default, Config's name is applied to configured array, for example:

	Configure::read('your_config_name'); // when your_config_name.yml is loaded

This can be disabled by arguments of constructor:

	new YamlReader(null, false); // default path is app/Config/
	new YamlReader('/your/path/for/config/', false);
	new YamlReader(array('baseKey' => false));

or changing property:

	$YamlReader = new YamlReader;
	$YamlReader->baseKey = false;

### I18n

If you want to use i18n in each string values, you can use `I18nYamlReader`:

	new I18nYamlReader; // __()
	new I18nYamlReader('your_locale_domain'); // __d()
	new I18nYamlReader('your_locale_domain', null, false); // like YamlReader arguments

	App::uses('I18nYamlReader', 'YamlReader.Configure');
	$I18nYamlReader = new I18nYamlReader;
	$I18nYamlReader->domain = 'your_locale_domain';
	Configure::config('I18n', $I18nYamlReader);

## License

Licensed under The MIT License.
Redistributions of files must retain the above copyright notice.


Copyright 2010 hiromi, https://github.com/hiromi2424

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
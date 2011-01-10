# Yaml Reader for CakePHP 2.x

## Installation

in your plugins directory,

	git clone git://github.com/hiromi2424/yaml_reader.git

or in current directory of your repository,

	git submodule add git://github.com/hiromi2424/yaml_reader.git plugins/yaml_reader

This contains Spyc verstion 0.4.5, but you want to use any version of Spyc, install it to your vendors directory.

## Usage

### in App/config/bootstrap.php:

	App::import('Lib', 'YamlReader.YamlReader');
	Configure::config('your_config_name', new YamlReader);

or

	App::import('Lib', 'YamlReader.YamlReader');
	Configure::config('your_config_name', new YamlReader('/your/path/for/config/'));

then,

	Configure::load('config_file_name_without_extension', 'your_config_name');

by default, Config's name is applied to configured array, for example:

	Configure::read('your_config_name');

this can be disabled by arguments of constructor:

	new YamlReader(null, false);
	new YamlReader(/your/path/for/config/', false);
	new YamlReader(array('path' => '/your/path/for/config/, 'baseKey' => false)); // specifying 'path' is required

or changing property:

	$YamlReader = new YamlReader;
	$YamlReader->baseKey = false;

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
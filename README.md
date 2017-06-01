most-3d-customizer
==================

Extended library of [libre3d/render-3d](https://github.com/libre3d/render-3d) by [Jonathan Foote](https://github.com/jonyo) to help interpret SCAD file, gnerate adjustable parameter inputs, and save to a new SCAD file.

For more details, please see [Coming soon!]()

Requirements
============

Since most-3-d-customizer is an extended library of [libre3d/render-3d](https://github.com/libre3d/render-3d), so the requirements are the same as the original library.

  * For **Open SCAD** files:  Requires [Open SCAD](http://www.openscad.org/)
  * For the actual rendering, requires [POV Ray](http://www.povray.org/)
  * [Composer](https://getcomposer.org/)

Installation
============

For already Composer user, add `"libre3d/render-3d": "~1.2.0"` to the `require` section, and then run `composer update`.

For new Composer user, clone or download this repository and download [Composer](https://getcomposer.org/). Then run `php composer.phar install` from the root folder of this library to install all dependencies. For more information click [here](https://getcomposer.org/doc/00-intro.md).

Usage
=====

The usage of the library is demostrated in PHP file called `index.php` in the root folder.

To include the composer vendor autoload PHP file, a line of PHP code need to be added as:

```php
require $_SERVER['DOCUMENT_ROOT'].'most-3d-customizer/vendor/autoload.php';
```

The SCAD file name is passing as parameter in URL.
Then the Customizer object need to be created and working folder, command and path for OpenSCAD and POV-Ray need to be set.
This is an example of the usage.

```php
$scadfile = $_SERVER['DOCUMENT_ROOT'].'/most-3d-customizer/start/'.$_GET['scadfile'];

$customizer = new \Libre3d\Render3d\Customizer();

// this is the working directory, where it will put any files used during the render process, as well as the final
// rendered image.
$customizer->workingDir('/path/to/working/folder/');

// Set paths to the executables on this system
$customizer->executable('openscad', '/path/to/openscad');
$customizer->executable('povray', '/path/to/povray');

try {
	// This will copy in your starting file into the working DIR if you give the full path to the starting file.
	// This will also set the fileType for you.
	$customizer->filename('/path/to/starting/example.scad');

	// Render!  This will do all the necessary conversions as long as the render engine (in this
	// case, the default engine, PovRAY) "knows" how to convert the file into a file it can use for rendering.
	// Note that this is a multi-step process that can be further broken down if you need it to.
	$renderedImagePath = $customizer->render('povray');

	echo "Render successful!  Rendered image will be at $renderedImagePath";
} catch (\Exception $e) {
	echo "Render failed :( Exception: ".$e->getMessage();
}

$result = $customizer->readSCAD($scadfile);

$rows = count($result);

for($i=0;$i<$rows;$i++) {
	//@ 0:description, 1:variable name, 2: value, 3:possible value
	$element = $render3d->generateElement($result[$i],$i);
	echo $element;
}

//@ for saving a new SCAD file
$newscadname = $render3d->writeSCAD($scadfile, $result);
```

The main workflow:
==================

  * Read SCAD file and extract adjustable parameters.
  * Generate HTML element input for each parameter.
  * Save adjusted parameters to a new SCAD file.

Parameter format
================

  * Textbox
    * variable_name = value;
  * Dropdown box
    * Numbers
      * variable_name = value; // [0,1,2,3,4]
    * Text
      * variable_name = value; // [yes,no]
    * Labeled value
      * variable_name = value; // [10:S,20:M,30:L]
  * Slider or range
    * max only (min is zero)
      * variable_name = value; // [40]
    * min and max
      * variable_name = value; // [1:10]
    * min, step, max
      * variable_name = value; // [1:0.5:10]

Credit
======

Thank you, [Jonathan Foote](https://github.com/jonyo) for sharing [libre3d/render-3d](https://github.com/libre3d/render-3d).


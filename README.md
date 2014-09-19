## API Documentation

Check out [TreeHelper API Documentation](http://imsamurai.github.io/CakePHP-TreeHelper/docs/master/)

## Abstract

[![Build Status](https://travis-ci.org/imsamurai/CakePHP-TreeHelper.png)](https://travis-ci.org/imsamurai/CakePHP-TreeHelper) [![Coverage Status](https://coveralls.io/repos/imsamurai/CakePHP-TreeHelper/badge.png?branch=master)](https://coveralls.io/r/imsamurai/CakePHP-TreeHelper?branch=master) [![Latest Stable Version](https://poser.pugx.org/imsamurai/tree-helper/v/stable.png)](https://packagist.org/packages/imsamurai/tree-helper) [![Total Downloads](https://poser.pugx.org/imsamurai/tree-helper/downloads.png)](https://packagist.org/packages/imsamurai/tree-helper) [![Latest Unstable Version](https://poser.pugx.org/imsamurai/tree-helper/v/unstable.png)](https://packagist.org/packages/imsamurai/tree-helper) [![License](https://poser.pugx.org/imsamurai/tree-helper/license.png)](https://packagist.org/packages/imsamurai/tree-helper)


Helper for building javascript tree from tree data

## Installation

	cd my_cake_app/app
	git clone git://github.com/imsamurai/CakePHP-TreeHelper.git Plugin/TreeHelper

or if you use git add as submodule:

	cd my_cake_app
	git submodule add "git://github.com/imsamurai/CakePHP-TreeHelper.git" "app/Plugin/TreeHelper"

then add plugin loading in Config/bootstrap.php

	CakePlugin::load('TreeHelper');

## Usage

Add helper to controller

	$helpers = array('TreeHelper.Tree');

Build tree

	echo $this->Tree->jqueryTree($data, $options);
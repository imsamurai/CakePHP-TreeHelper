CakePHP-TreeHelper
==================

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
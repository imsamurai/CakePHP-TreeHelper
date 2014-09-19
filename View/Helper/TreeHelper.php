<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 28.09.2012
 * Time: 11:32:39
 * Format: http://book.cakephp.org/2.0/en/views/helpers.html
 */
App::uses('AppHelper', 'View/Helper');

/**
 * Helper for building tree
 * 
 * @property HtmlHelper $Html Html helper
 * @property FormHelper $Form Form helper
 * 
 * @package TreeHelper
 * @subpackage View.Helper
 */
class TreeHelper extends AppHelper {

	/**
	 * Used helpers
	 *
	 * @var array
	 */
	public $helpers = array('Html', 'Form');

	/**
	 * Build tree in jquery-tree plugin format
	 *
	 * @param mixed $data Data for building tree
	 * @param array $options Options, can be:
	 *   - getName string|callable Function for getting string representation of node
	 *   - getChildrens string|callable Function for getting childrens of node
	 *   - expandTo int All nodes from 1 to $expandTo will be expanded initially
	 *   - inline bool Should be scripts and styles inline or not
	 * @return string Html tree with scripts and styles (if inline)
	 */
	public function build($data, array $options = array()) {
		$_options = $options + array(
			'getName' => 'name',
			'getChildrens' => 'childrens',
			'expandTo' => 1,
			'inline' => false
		);
		//строим Html дерево
		$tree = $this->_build(
				$data, $this->_toCallable($_options['getName']), $this->_toCallable($_options['getChildrens']), $_options['expandTo']
		);
		//возвращаем дерево и скрипты/стили
		return $tree . $this->_buildAssets((bool)$_options['inline']);
	}

	/**
	 * Build scripts and styles
	 * 
	 * @param bool $inline
	 * @return string|void
	 */
	protected function _buildAssets($inline) {
		$scripts = array('/tree_helper/vendor/jquery-tree/jQuery.Tree.custom.js');
		$styles = array('/tree_helper/vendor/jquery-tree/css/jQuery.Tree.custom.css');
		$assets = $this->Html->script($scripts, compact('inline'));
		$cake2x4 = version_compare(Configure::version(), 2.4, '>=');
		if ($cake2x4) {
			$assets .= $this->Html->css($styles, compact('inline'));
		} else {
			$assets .= $this->Html->css($styles, null, compact('inline'));
		}
		$assets .= $this->Html->scriptBlock('$(".jq-tree").Tree();', array('safe' => false, 'inline' => $inline));
		return $assets;
	}

	/**
	 * Compiles callable from string (Hash path)
	 * 
	 * @param callable|string $callback
	 * @return callable
	 * @throws InvalidArgumentException If callback is not callable nor string
	 */
	protected function _toCallable($callback) {
		if (is_callable($callback)) {
			return $callback;
		}
		if (!is_string($callback)) {
			throw new InvalidArgumentException('Callback must be callable or string (Hash path)');
		}
		return function($item) use($callback) {
			if ((is_array($item) || $item instanceof ArrayAccess) && isset($item[$callback])) {
				return $item[$callback];
			} elseif (is_object($item) && isset($item->{$callback})) {
				return $item->{$callback};
			} elseif (is_object($item) && method_exists($item, $callback)) {
				return $item->{$callback}();
			} else {
				return 'UNKNOWN';
			}
		};
	}

	/**
	 * Recursively build tree in jquery-tree plugin format
	 *
	 * @param mixed $data Node childrens data
	 * @param callable|string $getName Must return string node title from initial node data
	 * @param callable|string $getChildrens Must return children nodes or null
	 * @param int $expandTo All nodes from 1 to $expandTo will be expanded initially
	 * @param int $level Current node nesting level
	 * @param string $prefix Numeric dot-separated path from root to current node withoun current node number
	 * @return string Html tree or empty string
	 */
	protected function _build($data, callable $getName, callable $getChildrens, $expandTo, $level = 0, $prefix = '') {
		//инициализируем дерево
		$tree = '';
		//если данные узлов пусты
		if (!$data) {
			//возвращаем пустое дерево
			return $tree;
		}
		//инициализируем номер текущего узла на данном уровне
		$number = 1;
		//для каждого узла
		foreach ($data as $dataPart) {
			//инициализируем абсолютный путь к узлу от корня дерева
			$_prefix = $prefix . $number . '.';
			//инициализируем данные узла
			$item = $_prefix . ' ' . $getName($dataPart, $level);
			$item = $this->Form->label(null, $item, array('for' => ''));
			//добавляем к узлу дерево его потомков
			$item .= $this->_build($getChildrens($dataPart, $level), $getName, $getChildrens, $expandTo, $level + 1, $_prefix);
			//определяем должен ли узел быть развернутым
			$expanded = ($level < $expandTo) ? 'true' : null;
			//добавляем узел в дерево, задаем развернут ли узел
			$tree .= $this->Html->tag('li', $item, array('data-expanded' => $expanded));
			$number++;
		}
		//возвращаем дерево
		return empty($tree) ? '' : $this->Html->tag('ul', $tree, array('class' => ($level === 0) ? 'jq-tree' : null));
	}

}

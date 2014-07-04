<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 04.07.2014
 * Time: 16:01:02
 */
App::uses('ArrayObjectA', 'ArrayObjectA.Utility');
App::uses('TreeCollectionNode', 'TreeHelper.Lib/Tree');

/**
 * TreeCollection
 * 
 * @package TreeHelper
 * @subpackage Lib.Tree
 */
class TreeCollection implements IteratorAggregate, Countable, JsonSerializable {

	/**
	 * Node class name
	 */
	const NODE_CLASS = 'TreeCollectionNode';

	/**
	 * TreeCollection nodes
	 *
	 * @var ArrayObjectA 
	 */
	protected $_Nodes = null;

	/**
	 * Constructor
	 * 
	 * @param array|Iterator $Elements
	 */
	public function __construct($Elements = array()) {
		$this->_Nodes = new ArrayObjectA(array());
		$nodeClass = static::NODE_CLASS;
		foreach ($Elements as $Element) {
			$this->add(new $nodeClass($Element));
		}
	}

	/**
	 * Add node
	 * 
	 * @param TreeCollectionNode $Node
	 * @return TreeCollection
	 */
	public function add(TreeCollectionNode $Node) {
		$this->_Nodes[] = $Node;
		return $this;
	}

	/**
	 * Returns tree nodes
	 * 
	 * @return ArrayObjectA 
	 */
	public function getNodes() {
		return $this->_Nodes;
	}

	/**
	 * Iterator
	 * 
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return $this->_Nodes->getIterator();
	}

	/**
	 * Count
	 * 
	 * @return int
	 */
	public function count() {
		return $this->_Nodes->count();
	}

	/**
	 * Json serialization
	 * 
	 * @return ArrayObjectA
	 */
	public function jsonSerialize() {
		return $this->_Nodes;
	}

}

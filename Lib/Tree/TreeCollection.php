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
		$this->flushNodes();
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
	 * Set nodes
	 * 
	 * @param array|iterator $Nodes
	 * 
	 * @return TreeCollection 
	 */
	public function setNodes($Nodes) {
		$this->flushNodes();
		foreach ($Nodes as $Node) {
			$this->add($Node);
		}
		return $this;
	}
	
	/**
	 * Removes all nodes
	 * 
	 * @param array|iterator $Nodes
	 * 
	 * @return TreeCollection 
	 */
	public function flushNodes() {
		$this->_Nodes = new ArrayObjectA(array());
		return $this;
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
	
	/**
	 * Apply filter to current nodes
	 * 
	 * @param callable $callback
	 * @param bool $resetKeys
	 * @return TreeCollection
	 */
	public function filter(callable $callback, $resetKeys = true) {
		return (new static)
				->setNodes($this
						->getNodes()
						->filter($callback, $resetKeys)
						);
	}
	
	/**
	 * Apply filter to current nodes and all children nodes
	 * 
	 * @param callable $callback
	 * @param bool $resetKeys
	 * @return TreeCollection
	 */
	public function filterRecursive(callable $callback, $resetKeys = true) {
		$Tree = $this->filter($callback, $resetKeys);
		foreach ($Tree as $Node) {
			$Node->setChildrens($Node->getChildrens()->filterRecursive($callback, $resetKeys));
		}
		return $Tree;
	}
	
	/**
	 * Returns true if current tree is equals to $Tree
	 * 
	 * @param TreeCollection $Tree
	 * @return bool
	 */
	public function isEquals(TreeCollection $Tree) {
		return json_encode($this) === json_encode($Tree);
	}

}

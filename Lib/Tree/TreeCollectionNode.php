<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 04.07.2014
 * Time: 16:02:04
 */
App::uses('TreeCollection', 'TreeHelper.Lib/Tree');

/**
 * TreeCollection node
 * 
 * @package TreeHelper
 * @subpackage Tree
 */
class TreeCollectionNode implements JsonSerializable {

	/**
	 * Parent node
	 *
	 * @var TreeCollectionNode 
	 */
	protected $_Parent;

	/**
	 * Children tree
	 *
	 * @var TreeCollection 
	 */
	protected $_Childrens;

	/**
	 * Element
	 *
	 * @var mixed 
	 */
	protected $_Element;

	/**
	 * Parent get
	 * 
	 * @return TreeCollectionNode
	 */
	public function getParent() {
		return $this->_Parent;
	}

	/**
	 * Constructor
	 * 
	 * @param mixed $Element
	 * @param TreeCollection $Childrens
	 * @param TreeCollectionNode $Parent
	 */
	public function __construct($Element, TreeCollection $Childrens = null, TreeCollectionNode $Parent = null) {
		$this->setElement($Element);
		$this->setChildrens($Childrens);
		$this->setParent($Parent);
	}

	/**
	 * Parent set
	 * 
	 * @param TreeCollectionNode $Parent
	 * @return TreeCollectionNode
	 */
	public function setParent(TreeCollectionNode $Parent = null) {
		$this->_Parent = $Parent;
		return $this;
	}

	/**
	 * Check if current node has parent
	 * 
	 * @return bool
	 */
	public function hasParent() {
		return !is_null($this->getParent());
	}

	/**
	 * Childrens get
	 * 
	 * @return TreeCollection
	 */
	public function getChildrens() {
		return $this->_Childrens;
	}

	/**
	 * Childrens set
	 * 
	 * @param TreeCollection $Childrens
	 * @return TreeCollectionNode
	 */
	public function setChildrens(TreeCollection $Childrens = null) {
		$this->_Childrens = $Childrens ? $Childrens : new TreeCollection();
		foreach ($this->_Childrens as $Children) {
			$Children->setParent($this);
		}
		return $this;
	}

	/**
	 * Check if current node has childrens
	 * 
	 * @return bool
	 */
	public function hasChildrens() {
		return (bool)$this->getChildrens()->count();
	}

	/**
	 * Add children
	 * 
	 * @param TreeCollectionNode $Children
	 * @return TreeCollectionNode
	 */
	public function addChildren(TreeCollectionNode $Children) {
		$this->getChildrens()->add($Children->setParent($this));
		return $this;
	}
	
	/**
	 * Remove children
	 * 
	 * @param TreeCollectionNode $Children
	 * @return TreeCollectionNode
	 */
	public function removeChildren(TreeCollectionNode $Children) {
		$this->getChildrens()->remove($Children);
		return $this;
	}

	/**
	 * Element get
	 * 
	 * @return mixed
	 */
	public function getElement() {
		return $this->_Element;
	}

	/**
	 * Element set
	 * 
	 * @param mixed $Element
	 * @return TreeCollection
	 */
	public function setElement($Element) {
		$this->_Element = $Element;
		return $this;
	}

	/**
	 * Json serialization
	 * 
	 * @return array
	 */
	public function jsonSerialize() {
		return array(
			'Element' => $this->getElement(),
			'Parent' => $this->hasParent() ? $this->getParent()->getElement() : null,
			'Childrens' => $this->getChildrens()
		);
	}
	
	/**
	 * Returns true if current node is equals to $Node
	 * 
	 * @param TreeCollectionNode $Node
	 * @return bool
	 */
	public function isEquals(TreeCollectionNode $Node) {
		return json_encode($this) === json_encode($Node);
	}

}

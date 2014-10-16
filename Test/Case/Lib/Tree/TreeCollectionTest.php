<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 04.07.2014
 * Time: 19:45:32
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */
App::uses('TreeCollectionNode', 'TreeHelper.Lib/Tree');
App::uses('TreeCollection', 'TreeHelper.Lib/Tree');

/**
 * TreeCollectionTest
 * 
 * @package TreeHelperTest
 * @subpackage Tree
 */
class TreeCollectionTest extends CakeTestCase {

	/**
	 * {@inheritdoc}
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * Test create tree
	 * 
	 * @param array $Elements
	 * @dataProvider elementsProvider
	 */
	public function testCreate(array $Elements) {
		$Tree = new TreeCollection($Elements);
		$this->assertCount(count($Elements), $Tree);
		$this->assertCount(count($Elements), $Tree->getNodes());
		$this->assertInstanceOf('ArrayObjectA', $Tree->getNodes());
		foreach ($Tree as $Node) {
			$this->assertInstanceOf(TreeCollection::NODE_CLASS, $Node);
		}
	}

	/**
	 * Test json serialize
	 * 
	 * @param TreeCollection $Tree
	 * @param array $output
	 * 
	 * @dataProvider jsonSerializeProvider
	 */
	public function testJsonSerialize(TreeCollection $Tree, array $output) {
		$this->assertSame(json_encode($output, JSON_PRETTY_PRINT), json_encode($Tree, JSON_PRETTY_PRINT));
	}

	/**
	 * Test set nodes
	 * 
	 * @param array $Nodes
	 * @param array $initialElements
	 * 
	 * @dataProvider setNodesProvider
	 */
	public function testSetNodes(array $Nodes, array $initialElements) {
		$Tree = new TreeCollection($initialElements);
		$Tree->setNodes($Nodes);
		$this->assertCount(count($Nodes), $Tree);
		$this->assertCount(count($Nodes), $Tree->getNodes());
		$this->assertInstanceOf('ArrayObjectA', $Tree->getNodes());
		foreach ($Tree as $Node) {
			$this->assertInstanceOf(TreeCollection::NODE_CLASS, $Node);
		}
	}

	/**
	 * Test equality
	 * 
	 * @param TreeCollection $Tree1
	 * @param TreeCollection $Tree2
	 * @param bool $equals
	 * 
	 * @dataProvider equalsProvider
	 */
	public function testEquals(TreeCollection $Tree1, TreeCollection $Tree2, $equals) {
		$this->assertSame($equals, $Tree1->isEquals($Tree2));
		$this->assertSame($equals, $Tree2->isEquals($Tree1));
	}

	/**
	 * Test nodes filter
	 * 
	 * @param callable $filter
	 * @param TreeCollection $Tree
	 * @param TreeCollection $OutputTree
	 * 
	 * @dataProvider filterProvider
	 */
	public function testFilter(callable $filter, TreeCollection $Tree, TreeCollection $OutputTree) {
		$this->assertTrue($OutputTree->isEquals($Tree->filter($filter)));
	}

	/**
	 * Test nodes recursive filter
	 * 
	 * @param callable $filter
	 * @param TreeCollection $Tree
	 * @param TreeCollection $OutputTree
	 * 
	 * @dataProvider filterRecursiveProvider
	 */
	public function testFilterRecursive(callable $filter, TreeCollection $Tree, TreeCollection $OutputTree) {
		$this->assertTrue($OutputTree->isEquals($Tree->filterRecursive($filter)));
	}

	/**
	 * Test nodes recursive reverse filter
	 * 
	 * @param callable $filter
	 * @param TreeCollection $Tree
	 * @param TreeCollection $OutputTree
	 * 
	 * @dataProvider filterRecursiveReverseProvider
	 */
	public function testFilterRecursiveReverse(callable $filter, TreeCollection $Tree, TreeCollection $OutputTree) {
		$this->assertTrue($OutputTree->isEquals($Tree->filterRecursiveReverse($filter)));
	}

	/**
	 * Test remove node from tree
	 * 
	 * @param TreeCollection $Tree
	 * @param TreeCollectionNode $Node
	 * @param TreeCollection $OutputTree
	 * 
	 * @dataProvider removeProvider
	 */
	public function testRemove(TreeCollection $Tree, TreeCollectionNode $Node, TreeCollection $OutputTree) {
		$this->assertTrue($Tree->remove($Node)->isEquals($OutputTree));
	}

	/**
	 * Test nodes multisort
	 * 
	 * @param string|array $params
	 * @param TreeCollection $Tree
	 * @param TreeCollection $OutputTree
	 * 
	 * @dataProvider multisortProvider
	 */
	public function testMultisort($params, TreeCollection $Tree, TreeCollection $OutputTree) {
		$this->assertTrue($OutputTree->isEquals($Tree->multisort($params)));
	}

	/**
	 * Test nodes recursive filter
	 * 
	 * @param string|array $params
	 * @param TreeCollection $Tree
	 * @param TreeCollection $OutputTree
	 * 
	 * @dataProvider multisortRecursiveProvider
	 */
	public function testMultisortRecursive($params, TreeCollection $Tree, TreeCollection $OutputTree) {
		$this->assertTrue($OutputTree->isEquals($Tree->multisortRecursive($params)));
	}

	/**
	 * Data provider for testCreate
	 * 
	 * @return array
	 */
	public function elementsProvider() {
		return array(
			//set #0
			array(
				//Elements
				array()
			),
			//set #1
			array(
				//Elements
				array(
					1, 2, 3, 4
				)
			)
		);
	}

	/**
	 * Data provider for testJsonSerialize
	 * 
	 * @return array
	 */
	public function jsonSerializeProvider() {
		return array(
			//set #0
			array(
				//Tree
				new TreeCollection(array()),
				//output
				array()
			),
			//set #1
			array(
				//Tree
				new TreeCollection(array(1, 2, 3, 4)),
				//output
				array(
					array(
						'Element' => 1,
						'Parent' => null,
						'Childrens' => array()
					),
					array(
						'Element' => 2,
						'Parent' => null,
						'Childrens' => array()
					),
					array(
						'Element' => 3,
						'Parent' => null,
						'Childrens' => array()
					),
					array(
						'Element' => 4,
						'Parent' => null,
						'Childrens' => array()
					),
				)
			)
		);
	}

	/**
	 * Data provider for testSetNodes
	 * 
	 * @return array
	 */
	public function setNodesProvider() {
		return array(
			//set #0
			array(
				//Nodes
				array(
					new TreeCollectionNode(1),
					new TreeCollectionNode(2),
					new TreeCollectionNode(3),
				),
				//initialElements
				array()
			),
			//set #1
			array(
				//Nodes
				array(
					new TreeCollectionNode(1),
					new TreeCollectionNode(2),
					new TreeCollectionNode(3),
				),
				//initialElements
				array(6, 7, 8, 9, 0)
			),
			//set #2
			array(
				//Nodes
				array(),
				//initialElements
				array(6, 7, 8, 9, 0)
			),
			//set #3
			array(
				//Nodes
				array(),
				//initialElements
				array()
			),
		);
	}

	/**
	 * Data provider for testEquals
	 * 
	 * @return array
	 */
	public function equalsProvider() {
		$ComplexTree1 = new TreeCollection;
		$Node1 = new TreeCollectionNode(1);
		$Node2 = new TreeCollectionNode(2);
		$Node3 = new TreeCollectionNode(3);
		$Node4 = new TreeCollectionNode(4);
		$Node3->addChildren($Node4);
		$Node1->addChildren($Node2);
		$Node1->addChildren($Node3);
		$ComplexTree1->add($Node1);

		$ComplexTree2 = new TreeCollection;
		$Node11 = new TreeCollectionNode(1);
		$Node22 = new TreeCollectionNode(2);
		$Node33 = new TreeCollectionNode(3);
		$Node44 = new TreeCollectionNode(4);
		$Node33->addChildren($Node44);
		$Node11->addChildren($Node22);
		$Node11->addChildren($Node33);
		$ComplexTree2->add($Node11);

		$ComplexTree3 = new TreeCollection(array(1, 2, 3, 4));

		return array(
			//set #0
			array(
				//Tree1
				new TreeCollection,
				//Tree2
				new TreeCollection,
				//equals
				true
			),
			//set #1
			array(
				//Tree1
				new TreeCollection(array(1, 2, 3, 4)),
				//Tree2
				new TreeCollection(array(1, 2, 3, 4)),
				//equals
				true
			),
			//set #2
			array(
				//Tree1
				new TreeCollection(),
				//Tree2
				new TreeCollection(array(1, 2, 3, 4)),
				//equals
				false
			),
			//set #3
			array(
				//Tree1
				new TreeCollection(array(1, 2, 3)),
				//Tree2
				new TreeCollection(array(1, 2, 3, 4)),
				//equals
				false
			),
			//set #4
			array(
				//Tree1
				new TreeCollection(array(6)),
				//Tree2
				new TreeCollection(array(7)),
				//equals
				false
			),
			array(
				//Tree1
				$ComplexTree1,
				//Tree2
				$ComplexTree2,
				//equals
				true
			),
			array(
				//Tree1
				$ComplexTree1,
				//Tree2
				$ComplexTree3,
				//equals
				false
			),
		);
	}

	/**
	 * Data provider for testFilter
	 * 
	 * @return array
	 */
	public function filterProvider() {
		$filter1 = function(TreeCollectionNode $Node) {
			return $Node->getElement() === 1;
		};

		$filter2 = function(TreeCollectionNode $Node) {
			return $Node->getElement() !== 1;
		};

		$ComplexTree1 = function() {
			$Node1 = new TreeCollectionNode(1);
			$Node2 = new TreeCollectionNode(2);
			$Node3 = new TreeCollectionNode(3);
			$Node4 = new TreeCollectionNode(4);
			$Node3->addChildren($Node4);
			$Node1->addChildren($Node2);
			$Node1->addChildren($Node3);
			return (new TreeCollection)->add($Node1);
		};

		$OutputComplexTree1filter1 = function() {
			$Node1 = new TreeCollectionNode(1);
			$Node2 = new TreeCollectionNode(2);
			$Node3 = new TreeCollectionNode(3);
			$Node4 = new TreeCollectionNode(4);
			$Node3->addChildren($Node4);
			$Node1->addChildren($Node2);
			$Node1->addChildren($Node3);
			return (new TreeCollection)->add($Node1);
		};

		$OutputComplexTree1filter2 = function() {
			return new TreeCollection;
		};

		return array(
			//set #0
			array(
				//filter
				function() {
					return true;
				},
				//Tree
				new TreeCollection,
				//OutputTree
				new TreeCollection,
			),
			//set #1
			array(
				//filter
				function() {
					return true;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection(array(1, 2, 3)),
			),
			//set #2
			array(
				//filter
				function() {
					return false;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection,
			),
			//set #3
			array(
				//filter
				function(TreeCollectionNode $Node) {
					return $Node->getElement() !== 2;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection(array(1, 3)),
			),
			//set #4
			array(
				//filter
				$filter1,
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1filter1(),
			),
			//set #4
			array(
				//filter
				$filter2,
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1filter2(),
			),
		);
	}

	/**
	 * Data provider for testFilterRecursive
	 * 
	 * @return array
	 */
	public function filterRecursiveProvider() {
		$filter1 = function(TreeCollectionNode $Node) {
			return $Node->getElement() === 1;
		};

		$filter2 = function(TreeCollectionNode $Node) {
			return $Node->getElement() !== 1;
		};

		$filter3 = function(TreeCollectionNode $Node) {
			return !$Node->hasParent() || $Node->getParent()->getElement() <= 1;
		};

		$ComplexTree1 = function() {
			$Node1 = new TreeCollectionNode(1);
			$Node2 = new TreeCollectionNode(2);
			$Node3 = new TreeCollectionNode(3);
			$Node4 = new TreeCollectionNode(4);
			$Node3->addChildren($Node4);
			$Node1->addChildren($Node2);
			$Node1->addChildren($Node3);
			return (new TreeCollection)->add($Node1);
		};

		$OutputComplexTree1filter1 = function() {
			$Node1 = new TreeCollectionNode(1);
			return (new TreeCollection)->add($Node1);
		};

		$OutputComplexTree1filter2 = function() {
			return new TreeCollection;
		};

		$OutputComplexTree1filter3 = function() {
			$Node1 = new TreeCollectionNode(1);
			$Node2 = new TreeCollectionNode(2);
			$Node3 = new TreeCollectionNode(3);
			$Node1->addChildren($Node2);
			$Node1->addChildren($Node3);
			return (new TreeCollection)->add($Node1);
		};

		return array(
			//set #0
			array(
				//filter
				function() {
					return true;
				},
				//Tree
				new TreeCollection,
				//OutputTree
				new TreeCollection,
			),
			//set #1
			array(
				//filter
				function() {
					return true;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection(array(1, 2, 3)),
			),
			//set #2
			array(
				//filter
				function() {
					return false;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection,
			),
			//set #3
			array(
				//filter
				function(TreeCollectionNode $Node) {
					return $Node->getElement() !== 2;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection(array(1, 3)),
			),
			//set #4
			array(
				//filter
				$filter1,
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1filter1(),
			),
			//set #5
			array(
				//filter
				$filter2,
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1filter2(),
			),
			//set #6
			array(
				//filter
				$filter3,
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1filter3(),
			),
		);
	}

	/**
	 * Data provider for testRemove
	 * 
	 * @return array
	 */
	public function removeProvider() {
		return array(
			//set #0
			array(
				//Tree
				new TreeCollection(array(1, 2, 3, 4)),
				//Node
				new TreeCollectionNode(2),
				//OutputTree
				new TreeCollection(array(1, 3, 4)),
			),
			//set #1
			array(
				//Tree
				new TreeCollection(array(1, 2, 3, 4)),
				//Node
				new TreeCollectionNode(0),
				//OutputTree
				new TreeCollection(array(1, 2, 3, 4)),
			),
			//set #2
			array(
				//Tree
				new TreeCollection,
				//Node
				new TreeCollectionNode(0),
				//OutputTree
				new TreeCollection,
			),
		);
	}

	/**
	 * Data provider for testFilterRecursiveReverse
	 * 
	 * @return array
	 */
	public function filterRecursiveReverseProvider() {
		$filter1 = function(TreeCollectionNode $Node) {
			return $Node->getElement() === 1;
		};

		$filter2 = function(TreeCollectionNode $Node) {
			return $Node->getElement() !== 1;
		};

		$filter3 = function(TreeCollectionNode $Node) {
			if ($Node->getElement() == 4) {
				return false;
			} else {
				return $Node->hasChildrens();
			}
		};

		$ComplexTree1 = function() {
			$Node1 = new TreeCollectionNode(1);
			$Node2 = new TreeCollectionNode(2);
			$Node3 = new TreeCollectionNode(3);
			$Node4 = new TreeCollectionNode(4);
			$Node3->addChildren($Node4);
			$Node1->addChildren($Node2);
			$Node1->addChildren($Node3);
			return (new TreeCollection)->add($Node1);
		};

		$OutputComplexTree1filter1 = function() {
			$Node1 = new TreeCollectionNode(1);
			return (new TreeCollection)->add($Node1);
		};

		$OutputComplexTree1filter2 = function() {
			return new TreeCollection;
		};

		$OutputComplexTree1filter3 = function() {
			return new TreeCollection;
		};

		return array(
			//set #0
			array(
				//filter
				function() {
					return true;
				},
				//Tree
				new TreeCollection,
				//OutputTree
				new TreeCollection,
			),
			//set #1
			array(
				//filter
				function() {
					return true;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection(array(1, 2, 3)),
			),
			//set #2
			array(
				//filter
				function() {
					return false;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection,
			),
			//set #3
			array(
				//filter
				function(TreeCollectionNode $Node) {
					return $Node->getElement() !== 2;
				},
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection(array(1, 3)),
			),
			//set #4
			array(
				//filter
				$filter1,
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1filter1(),
			),
			//set #5
			array(
				//filter
				$filter2,
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1filter2(),
			),
			//set #6
			array(
				//filter
				$filter3,
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1filter3(),
			),
		);
	}

	/**
	 * Data provider for testMultisort
	 * 
	 * @return array
	 */
	public function multisortProvider() {
		$ComplexTree1 = function() {
			$Node1 = new TreeCollectionNode(array('weight' => 1));
			$Node11 = new TreeCollectionNode(array('weight' => 11));
			$Node2 = new TreeCollectionNode(array('weight' => 2));
			$Node3 = new TreeCollectionNode(array('weight' => 3));
			$Node4 = new TreeCollectionNode(array('weight' => 5));
			$Node3->addChildren($Node4);
			$Node1->addChildren($Node2);
			$Node1->addChildren($Node3);
			return (new TreeCollection)->add($Node1)->add($Node11);
		};

		$OutputComplexTree1 = function() {
			$Node1 = new TreeCollectionNode(array('weight' => 1));
			$Node11 = new TreeCollectionNode(array('weight' => 11));
			$Node2 = new TreeCollectionNode(array('weight' => 2));
			$Node3 = new TreeCollectionNode(array('weight' => 3));
			$Node4 = new TreeCollectionNode(array('weight' => 5));
			$Node3->addChildren($Node4);
			$Node1->addChildren($Node2);
			$Node1->addChildren($Node3);
			return (new TreeCollection)->add($Node11/* ! */)->add($Node1/* ! */);
		};

		return array(
			//set #0
			array(
				//params
				array(),
				//Tree
				new TreeCollection,
				//OutputTree
				new TreeCollection,
			),
			//set #1
			array(
				//params
				'asc',
				//Tree
				new TreeCollection(array(3, 1, 2)),
				//OutputTree
				new TreeCollection(array(1, 2, 3)),
			),
			//set #2
			array(
				//params
				'desc',
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection(array(3, 2, 1)),
			),
			//set #3
			array(
				//params
				array(array(
						'field' => function($Node) {
							return $Node->getElement()['weight'];
						},
						'order' => 'desc'
					)),
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1(),
			),
		);
	}

	/**
	 * Data provider for testMultisortRecursive
	 * 
	 * @return array
	 */
	public function multisortRecursiveProvider() {
		$ComplexTree1 = function() {
			$Node1 = new TreeCollectionNode(array('weight' => 1));
			$Node11 = new TreeCollectionNode(array('weight' => 11));
			$Node2 = new TreeCollectionNode(array('weight' => 2));
			$Node3 = new TreeCollectionNode(array('weight' => 3));
			$Node4 = new TreeCollectionNode(array('weight' => 5));
			$Node3->addChildren($Node4);
			$Node1->addChildren($Node2);
			$Node1->addChildren($Node3);
			return (new TreeCollection)->add($Node1)->add($Node11);
		};

		$OutputComplexTree1 = function() {
			$Node1 = new TreeCollectionNode(array('weight' => 1));
			$Node11 = new TreeCollectionNode(array('weight' => 11));
			$Node2 = new TreeCollectionNode(array('weight' => 2));
			$Node3 = new TreeCollectionNode(array('weight' => 3));
			$Node4 = new TreeCollectionNode(array('weight' => 5));
			$Node3->addChildren($Node4);
			$Node1->addChildren($Node3/* ! */);
			$Node1->addChildren($Node2/* ! */);
			return (new TreeCollection)->add($Node11/* ! */)->add($Node1/* ! */);
		};

		return array(
			//set #0
			array(
				//params
				array(),
				//Tree
				new TreeCollection,
				//OutputTree
				new TreeCollection,
			),
			//set #1
			array(
				//params
				'asc',
				//Tree
				new TreeCollection(array(3, 1, 2)),
				//OutputTree
				new TreeCollection(array(1, 2, 3)),
			),
			//set #2
			array(
				//params
				'desc',
				//Tree
				new TreeCollection(array(1, 2, 3)),
				//OutputTree
				new TreeCollection(array(3, 2, 1)),
			),
			//set #3
			array(
				//params
				array(array(
						'field' => function($Node) {
							return $Node->getElement()['weight'];
						},
						'order' => 'desc'
					)),
				//Tree
				$ComplexTree1(),
				//OutputTree
				$OutputComplexTree1(),
			),
		);
	}

}

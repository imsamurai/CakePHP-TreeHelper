<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 04.07.2014
 * Time: 19:04:09
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */
App::uses('TreeCollectionNode', 'TreeHelper.Lib/Tree');
App::uses('TreeCollection', 'TreeHelper.Lib/Tree');

/**
 * TreeCollectionNodeTest
 * 
 * @package TreeHelperTest
 * @subpackage Tree
 */
class TreeCollectionNodeTest extends CakeTestCase {

	/**
	 * {@inheritdoc}
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * Test create node
	 * 
	 * @param mixed $Element
	 * @param TreeCollection $Childrens
	 * @param TreeCollectionNode $Parent
	 * 
	 * @dataProvider nodesProvider
	 */
	public function testCreate($Element, TreeCollection $Childrens = null, TreeCollectionNode $Parent = null) {
		$Node = new TreeCollectionNode($Element, $Childrens, $Parent);
		$this->assertSame($Element, $Node->getElement());
		$this->assertSame($Parent, $Node->getParent());
		$this->assertNotNull($Node->getChildrens());
		$this->assertSame(!is_null($Childrens), $Node->hasChildrens());
		$this->assertSame(!is_null($Parent), $Node->hasParent());
	}

	/**
	 * Test node setters
	 * 
	 * @param mixed $Element
	 * @param TreeCollection $Childrens
	 * @param TreeCollectionNode $Parent
	 * 
	 * @dataProvider nodesProvider
	 */
	public function testSet($Element, TreeCollection $Childrens = null, TreeCollectionNode $Parent = null) {
		$Node = new TreeCollectionNode(null);
		$Node->setElement($Element);
		$Node->setChildrens($Childrens);
		$Node->setParent($Parent);
		$this->assertSame($Element, $Node->getElement());
		$this->assertSame($Parent, $Node->getParent());
		$this->assertNotNull($Node->getChildrens());
		$this->assertSame(!is_null($Childrens), $Node->hasChildrens());
		$this->assertSame(!is_null($Parent), $Node->hasParent());
		if ($Node->hasChildrens()) {
			foreach ($Node->getChildrens() as $SubNode) {
				$this->assertSame($Node, $SubNode->getParent());
			}
		}
	}

	/**
	 * Test add childrens
	 * 
	 * @param TreeCollectionNode $Node
	 * @param TreeCollectionNode[] $Childrens
	 * 
	 * @dataProvider childrenProvider
	 */
	public function testAddChildrens(TreeCollectionNode $Node, array $Childrens) {
		$this->assertFalse($Node->hasChildrens());
		foreach ($Childrens as $Children) {
			$this->assertFalse($Children->hasParent());
			$Node->addChildren($Children);
		}
		$this->assertCount(count($Childrens), $Node->getChildrens());
		foreach ($Node->getChildrens() as $SubNode) {
			$this->assertSame($Node, $SubNode->getParent());
		}
	}
	
	/**
	 * Test remove childrens
	 * 
	 * @param TreeCollectionNode $Node
	 * @param TreeCollectionNode[] $Childrens
	 * 
	 * @dataProvider childrenProvider
	 */
	public function testRemoveChildrens(TreeCollectionNode $Node, array $Childrens) {
		foreach ($Childrens as $Children) {
			$this->assertFalse($Node->hasChildrens());
			$Node->addChildren($Children);
			$this->assertTrue($Node->hasChildrens());
			$Node->removeChildren($Children);
			$this->assertFalse($Node->hasChildrens());
		}
	}

	/**
	 * Test json serialize
	 * 
	 * @param TreeCollectionNode $Node
	 * @param array $output
	 * 
	 * @dataProvider jsonSerializeProvider
	 */
	public function testJsonSerialize(TreeCollectionNode $Node, array $output) {
		$this->assertSame(json_encode($output, JSON_PRETTY_PRINT), json_encode($Node, JSON_PRETTY_PRINT));
	}
	
	/**
	 * Test isEquals
	 * 
	 * @param TreeCollectionNode $Node1
	 * @param TreeCollectionNode $Node2
	 * @param bool $equals
	 * 
	 * @dataProvider isEqualsProvider
	 */
	public function testIsEquals(TreeCollectionNode $Node1, TreeCollectionNode $Node2, $equals) {
		$this->assertSame($equals, $Node1->isEquals($Node2));
		$this->assertSame($equals, $Node2->isEquals($Node1));
	}

	/**
	 * Data provider for testCreate, testSet
	 * 
	 * @return array
	 */
	public function nodesProvider() {
		return array(
			//set #0
			array(
				//Element
				1,
				//Childrens
				null,
				//Parent
				null,
			),
			//set #1
			array(
				//Element
				1,
				//Childrens
				null,
				//Parent
				new TreeCollectionNode(5),
			),
			//set #3
			array(
				//Element
				new stdClass(),
				//Childrens
				null,
				//Parent
				new TreeCollectionNode(new stdClass()),
			),
			//set #4
			array(
				//Element
				0,
				//Childrens
				new TreeCollection(array(1, 2, 3)),
				//Parent
				new TreeCollectionNode(-1),
			),
		);
	}

	/**
	 * Data provider for testAddChildrens
	 * 
	 * @return array
	 */
	public function childrenProvider() {
		return array(
			//set #0
			array(
				//Node
				new TreeCollectionNode(1),
				//Childrens
				array(
					new TreeCollectionNode(2)
				)
			),
			//set #1
			array(
				//Node
				new TreeCollectionNode(1),
				//Childrens
				array(
					new TreeCollectionNode(2),
					new TreeCollectionNode(3),
					new TreeCollectionNode(4),
				)
			),
		);
	}

	/**
	 * Data provider for testJsonSerialize
	 * 
	 * @return testJsonSerialize
	 */
	public function jsonSerializeProvider() {
		$Node1 = new TreeCollectionNode('one');
		$Node2 = new TreeCollectionNode('two');
		$Node3 = new TreeCollectionNode('three');
		$Node4 = new TreeCollectionNode('four');

		$Node3->addChildren($Node4);
		$Node1->addChildren($Node2);
		$Node1->addChildren($Node3);

		return array(
			//set #0
			array(
				//Node
				$Node1,
				//output
				array(
					'Element' => 'one',
					'Parent' => null,
					'Childrens' => array(
						array(
							'Element' => 'two',
							'Parent' => 'one',
							'Childrens' => array()
						),
						array(
							'Element' => 'three',
							'Parent' => 'one',
							'Childrens' => array(
								array(
									'Element' => 'four',
									'Parent' => 'three',
									'Childrens' => array()
								)
							)
						),
					)
				)
			)
		);
	}

	/**
	 * Data provider for testIsEquals
	 * 
	 * @return array
	 */
	public function isEqualsProvider() {
		return array(
			//set #0
			array(
				//Node1
				new TreeCollectionNode(1),
				//Node2
				new TreeCollectionNode(1),
				//equals
				true
			),
			//set #1
			array(
				//Node1
				new TreeCollectionNode(2),
				//Node2
				new TreeCollectionNode(1),
				//equals
				false
			),
			//set #2
			array(
				//Node1
				new TreeCollectionNode(1, new TreeCollection(array(2, 3, 4))),
				//Node2
				new TreeCollectionNode(1, new TreeCollection(array(2, 3, 4))),
				//equals
				true
			),
			//set #3
			array(
				//Node1
				new TreeCollectionNode(1, new TreeCollection(array(2, 3, 4))),
				//Node2
				new TreeCollectionNode(1, new TreeCollection(array(2, 3, 4, 5))),
				//equals
				false
			),
			//set #4
			array(
				//Node1
				new TreeCollectionNode(1, new TreeCollection(array(2, 3, 4)), new TreeCollectionNode(0)),
				//Node2
				new TreeCollectionNode(1, new TreeCollection(array(2, 3, 4)), new TreeCollectionNode(0)),
				//equals
				true
			),
			//set #5
			array(
				//Node1
				new TreeCollectionNode(1, new TreeCollection(array(2, 3, 4)), new TreeCollectionNode(0)),
				//Node2
				new TreeCollectionNode(1, new TreeCollection(array(2, 3, 4)), new TreeCollectionNode(-1)),
				//equals
				false
			),
		);
	}
}

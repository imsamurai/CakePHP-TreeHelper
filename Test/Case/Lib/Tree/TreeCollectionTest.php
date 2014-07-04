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

}

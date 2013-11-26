<?

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 28.09.2012
 * Time: 12:09:31
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('TreeHelper', 'TreeHelper.View/Helper');

/**
 * Test Tree helper
 * 
 * @property TreeHelper $TreeHelper
 * 
 * @package TreeHelperTest
 * @subpackage TreeHelper
 */
class TreeHelperTest extends CakeTestCase {

	/**
	 * {@inheritdoc}
	 */
	public function setUp() {
		parent::setUp();
		$this->TreeHelper = new TreeHelper(new View(new Controller()));
	}

	/**
	 * Test build tree with paths instead of callbacks
	 * 
	 * @param mixed $data
	 * @param string $template
	 * @param array $options
	 * @param array $result
	 * 
	 * @dataProvider treeProvider
	 */
	public function testBuild($data, $template, $options, $result) {
		$tree = $this->TreeHelper->build($data, $options);
		$expected = vsprintf($template, $result);
		if (empty($options['inline'])) {
			$this->assertSame($expected, $tree);
		} else {
			$this->assertContains($expected, $tree);
		}
	}

	/**
	 * Data provider testBuild
	 * 
	 * @return array
	 */
	public function treeProvider() {
		$data = array(
			array(
				'name' => 'item1',
				'details' => array(
					'item1_detail1',
					'item1_detail2',
					'item1_detail3'
				),
				'childrens' => array(
					array(
						'name' => 'item3',
						'details' => array(
							'item3_detail1',
							'item3_detail2',
							'item3_detail3'
						),
						'childrens' => array()
					),
					array(
						'name' => 'item4',
						'details' => array(
							'item4_detail1',
							'item4_detail2',
							'item4_detail3'
						),
						'childrens' => array(
							array(
								'name' => 'item5',
								'details' => array(
									'item5_detail1',
									'item5_detail2',
									'item5_detail3'
								),
								'childrens' => array()
							)
						)
					)
				)
			),
			array(
				'name' => 'item2',
				'details' => array(
					'item2_detail1',
					'item2_detail2',
					'item2_detail3'
				),
				'childrens' => array()
			)
		);

		$template = '<ul class="chains-tree"><li data-expanded="true"><label for="">1. %s</label><ul><li><label for="">1.1. %s</label></li><li><label for="">1.2. %s</label><ul><li><label for="">1.2.1. %s</label></li></ul></li></ul></li><li data-expanded="true"><label for="">2. %s</label></li></ul>';

		return array(
			//data, template, options, result
			//set #0
			array($data, $template, array(), array('item1', 'item3', 'item4', 'item5', 'item2')),
			//set #1
			array($data, $template, array(
					'getName' => function($dataPart) {
				return implode('|', $dataPart['details']);
			},
					'getChildrens' => function($dataPart) {
				return $dataPart['childrens'];
			}
				), array('item1_detail1|item1_detail2|item1_detail3', 'item3_detail1|item3_detail2|item3_detail3', 'item4_detail1|item4_detail2|item4_detail3', 'item5_detail1|item5_detail2|item5_detail3', 'item2_detail1|item2_detail2|item2_detail3')
			),
			//set #2
			array($data, $template, array('inline' => true), array('item1', 'item3', 'item4', 'item5', 'item2')),
		);
	}

}

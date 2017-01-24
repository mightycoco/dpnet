<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DatasourceTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DatasourceTable Test Case
 */
class DatasourceTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DatasourceTable
     */
    public $Datasource;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.datasource'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Datasource') ? [] : ['className' => 'App\Model\Table\DatasourceTable'];
        $this->Datasource = TableRegistry::get('Datasource', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Datasource);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

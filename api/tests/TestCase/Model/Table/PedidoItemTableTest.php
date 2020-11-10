<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PedidoItemTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PedidoItemTable Test Case
 */
class PedidoItemTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PedidoItemTable
     */
    protected $PedidoItem;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.PedidoItem',
        'app.Pedidos',
        'app.Produtos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PedidoItem') ? [] : ['className' => PedidoItemTable::class];
        $this->PedidoItem = $this->getTableLocator()->get('PedidoItem', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PedidoItem);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

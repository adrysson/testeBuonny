<?php

declare(strict_types=1);

namespace App\Controller\V1\Pedidos;

use App\Controller\AppController;

/**
 * PedidoItem Controller
 *
 * @property \App\Model\Table\PedidoItemTable $PedidoItem
 * @method \App\Model\Entity\PedidoItem[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PedidoItemController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $itens = $this->PedidoItem->find('all', [
            'contain' => ['Pedido', 'Produto'],
            'conditions' => [
                'pedido_id' => $this->getRequest()->getParam('pedido_id'),
            ],
        ]);

        $this->set(compact('itens'));
        $this->viewBuilder()->setOption('serialize', ['itens']);
    }
}

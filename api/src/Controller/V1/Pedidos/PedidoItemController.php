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

    /**
     * View method
     *
     * @param string|null $id Pedido Item id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pedidoItem = $this->PedidoItem->get($id, [
            'contain' => ['Pedido', 'Produto'],
        ]);

        $this->set(compact('pedidoItem'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $item = $this->PedidoItem->newEmptyEntity();
        if ($this->request->is('post')) {
            $item = $this->PedidoItem->patchEntity($item, $this->request->withData('pedido_id', $this->getRequest()->getParam('pedido_id'))->getData());
            if ($this->PedidoItem->save($item)) {
                return $this->getResponse()->withStringBody(__('Item do pedido salvo com sucesso'));
            }
            if (!empty($item->getErrors())) {
                return $this
                    ->getResponse()
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode($item->getErrors()));
            }
        }
        return $this->getResponse()->withStatus(500)->withStringBody(__('Devido a um erro não foi possível salvar o item do pedido, tente novamente'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pedido Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $item = $this->PedidoItem->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is('put')) {
            $item = $this->PedidoItem->patchEntity($item, $this->request->getData());
            if ($this->PedidoItem->save($item)) {
                $this->Flash->success(__('The pedido item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido item could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Pedido Item id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $item = $this->PedidoItem->get($id);
        if ($this->PedidoItem->delete($item)) {
            return $this->getResponse()->withStatus(204)->withStringBody(__('Item do pedido excluído com sucesso'));
        }
        return $this->getResponse()->withStatus(500)->withStringBody(__('Devido a um erro não foi possível excluir o item do pedido, tente novamente'));
    }
}

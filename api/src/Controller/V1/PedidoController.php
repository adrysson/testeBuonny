<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Controller\AppController;
use App\Model\Entity\Pedido;

/**
 * Pedido Controller
 *
 * @property \App\Model\Table\PedidoTable $Pedido
 * @method \App\Model\Entity\Pedido[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PedidoController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $params = $this->getRequest()->getQueryParams();

        $pedidos = $this->Pedido->find('search', [
            'contain' => ['Cliente'],
            'search' => $params,
        ]);

        // Filtro pra valor mínimo e máximo (para cliente está na model)
        if ((isset($params['valor_min']) && !empty($params['valor_min'])) || (isset($params['valor_max']) && !empty($params['valor_max']))) {
            $pedidos = $pedidos->filter(function (Pedido $pedido) use ($params) {
                if (isset($params['valor_min']) && !empty($params['valor_min'])) {
                    if ($pedido->preco_total < $params['valor_min']) {
                        return false;
                    }
                }
                if (isset($params['valor_max']) && !empty($params['valor_max'])) {
                    if ($pedido->preco_total > $params['valor_max']) {
                        return false;
                    }
                }
                return true;
            });
        };

        $this->set(compact('pedidos'));
        $this->viewBuilder()->setOption('serialize', ['pedidos']);
    }

    /**
     * View method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pedido = $this->Pedido->get($id, [
            'contain' => ['Cliente', 'PedidoItem'],
        ]);

        $this->set(compact('pedido'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pedido = $this->Pedido->newEmptyEntity();
        if ($this->request->is('post')) {
            $pedido = $this->Pedido->patchEntity($pedido, $this->request->getData());
            if ($this->Pedido->save($pedido)) {
                $this->Flash->success(__('The pedido has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido could not be saved. Please, try again.'));
        }
        $clientes = $this->Pedido->Cliente->find('list', ['limit' => 200]);
        $this->set(compact('pedido', 'clientes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pedido = $this->Pedido->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pedido = $this->Pedido->patchEntity($pedido, $this->request->getData());
            if ($this->Pedido->save($pedido)) {
                $this->Flash->success(__('The pedido has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido could not be saved. Please, try again.'));
        }
        $clientes = $this->Pedido->Cliente->find('list', ['limit' => 200]);
        $this->set(compact('pedido', 'clientes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pedido = $this->Pedido->get($id);
        if ($this->Pedido->delete($pedido)) {
            $this->Flash->success(__('The pedido has been deleted.'));
        } else {
            $this->Flash->error(__('The pedido could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

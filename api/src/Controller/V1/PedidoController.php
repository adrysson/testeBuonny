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
                return $this->getResponse()->withStringBody(__('Pedido salvo com sucesso'));
            }
            if (!empty($pedido->getErrors())) {
                return $this
                    ->getResponse()
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode($pedido->getErrors()));
            }
        }
        return $this->getResponse()->withStatus(500)->withStringBody(__('Devido a um erro não foi possível salvar o pedido, tente novamente'));
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
        $this->request->allowMethod(['delete']);
        $pedido = $this->Pedido->get($id);
        if ($this->Pedido->delete($pedido)) {
            return $this->getResponse()->withStatus(204)->withStringBody(__('Pedido excluído com sucesso'));
        }
        return $this->getResponse()->withStatus(500)->withStringBody(__('Devido a um erro não foi possível excluir o pedido, tente novamente'));
    }
}

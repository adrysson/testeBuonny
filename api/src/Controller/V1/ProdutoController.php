<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Controller\AppController;

/**
 * Produto Controller
 *
 * @property \App\Model\Table\ProdutoTable $Produto
 * @method \App\Model\Entity\Produto[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProdutoController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $produtos = $this->Produto->find('all');

        $this->set(compact('produtos'));
        $this->viewBuilder()->setOption('serialize', ['produtos']);
    }
}

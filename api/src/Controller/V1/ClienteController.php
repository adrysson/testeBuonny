<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Controller\AppController;

/**
 * Cliente Controller
 *
 * @property \App\Model\Table\ClienteTable $Cliente
 * @method \App\Model\Entity\Cliente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClienteController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $clientes = $this->Cliente->find('all');

        $this->set(compact('clientes'));
        $this->viewBuilder()->setOption('serialize', ['clientes']);
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Pedido Entity
 *
 * @property int $id
 * @property int $cliente_id
 *
 * @property \App\Model\Entity\Cliente $cliente
 * @property \App\Model\Entity\PedidoItem[] $pedido_item
 */
class Pedido extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'cliente_id' => true,
        'cliente' => true,
        'pedido_item' => true,
    ];
}

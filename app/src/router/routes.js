
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { name: 'index-pedido', path: '', component: () => import('pages/Index.vue') }
    ]
  },
  {
    path: '/pedidos',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { name: 'add-pedido', path: 'add', component: () => import('pages/Pedidos/Add.vue') },
      { name: 'edit-pedido', path: 'edit/:id', component: () => import('pages/Pedidos/Edit.vue') }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes

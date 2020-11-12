
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { name: 'index-pedidos', path: '', component: () => import('pages/Index.vue') }
    ]
  },
  {
    path: '/pedidos',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { name: 'add-pedidos', path: 'add', component: () => import('pages/Pedidos/Add.vue') }
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

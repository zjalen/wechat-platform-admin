const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', redirect: { path: '/platforms'} },
      { path: 'platforms', component: () => import('pages/Index.vue') },
      { path: 'platforms/create', component: () => import('pages/PlatformCreateAndEdit.vue') },
      { path: 'platforms/:id/edit', component: () => import('pages/PlatformCreateAndEdit.vue') },
    ],
  },

  {
    path: '/',
    component: () => import('layouts/NoAuthLayout.vue'),
    children: [
      { path: 'login', component: () => import('pages/Login.vue') },
      // Always leave this as last one,
      // but you can also remove it
      {
        path: '/:catchAll(.*)*',
        component: () => import('pages/Error404.vue'),
      },
    ],
  },
]

export default routes

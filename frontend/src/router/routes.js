const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', redirect: { name: 'platformIndex'} },
      { path: 'platforms', component: () => import('pages/Index.vue'), name: 'platformIndex' },
      { path: 'platforms/create', component: () => import('pages/PlatformCreateAndEdit.vue') },
      { path: 'platforms/:id/edit', component: () => import('pages/PlatformCreateAndEdit.vue') },
      { path: 'open-platform/:id', component: () => import('pages/OpenPlatform.vue') },
    ],
  },

  {
    path: '/open-platform/:opId/mini-program/:appId/',
    component: () => import('layouts/SubManageLayout.vue'),
    children: [
      { path: '', component: () => import('pages/mini-program/Index.vue'), name: 'subMiniProgramIndex' },
      { path: 'basic-information', component: () => import('pages/mini-program/BasicInformation.vue'), name: 'basicInformation' },
      { path: 'trust-domain', component: () => import('pages/mini-program/TrustDomain.vue'), name: 'trustDomain' },
      { path: 'trust-tester', component: () => import('pages/mini-program/TrustTester.vue'), name: 'trustTester' },
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

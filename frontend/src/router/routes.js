
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue') },
      { path: 'admin', component: () => import('pages/Admin.vue') },
      { path: 'login', component: () => import('pages/LoginPage.vue') },
    ]
  },

  {
    path: '/user',
    component: () => import('layouts/LoginLayout.vue'),
    children: [
      { path: 'dashboard', name: 'dashboard', component: () => import('pages/Dashboard.vue') },
      { path: 'profile', name: 'profile', component: () => import('pages/Profile.vue') },
      { path: 'survey', name: 'survey', component: () => import('pages/SurveyForm.vue') },
    ]
  },
  // {
  //   path: '/admin',
  //   component: () => import('layouts/LoginLayout.vue'),
  //   children: [
  //     { path: 'dashboard', name: 'dashboard', component: () => import('pages/Dashboard.vue') },
  //   ]
  // },
  
  

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes

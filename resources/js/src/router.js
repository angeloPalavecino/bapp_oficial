/*=========================================================================================
  File Name: router.js
  Description: Routes for vue-router. Lazy loading is enabled.
  Object Strucutre:
                    path => router path
                    name => router name
                    component(lazy loading) => component to load
                    meta : {
                      rule => which user can have access (ACL)
                      breadcrumb => Add breadcrumb to specific page
                      pageTitle => Display title besides breadcrumb
                    }
  ----------------------------------------------------------------------------------------
  Item Name: Vuesax Admin - VueJS Dashboard Admin Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home'
import Login from './views/Login'
import Roles from './views/pages/administracion/roles/Roles'
import Users from './views/pages/administracion/users/Users'
import Empresas from './views/pages/administracion/empresas/Empresas'
import FueraZona from './views/pages/administracion/fuerazona/FueraZona'
import Excepciones from './views/pages/administracion/excepciones/Excepciones'
import Observaciones from './views/pages/administracion/observaciones/Observaciones'
import ObsInternas from './views/pages/administracion/obsinternas/ObsInternas'


Vue.use(Router)


const router = new Router({
  history: true,
  mode: 'history',
  routes: [

    {
  // =============================================================================
  // MAIN LAYOUT ROUTES
  // =============================================================================
      path: '',
      component: () => import('./layouts/main/Main.vue'),
      children: [
    // =============================================================================
    // Theme Routes
    // =============================================================================
        {
          path: '/',
          name: 'home',
          component: Home,
          meta: {
            auth: true  
          }
        },
        {
          path: '/pages/administracion/users/users',
          name: 'users',
          component: Users,
          meta: {
            auth: true,
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Usuarios'},
              { title: 'Lista de usuarios', active: true },
              ],
              pageTitle: 'Lista de usuarios',
          }
        },
        {
          path: '/pages/administracion/roles/roles',
          name: 'roles',
          component: Roles,
          meta: {
            auth: true,
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Roles'},
              { title: 'Lista de roles', active: true },
              ],
              pageTitle: 'Lista de roles',
            
          }
        },
        {
          path: '/pages/administracion/empresas/empresas',
          name: 'empresas',
          component: Empresas,
          meta: {
            auth: true,
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Empresas'},
              { title: 'Lista de empresas', active: true },
              ],
              pageTitle: 'Lista de empresas',
            
          }
        },
        {
          path: '/pages/administracion/observaciones/observaciones',
          name: 'observaciones',
          component: Observaciones,
          meta: {
            auth: true,
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Observaciones'},
              { title: 'Lista de observaciones', active: true },
              ],
              pageTitle: 'Lista de observaciones',
            
          }
        },
        {
          path: '/pages/administracion/obsinternas/obsinternas',
          name: 'obsinternas',
          component: ObsInternas,
          meta: {
            auth: true,
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Observaciones internas'},
              { title: 'Lista de observaciones internas', active: true },
              ],
              pageTitle: 'Lista de observaciones internas',
            
          }
        },
        {
          path: '/pages/administracion/excepciones/excepciones',
          name: 'excepciones',
          component: Excepciones,
          meta: {
            auth: true,
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Excepciones'},
              { title: 'Lista de excepciones', active: true },
              ],
              pageTitle: 'Lista de excepciones',
            
          }
        },
        {
          path: '/pages/administracion/fuerazona/fuerazona',
          name: 'fuerazona',
          component: FueraZona,
          meta: {
            auth: true,
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Fuera de zona'},
              { title: 'Lista de fuera de zona', active: true },
              ],
              pageTitle: 'Lista de fuera de zona',
            
          }
        }
      ],
    },
  // =============================================================================
  // FULL PAGE LAYOUTS
  // =============================================================================
    {
      path: '',
      component: () => import('@/layouts/full-page/FullPage.vue'),
      children: [
    // =============================================================================
    // PAGES
    // =============================================================================
        {
          path: '/login',
          name: 'login',
          component: Login,
          meta: {
            auth: false
          }
        },
        {
          path: '/pages/error/error-404',
          name: 'pageError404',
          component: () => import('@/views/pages/error/Error404.vue'),
          meta: {
            auth: false
          }
        },
      ]
    },
     // Redirect to 404 page, if no match found
     {
      path: '*',
      redirect: '/pages/error/error-404'
    }
    
  ],
})

export default router
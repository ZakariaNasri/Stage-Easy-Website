import Vue from 'vue'
import VueRouter from 'vue-router'
import HomeView from '../views/welcomePage.vue'
// import HomeView2 from '../views/welcomePage2.vue'
// Student
import studentDashboard from '../views/studentDashboard.vue';
import accueilStd from '../views/studentRoutes/accueilStd.vue';
import profilStd from '../views/studentRoutes/profilStd.vue';
import notificationStd from '../views/studentRoutes/notificationStd.vue';
import demandeStd from '../views/studentRoutes/demandeStd.vue';
import stageStd from '../views/studentRoutes/stageStd.vue';
import signUp from '../components/signUp.vue';

// Chef
import chefDashboard from '../views/chefDashboard.vue';
import accueilChef from '../views/chefRoutes/accueilChef.vue';
import offresChef from '../views/chefRoutes/offresChef.vue';
import profilChef from '../views/chefRoutes/profilChef.vue';
import notificationChef from '../views/chefRoutes/notificationChef.vue';
import gererComptes from '../views/chefRoutes/gererComptes.vue';
import listeStagieres from '../views/chefRoutes/listeStagieres.vue';
// Resp
import respDashboard from '../views/respDashboard.vue';
import accueilResp from '../views/respRoutes/accueilResp.vue';
import profilResp from '../views/respRoutes/profilResp.vue';
import notifResp from '../views/respRoutes/notifResp.vue';
import presenceResp from '../views/respRoutes/presenceResp.vue';
import notationResp from '../views/respRoutes/notationResp.vue';
import offresResp from '../views/respRoutes/offresResp.vue';



Vue.use(VueRouter)


const routes = [
  {
    path: '/signUp',
    name: 'signUp',
    component: signUp
  },
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  // {
  //   path: '/',
  //   name: 'home',
  //   component: HomeView2
  // },
  {
    path: '/signUp',
    name: 'signUp',
    component: signUp
  },
  // Student
  {
    path: '/studentDashboard',
    name: 'studentDashboard',
    component: studentDashboard,
    children: [
      {
        path: '/accueilStd',
        name: 'accueilStd',
        component: accueilStd
      },
      {
        path: '/profilStd',
        name: 'profilStd',
        component: profilStd
      },
      {
        path: '/notificationStd',
        name: 'notificationStd',
        component: notificationStd
      },
      {
        path: '/demandeStd',
        name: 'demandeStd',
        component: demandeStd
      },
      {
        path: '/stageStd',
        name: 'stageStd',
        component: stageStd
      },
      
    ],
  },
  // Chef Departement
  {
    path: '/chefDashboard',
    name: 'chefDashboard',
    component: chefDashboard,
    children: [
      {
        path: '/accueilChef',
        name: 'accueilChef',
        component: accueilChef
      },
      {
        path: '/offresChef',
        name: 'offresChef',
        component: offresChef
      },
      {
        path: '/profilChef',
        name: 'profilChef',
        component: profilChef
      },
      {
        path: '/notificationChef',
        name: 'notificationChef',
        component: notificationChef
      },
      {
        path: '/gererComptes',
        name: 'gererComptes',
        component: gererComptes
      },
      {
        path: '/listeStagieres',
        name: 'listeStagieres',
        component: listeStagieres
      },
    ]
  },
  // Responsable
  {
    path: '/respDashboard',
    name: 'respDashboard',
    component: respDashboard,
    children:[
      {
        path: '/accueilResp',
        name: 'accueilResp',
        component: accueilResp
      },
      {
        path: '/profilResp',
        name: 'profilResp',
        component: profilResp
      },
      {
        path: '/notifResp',
        name: 'notifResp',
        component: notifResp
      },
      {
        path: '/presenceResp',
        name: 'presenceResp',
        component: presenceResp
      },
      {
        path: '/notationResp',
        name: 'notationResp',
        component: notationResp
      },
      {
        path: '/offresResp',
        name: 'offresResp',
        component: offresResp
      }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router

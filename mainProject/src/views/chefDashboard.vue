<template>
    <v-app style="background-color: #eee;">
      <v-row>
        <v-col md="2"><newSideBar ref="sideBar" :navItems="navItems"></newSideBar></v-col>
        <v-col md="10"><router-view></router-view></v-col>
      </v-row>
      
    </v-app>
  </template>
  
  <script>
  import newSideBar from '../components/newSideBar.vue';
  export default {
    name: 'studentDashboard',
    components:{
        newSideBar,
    },

    data(){
      return{
        navItems:[
          {
            to:'/accueilChef',
            icon:'mdi-home',
            title:'Accueil',
          },
          {
            to:'/profilChef',
            icon:'mdi-account',
            title:'Profil',
          },
          {
            to:'/notificationChef',
            icon:'mdi-bell',
            title:'Notifications',
          },
          {
            to:'/offresChef',
            icon:'mdi-offer',
            title:'Offres',
          },
          {
            to:'/gererComptes',
            icon:'mdi-account-group',
            title:'Gerer Comptes',
          },
          {
            to:'/listeStagieres',
            icon:'mdi-account-school',
            title:'Liste Stagiaires',
          },

          
        ]
      }
    }
    }
  
  </script>
  
  <style>
  
  </style>
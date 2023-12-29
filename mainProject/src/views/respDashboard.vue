<template>
  <v-app style="background-color: #eee;">
    <v-row>
      <v-col style="z-index: 100 !important;" md="2"><newSideBar ref="sideBar" :navItems="navItems"></newSideBar></v-col>
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
            to:'/accueilResp',
            icon:'mdi-home',
            title:'Accueil',
          },
          {
            to:'/profilResp',
            icon:'mdi-account',
            title:'Profil',
          },
          {
            to:'/notifResp',
            icon:'mdi-bell',
            title:'Notifications',
          },
          {
            to:'/offresResp',
            icon:'mdi-offer',
            title:'Mes Offres',
          },
          {
            to:'/notationResp',
            icon:'mdi mdi-note-multiple',
            title:'Notation',
          },
          {
            to:'/presenceResp',
            icon:'mdi mdi-list-status',
            title:'Présence',
          },

          
        ]
      }
    }
    }
</script>

<style>

</style>
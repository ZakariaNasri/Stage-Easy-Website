<template>
  <v-app style="background-color: #eee;">
    <v-row style="padding: 0;">
      <v-col style="z-index: 100 !important;" md="2"><newSideBar ref="sideBar" :navItems="navItems"></newSideBar></v-col>
      <v-col md="10" style="padding: 0;"><router-view></router-view></v-col>
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
            to:'/accueilStd',
            icon:'mdi-home',
            title:'Accueil',
          },
          {
            to:'/profilStd',
            icon:'mdi-account',
            title:'Profil',
          },
          {
            to:'/notificationStd',
            icon:'mdi-bell',
            title:'Notifications',
          },
          {
            to:'/demandeStd',
            icon:'mdi-format-list-bulleted',
            title:'Mes Demandes',
          },
          {
            to:'/stageStd',
            icon:'mdi-account-school',
            title:'Stage Courant',
          },

          
        ]
      }
    }
    }
</script>

<style>

</style>
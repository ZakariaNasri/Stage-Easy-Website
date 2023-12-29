<template>
    <v-dialog
      v-model="dialog"
      scrollable  
      max-width="500px"
      transition="dialog-transition"
    >
    <v-alert
              :value="alert1"
              class="mb-0"
              transition="scale-transition"
              >
              <v-row>
                <v-col md="8"> 
                  <h1 class="header">{{header}}</h1>
                  <v-divider class="mb-3"></v-divider>
                  <h3 class="paragraph">{{ msg }}</h3>
                </v-col>
                <v-col md="4">
                  <img src="@/assets/icons/Cross Mark Symbol Icon.H01.2k.png" style="width: 140px; height: 140px;">
                </v-col>
              </v-row>
              </v-alert>
    </v-dialog>
  </template>
  
  <script>
  export default {
    data(){
      return{
        dialog:true
  
      }
    },
    props:['header','msg']
    
  }
  </script>
  
  <style scoped>
    .header{
    font-size: 50px;
    font-family:'Outfit';
    color: #ff3030;
  }
  .paragraph{
    font-size: 20px;
    font-family:'Outfit';
    color: #120030;
  }
  </style>
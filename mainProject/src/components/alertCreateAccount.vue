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
                <img :src="mySrc" style="width: 140px; height: 140px;">
              </v-col>
            </v-row>
            <v-btn
              v-if="msg==='confirmer la creation du compte'"
              class="mt-2"
              @click="createAccount"
              color="#63D693"
              :block="true"
            >
              Confirmer
            </v-btn>
            </v-alert>
  </v-dialog>
</template>

<script>
import api from '@/api'
export default {
  data(){
    return{
      dialog:true,
      mySrc:require('@/assets/icons/Checkmark Icon.I01.2k.png')

    }
  },
  computed(){
    this.isLogout();
  },
  mounted(){
    console.log(this.idStage)
  },
  methods:{
  isLogout(){
    if (this.msg==="Deconnection...") {
      this.mySrc=require('@/assets/icons/Icon Logout.I01.2k.png')
    }
  },
  async createAccount(){
      console.log(this.idStage)
      const response = await api.post('/confirmCreation',{id:this.idStage})
        console.log(response)
        setTimeout(function() {
            location.reload();
        }, 500);
      
  }
  },
  props:['header','msg','idStage']
  
}
</script>

<style scoped>
  .header{
  font-size: 50px;
  font-family:'Outfit';
  color: #63D693;
}
.paragraph{
  font-size: 20px;
  font-family:'Outfit';
  color: #120030;
}
</style>
<template>
  <v-dialog
    v-model="dialog"
    scrollable
    max-width="400px"
    transition="dialog-transition"
  >

  <alertCreateAccount 
      header="Actualisation!"
      :msg="msg"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>

  <div class="container text-center">
    <h1 class="header">{{header}}</h1>
    <br>
    <v-text-field
        v-model="currentMdps"
        name="mot de passe"
        label="Mot de passe"
        :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
        :type="show ? 'text' : 'password'"
        @click:append="show = !show"
        color="#63D693"
        style="width: 70%; display: inline-block;"
    >
    </v-text-field>

    <v-btn
      class="mr-4 mt-4"
      @click="reset"
      flat
      style="border: 2px solid #63D693;"
      >
      Annuler
    </v-btn>
    <v-btn 
      class="mt-4" 
      @click="saveInfo"
      color="#63D693"
    >
      Confirmer
    </v-btn>
</div>
  </v-dialog>
</template>

<script>
import api from '@/api'
import alertCreateAccount from "@/components/alertCreateAccount.vue";
export default {
    components:{
        alertCreateAccount,
    },
    data(){
        return{
            dialog:true,
            show:false,
            showAlertCreateModel:false,
            currentMdps:'',
            msg:''
        }
    },

    props:['header','type','infoEtud','nvMdps'],

    computed:{
    user() {
      return JSON.parse(localStorage.getItem('user'));
    }
  },

    methods:{
    async saveInfo(){
      console.log(this.infoEtud)
      try{
    await api.post('/updateInfoEtud', {
      id:this.user.id_Etud,
      currentMdps:this.currentMdps,
      nom: this.infoEtud.nom_Etud,
      prenom: this.infoEtud.pre_Etud,
      email: this.infoEtud.email,
      nvMdps: this.nvMdps,
      dateN: this.infoEtud.dateNaiss,
      lieuN: this.infoEtud.lieuNaiss,
      telE: this.infoEtud.telEtud,
      numCarte: this.infoEtud.numCarte,
      diplome: this.infoEtud.diplome,
      specialite: this.infoEtud.specialite,
      })
      .then(response => {
        console.log(response)
        this.msg = response.data.msg;
        this.showAlertCreateModel=!this.showAlertCreateModel
        this.dialog=!this.dialog
        setTimeout(function() {
            location.reload();
        }, 2500);
      })
  }catch(error) {
        console.log(error)
      }
    },
    reset(){
        location.reload();
    }
}
}
</script>

<style scoped>
    .container{
        text-align: center;
        border: 2px solid #63D693;
        border-radius: 10px;
        background-color: white;
        width: 400px;
    }
    .header{
  font-size: 30px;
  font-family:'Outfit';
  color: #63D693;
  margin-bottom: 5px;
}
</style>
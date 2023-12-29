<template>
  <v-dialog
    v-model="dialog"
    scrollable  
    max-width="500px"
    transition="dialog-transition"
  >

  <alert-create-account 
      :header="header2"
      :msg="msg2"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>
  <v-alert
            :value="alert1"
            class="mb-0"
            transition="scale-transition"
            >

              <v-row class="d-flex justify-center">
                  <img src="@/assets/icons/Motif.I09.2k.png" style="width: 150px; height: 150px;">
              </v-row>
              <v-divider class="my-3"></v-divider>
              <v-row class="d-flex justify-center">
                <h1 class="header">{{header}}</h1>
              </v-row>
            <v-row class="d-flex justify-center mb-2">
                <h3 class="paragraph text-center">{{ msg }}</h3>
            </v-row>

            <v-text-field
              v-model="motif"
              label="Motif"
              type="text"
              outlined
              dense
              color="#63D693"
              class="rounded-lg mt-4"
            ></v-text-field>

            <v-btn
              v-if="refuseFrom==='ChefDep'"
              class="mt-2"
              @click="refuseDemand"
              color="#63D693"
              :block="true"
            >
              Confirmer
            </v-btn>
            <v-btn
              v-if="refuseFrom==='Resp'"
              class="mt-2"
              @click="refuseDemandR"
              color="#63D693"
              :block="true"
            >
              Confirmer
            </v-btn>

            </v-alert>
  </v-dialog>
</template>

<script>
import api from "@/api";
import alertCreateAccount from './alertCreateAccount.vue';
export default {
  components: { alertCreateAccount },
  data(){
    return{
      dialog:true,
      showAlertCreateModel:false,
      motif:'',
      msg2:'',
      header2:'',
    }
  },
  props:['header','msg','id_Stage','refuseFrom'],
  methods:{
    async refuseDemand(){
      console.log(this.id_Stage)
      try {
        await api.post("/envoyerMotif", {id:this.id_Stage,motif:this.motif})
        
      } catch (error) {
        console.log(error);
      }
      try {
        await api.post("/refuserDemande", {id:this.id_Stage})
        .then((response) => {
          this.msg2=response.data.msg
          this.header2='demande refuser'
          this.showAlertCreateModel=!this.showAlertCreateModel
          setTimeout(function() {
            location.reload();
        }, 2000);
        });
      } catch (error) {
        console.log(error);
      }
    },
    async refuseDemandR(){
      console.log(this.id_Stage)
      try {
        await api.post("/envoyerMotifR", {id:this.id_Stage,motif:this.motif})
        
      } catch (error) {
        console.log(error);
      }
      try {
        await api.post("/refuserDemandeR", {id:this.id_Stage})
        .then((response) => {
          this.msg2=response.data.msg
          this.header2='demande refuser'
          this.showAlertCreateModel=!this.showAlertCreateModel
          setTimeout(function() {
            location.reload();
        }, 2000);
        });
      } catch (error) {
        console.log(error);
      }
      
    },
  }

}
</script>

<style scoped>
  .header{
  font-size: 30px;
  font-family:'Outfit';
  color: #63D693;
}
.paragraph{
  font-size: 15px;
  font-family:'Outfit';
  color: #120030;
}
</style>
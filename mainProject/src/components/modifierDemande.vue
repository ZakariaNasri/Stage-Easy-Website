<template>
<v-dialog class="overflow-hidden" v-model="dialog" max-width="900" >
  <alertCreateAccount 
      header="Modification:"
      :msg="msg"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>

      <ErrorPopup
      header="Désolé !"
      :msg="msg"
      v-if="showeErrorModel"
      @close="showeErrorModel = false"
      @click:outside="showeErrorModel = false"/>

  <v-card class="px-5 py-5" style="border-radius: 10px;">
    <h1 class="bigTitle text-center mb-4">Modifier La Demande</h1>
    <form>
        <v-container grid-list-xs>
            <v-row class="mt-5">
        <v-col cols="12" md="12" >
          <v-text-field 
          v-model="demandeInfos.theme"
          label="theme de stage"
          filled 
          :value="InfoDemande.theme"
          outlined 
          dense>
          </v-text-field>
        </v-col>

        <v-col cols="12" md="6" >
          <v-text-field 
          v-model="demandeInfos.duree"
          label="duree de stage"
          filled 
          :value="InfoDemande.duree"
          outlined 
          dense>
          </v-text-field>
        </v-col>

        <v-col cols="12" md="6" >
          <v-text-field 
          v-model="demandeInfos.datePost"
          label="date poste"
          filled 
          :value="InfoDemande.datePost"
          outlined 
          dense>
          </v-text-field>
        </v-col>

        <v-col cols="12" md="6" >
          <v-text-field 
          v-model="demandeInfos.dateDeb"
          label="date debut de stage"
          filled 
          :value="InfoDemande.dateDeb"
          outlined 
          dense>
          </v-text-field>
        </v-col>

        <v-col cols="12" md="6" >
          <v-text-field 
          v-model="demandeInfos.dateFin"
          label="date fin de stage"
          filled 
          :value="InfoDemande.dateFin"
          outlined 
          dense>
          </v-text-field>
        </v-col>

      </v-row>
        </v-container>
    </form>
    <v-layout row wrap class="justify-end mr-3 mb-2">
        <v-btn class="mr-3"
        style="border: 2px solid #63D693;"
        >Effacer</v-btn>
    <v-btn color="#63D693"
    @click="updateDemand"
    >Sauvgarder</v-btn>
    </v-layout>
    
  </v-card>
</v-dialog>

</template>

<script>
import api from '@/api'
import alertCreateAccount from "@/components/alertCreateAccount.vue";
import ErrorPopup from './errorPopup.vue';
export default {
    components:{
        alertCreateAccount,
        ErrorPopup,
    },
  props:['InfoDemande'],
    data(){
        return{
            dialog: true,
            demandeInfos: {},
            showAlertCreateModel:false,
            showeErrorModel:false
        }
       
    },
    methods:{
      async updateDemand(){
        try{

          console.log(this.demandeInfos.duree)
          await api.post('/modifierDemande',{
            id:this.demandeInfos.id_Stage,
            dateD:this.demandeInfos.dateDeb,
            theme:this.demandeInfos.theme,
            duree:this.demandeInfos.duree,
            nomR:this.demandeInfos.nom_Resp,
            preR:this.demandeInfos.pre_Resp,
            email:this.demandeInfos.emailResp,
            nomEnt:this.demandeInfos.nom_Entreprise,
            Addr:this.demandeInfos.addr_Entreprise,
            tel:this.demandeInfos.telEntreprise,
          }
          )
          .then(response => {
            if (response.data.msg==="votre demande  ete bien modifier") {
              this.msg = response.data.msg;
              this.showAlertCreateModel=!this.showAlertCreateModel;
            setTimeout(function() {
            location.reload();
            }, 2000);
            }
              else{
                this.msg = response.data.msg;
                this.showeErrorModel=!this.showeErrorModel;
                setTimeout(function() {
                location.reload();
                }, 2000);
              }
      })
    }
    catch(error) {
      console.log(this.demandeInfos.duree)
        console.log(error);
      }
      }
    },
    mounted(){
      this.demandeInfos=this.InfoDemande
    }
}
</script>

<style scoped>
  .bigTitle{
    font-size: 40px;
    font-family: Outfit;
    font-weight: 600;
    color: #63D693;
  }
</style>
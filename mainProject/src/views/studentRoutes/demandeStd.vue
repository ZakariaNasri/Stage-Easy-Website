<template>
  <div class="container">

    <errorPopup 
      header="Erreur!"
      :msg="msg"
      v-if="showErrorPopup"
      @close="showErrorPopup = false"
      @click:outside="showErrorPopup = false"/>

    <alertCreateAccount 
      header="Supprimer!"
      :msg="msg"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>
      
    <stdHeader title="Mes Demandes" icon1="mdi-bell" toPage="./notificationStd"/>
    <v-row class="my-5">
        <v-col>
          <v-text-field
            v-model="searchTerm"
            placeholder="trouver une demande..."
            search
            style="width: 95%;margin: 0 auto;"
            filled
            color="#666"
            rounded
            dense
            class="search"
            append-icon="mdi-magnify"
          ></v-text-field>
        </v-col>
      </v-row>

    <modifierDemande 
      :InfoDemande="oneDemande"
      v-if="showDemandeModifModal" 
      @close="showDemandeModifModal = false"
      @click:outside="showDemandeModif"/>

      <emptyArrays v-if="demandes.length === 0" 
      message="Aucune demande disponible"
      :source="imagePath"
      />
    <v-container grid-list-xs>
      <v-select
      v-if="demandes.length !== 0" 
        v-model="selectedFilter"
        :items="filterOptions"
        label="Sort By"
        outlined
        color="#120030"
        background-color="#63d69371"
        style="width: 200px;"></v-select>
      <v-row cols="1">
        <v-col lg12 v-for="(demande, id) in filteredDemandes" :key="id">
          <v-card class="mx-auto demandeCards" max-width="" width="300px" elevation="0">
            <v-list-item three-line>
              <v-list-item-content>
                <div class="text-overline mb-4">
                  {{demande.datePost}}
                </div>
                <v-list-item-title class="text-h5 mb-1">
                  {{demande.theme}}
                </v-list-item-title>
                <v-list-item-subtitle>{{demande.nom_Resp}} {{ demande.pre_Resp }}</v-list-item-subtitle>
              </v-list-item-content>
              <v-list-item-avatar tile size="80" color="grey">
                <img :src="`${backendUrl}/offerPic/${demande.id_Offre}`" alt="alt">
              </v-list-item-avatar>
            </v-list-item>
            <v-row>
                <v-card-actions>
              <v-list-item-subtitle class="ml-5">duree : <span style="color: rgb(153, 153, 153);">{{ demande.duree }} jours</span> </v-list-item-subtitle>
            </v-card-actions>
            <v-spacer></v-spacer>
                <v-card-actions>
                <v-chip class="mr-5" :color="getChipColor(demande.etat,demande.etatChef,demande.etatResp)" text-color="white">{{demande.etat}}</v-chip>
            </v-card-actions>
            </v-row>
            <v-card-actions>
              <v-list-item-subtitle class="demandeDetails ml-3"
              @click="demande.showDetails = !demande.showDetails"
              >Details...</v-list-item-subtitle>
            </v-card-actions>
            <v-expand-transition>
              <div v-show="demande.showDetails">
              <v-divider></v-divider>
                
              <!-- <h4 class="detail"><span>nom :</span> {{ demande.nom_Etud }}</h4>
              <h4 class="detail"><span>prenom : </span>{{ demande.pre_Etud }}</h4>
              <h4 class="detail"><span>Date/lieu de naissance : </span>{{ demande.dateNaiss }} - {{ demande.lieuNaiss }}</h4>
              <h4 class="detail"><span>email :</span> {{ demande.emailEtud }}</h4>
              <h4 class="detail"><span>Telephone : </span>{{ demande.telEtud }}</h4>
              <h4 class="detail"><span>Diplome : </span>{{ demande.diplome }}</h4>
              <h4 class="detail"><span>Specialite : </span>{{ demande.specialite }}</h4> -->
              <h4 class="detail"><span>Date de debut : </span>{{ demande.dateDeb }}</h4>
              <h4 class="detail mb-1"><span>Date de fin : </span>{{ demande.dateFin }}</h4>
              <h4 class="detail mb-1" v-show="demande.etat=== 'refuse'"><span class="motif">Motif de refus :</span>{{ demande.motif }}</h4>
              <v-layout justify-center>
                
                <v-btn color="red" dark class="text-center my-3 ml-3"
                
              v-if="demande.etatChef==='en attente'||demande.etat==='refuse'"
              @click="supprimer(demande.etat,demande.etatChef,demande.id_Stage)"
              outlined
              >Supprimer</v-btn>
                <v-spacer></v-spacer>
                <v-btn  v-if="demande.etatChef==='en attente'||demande.etat==='refuse'" 
                        class="text-center my-3 mr-3" 
                        outlined
                        color="#63D693"
                        @click="showDemandeModif(demande)"
                >Modifier</v-btn>
              </v-layout>
              
          </div>
  </v-expand-transition>
            
            
            
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import api from "@/api";
import  modifierDemande  from "@/components/modifierDemande.vue";
import stdHeader from "@/components/stdHeader.vue";
import alertCreateAccount from "@/components/alertCreateAccount.vue";
import errorPopup from "@/components/errorPopup.vue";
import EmptyArrays from '@/components/emptyArrays.vue';
import backendUrl from '@/backendConfig'
  export default {
    components:{
      stdHeader,
      modifierDemande,
      alertCreateAccount,
      errorPopup,
        EmptyArrays,
    },
  data() {
    return {
      backendUrl,
      demandes: [],
      oneDemande:{},
      case:{},
      imagePath: require('@/assets/empty/6.png'),
      showDemandeModifModal:false,
      showAlertCreateModel:false,
      showErrorPopup:false,
      disableSubBtn:false,
      selectedFilter: 'all',
      filterOptions: ['all','en attente', 'refuse', 'accepte',],
      msg:'',
    }
  },
  computed: {
    filteredDemandes() {
      if (!this.selectedFilter || this.selectedFilter==='all') {
        return this.demandes
      } else {
        return this.demandes.filter((demande) => demande.etat === this.selectedFilter)
      }
    },
    user() {
      return JSON.parse(localStorage.getItem('user'));
    }
  },
  async mounted(){
    await this.listeDemande();
    console.log(this.demandes)
    // this.suppBtn()

    
    
  },
  methods: {
    getChipColor(status,statusChef,statusResp) {
      if (statusChef==='accepte'&& statusResp==='en attente') {
        return '#FFD93D'
      }
      else if(statusChef==='en attente'){
        return 'orange'
      }
      else if(statusResp==='refuse'||statusChef==='refuse'){
        return 'red'
      }
      else if (statusResp==='accepte'){
        return 'green'
      }
      },

    // suppBtn(){
    //   for (let i = 0; i < this.demandes.length; i++) {
    //     if (this.demande[i].etat==='refuser'||(this.demande[i].etatChef==='en attente')) {
    //       this.disableSubBtn=false
    //     }else 
    //     {
    //       this.disableSubBtn=true
    //     }
    //   }
    // },

    showDemandeModif(InfoDemande) {
        this.oneDemande=InfoDemande
      this.showDemandeModifModal = !this.showDemandeModifModal
        },

        async listeDemande(){
  try{
    await api.post('/listeDemande' , {id:this.user.id_Etud})
      .then(response => {
        this.demandes=response.data
      })
  }catch(error) {
        console.log(error)
      }},

      async supprimer(etat,etatChef,idStage){
        if (etat==='refuse'||(etatChef==='en attente')) {
          try{
          await api.delete('/suppDemande', {data:{id:idStage}})
          .then(response => {
            this.msg = response.data.msg;
            this.showAlertCreateModel=!this.showAlertCreateModel;
            setTimeout(function() {
            location.reload();
        }, 1500);
      })
    }
    catch(error) {
        console.log(error);
      }
        }else 
        {
          this.msg='cette demande est deja acceptee'
          this.showErrorPopup=!this.showErrorPopup
        }
        
      }
    },
  
  }


</script>

<style scoped>
.header{
  font-size: 70px;
  font-family:'Outfit';
  color: #120030;
}
.header span{
  color: #63D693;
}
.demandeDetails{
  color: #120030; 
}
.demandeDetails:hover{
  color: #63D693;
  cursor: pointer;
}
.demandeCards{
  border:2px solid #63D693;
  border-radius: 10px;
}
.detail{
  color: #120030;
  font-size: 15px;
  font-family: Outfit;
}
.detail span{
  margin: 3px 0 0 5px;
  font-family: Outfit;
  color: rgb(122, 122, 122);
}
.motif{
  color: red !important;
}
</style>
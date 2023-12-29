<template>
  <v-container grid-list-xs>
    <chefHeader title="Accueil" icon1="mdi-bell" toPage="./notificationChef"/>
      <v-row style="margin-bottom: 50px;">
        <v-col>
          <v-text-field
            v-model="searchTerm"
            placeholder="trouver une demande..."
            search
            filled
            color="#666"
            rounded
            dense
            class="search"
            append-icon="mdi-magnify"
          ></v-text-field>
        </v-col>
      </v-row>
      <motifModel 
      header="Envoyer un Motif"
      msg="l est important que l'étudiant comprenne la raison du refus de sa demande." 
      :id_Stage="id_Stage"
      refuseFrom="ChefDep"
      v-if="showMotifModel"
      @close="showMotifModel = false"
      @click:outside="showMotifModel = false"
      />
    <alertCreateAccount 
      :header="header"
      :msg="msg"
      :idStage="idStage"
      imgSrc="require('@/assets/icons/bell.png')"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"
      />
      <emptyArrays v-if="demandes.length === 0 && demandesAccepte.length === 0 && demandesRefuse.length === 0" 
      message="Aucune demande disponible"
      :source="imagePath"
      />

    <v-container grid-list-xs v-if="demandes.length !== 0 || demandesAccepte.length !== 0 || demandesRefuse.length !== 0" >
      
      <!-- DEMANDES EN ATTENTES -->

      <v-row class="mb-2">
        <v-btn @click="pendingControl=!pendingControl" elevation="0" rounded color="#63D693">
          <v-icon>{{ pendingControl ? 'mdi-chevron-down' : 'mdi-chevron-right' }}</v-icon>
        </v-btn>
        <h3 class="DemandeCase mt-1 mx-2">Demandes En Atente </h3>
        <v-divider class="mt-4"></v-divider>
      </v-row>
      <v-row>
        <v-col md="12" class="mb-3" v-for="(demande, id) in filteredDemandes" :key="id" v-show="pendingControl">
          <v-card class="demandes ml-3" elevation="0">
            <v-row>
              <v-col md="10">
                <v-card-title class="demandeTitle">{{ demande.theme }}</v-card-title>
                <v-card-subtitle>{{ demande.nom_Etud }} {{ demande.pre_Etud }} / {{ demande.email }}</v-card-subtitle>
                <v-card-actions class="ml-2 mb-2">
                  <v-btn color="#63D693"
                        rounded 
                        dark
                        @click="acceptFromChef(demande.id_Stage)"
                        ><v-icon>mdi-check-bold</v-icon>
                      </v-btn>
                  <v-btn color="error" 
                        rounded 
                        dark
                        @click="refuseFromChef(demande.id_Stage)"
                        ><v-icon>mdi-close-thick</v-icon></v-btn>
                </v-card-actions>
              </v-col>
              <v-col md="2" class="text-center align-self-center">
                <img :src="`${backendUrl}/offerPic/${demande.id_Offre}`" alt="alt" style="width: 100px; height: 100px; border-radius: 20px;">
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>

      <!-- DEMANDES ACCEPTEES -->
      <v-row class="mb-2">
        <v-btn @click="acceptedControl=!acceptedControl" elevation="0" rounded color="#63D693">
          <v-icon>{{ acceptedControl ? 'mdi-chevron-down' : 'mdi-chevron-right' }}</v-icon>
        </v-btn>
        <h3 class="DemandeCase mt-1 mx-2">Demandes Acceptees </h3>
        <v-divider class="mt-4"></v-divider>
      </v-row>
      <v-row>
        <v-col md="12" class="mb-3" v-for="(demandeAccepte, id) in filteredAcceptedDemandes" :key="id" v-show="acceptedControl">
          <v-card class="demandes ml-3" elevation="0" style="background-color: rgba(72, 255, 72, 0.148);">
            <v-row>
              <v-col md="10">
                <v-card-title class="demandeTitle">{{ demandeAccepte.theme }}</v-card-title>
                <v-card-subtitle>{{ demandeAccepte.nom_Etud }} {{ demandeAccepte.pre_Etud }} / {{ demandeAccepte.email }}</v-card-subtitle>
                <v-card-actions class="ml-2 mb-2">
                  
                </v-card-actions>
              </v-col>
              <v-col md="2" class="text-center align-self-center">
                <img :src="`${backendUrl}/offerPic/${demandeAccepte.id_Offre}`" class="mt-2" alt="alt" style="width: 80px; height: 80px; border-radius: 15px;">
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>

      <!-- DEMANDES REFUSEES -->
      <v-row class="mb-2">
        <v-btn @click="refusedControl=!refusedControl" elevation="0" rounded color="#63D693">
          <v-icon>{{ refusedControl ? 'mdi-chevron-down' : 'mdi-chevron-right' }}</v-icon>
        </v-btn>
        <h3 class="DemandeCase mt-1 mx-2">Demandes Refusees </h3>
        <v-divider class="mt-4"></v-divider>
      </v-row>
      <v-row>
        <v-col md="12" class="mb-3" v-for="(demandeRefuse, id) in filteredRefusedDemandes" :key="id" v-show="refusedControl">
          <v-card class="demandes ml-3" elevation="0"  style="background-color: rgba(255, 88, 88, 0.253); border: 2px solid rgba(255, 0, 0, 0.379);">
            <v-row>
              <v-col md="10">
                <v-card-title class="demandeTitle">{{ demandeRefuse.theme }}</v-card-title>
                <v-card-subtitle>{{ demandeRefuse.nom_Etud }} {{ demandeRefuse.pre_Etud }} / {{ demandeRefuse.email }}</v-card-subtitle>
                <v-card-actions class="ml-2 mb-2">
                  
                </v-card-actions>
              </v-col>
              <v-col md="2" class="text-center align-self-center">
                <img :src="`${backendUrl}/offerPic/${demandeRefuse.id_Offre}`" alt="alt" style="width: 80px; height: 80px; border-radius: 15px;">
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-container>
</template>

<script>
import api from "@/api";
import alertCreateAccount from "@/components/alertCreateAccount.vue";
import chefHeader from "@/components/chefHeader.vue";
import MotifModel from '@/components/motifModel.vue';
import backendUrl from '@/backendConfig'
import EmptyArrays from '@/components/emptyArrays.vue';
export default {
  components:{
    chefHeader,
    alertCreateAccount,
    MotifModel,
    EmptyArrays,
  },
  data(){
    return{
      backendUrl,
      showMotifModel:false,
      demandes:[],
      msg:'',
      header:'',
      demandesAccepte:[],
      demandesRefuse:[],
      searchTerm: '',
      pendingControl:true,
      acceptedControl:true,
      refusedControl:true,
      imagePath: require('@/assets/empty/5.png'),
      idStage:null,
    }
  },

  async mounted(){
      // demandes en attente
  await this.getDemands()

    // demandes acceptees

    await this.getAcceptedDemands()

        // demandes refusees

        await this.getRefusedDemands()
  },

  methods:{
    async acceptFromChef(id_Stage){
      try{
      await api.post('/ChefAccepter',{id:id_Stage})
      .then(response => {
        this.demandes = response.data;
        if (response.data.msg==="confirmer la creation du compte") {
          this.header='acceptee !'
          this.idStage=id_Stage
        this.msg=response.data.msg
        this.showAlertCreateModel=!this.showAlertCreateModel
        }
        else{
          this.header='acceptee !'
        this.msg=response.data.msg
        this.showAlertCreateModel=!this.showAlertCreateModel
        setTimeout(function() {
            location.reload();
        }, 2000);
        }

      })
    }
    catch(error) {
        console.log(error);
      }
    },

    refuseFromChef(id_Stage){
        this.showMotifModel=!this.showMotifModel
        this.id_Stage=id_Stage
      },

          // demandes en attente
    async getDemands(){
      try{
      await api.get('/listDemandeAttente',{id:this.user.id_ChefDep})
      .then(response => {
        console.log(response)
        this.demandes = response.data;
        console.log(this.demandes)
      })
    }
    catch(error) {
        console.log(error);
      }
    },
          // demandes acceptees
          async getAcceptedDemands(){
      try{
      await api.get('/listDemandeAccepte',{id:this.user.id_ChefDep})
      .then(response => {
        this.demandesAccepte = response.data;
      })
    }
    catch(error) {
        console.log(error);
      }
    },
    // demandes Refusee
    async getRefusedDemands(){
      try{
      await api.get('/listDemandeRefuse',{id:this.user.id_ChefDep})
      .then(response => {
        this.demandesRefuse = response.data;
      })
    }
    catch(error) {
        console.log(error);
      }
    },
  },

  computed: {
    user() {
      return JSON.parse(localStorage.getItem('user'));
    },
    filteredDemandes() {
  if (this.searchTerm === '') {
    return this.demandes;
  } else {
    return this.demandes.filter((demande) => {
      return Object.values(demande).some(
        (val) =>
          val !== null &&
          typeof val === 'string' &&
          val.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
    },
    );
  }
},
filteredAcceptedDemandes() {
  if (this.searchTerm === '') {
    return this.demandesAccepte;
  } else {
    return this.demandesAccepte.filter((demandeAccepte) => {
      return Object.values(demandeAccepte).some(
        (val) =>
          val !== null &&
          typeof val === 'string' &&
          val.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
    },
    );
  }
},
filteredRefusedDemandes() {
  if (this.searchTerm === '') {
    return this.demandesRefuse;
  } else {
    return this.demandesRefuse.filter((demandeRefuse) => {
      return Object.values(demandeRefuse).some(
        (val) =>
          val !== null &&
          typeof val === 'string' &&
          val.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
    },
    );
  }
},
  }}
</script>

<style scoped>


.demandes{
  border-radius: 10px;
  border: 2px solid #63D693;
  color: #120030;
}
.DemandeCase{
  color: #120030;
  font-size: 20;
  font-family: Outfit;
}
</style>
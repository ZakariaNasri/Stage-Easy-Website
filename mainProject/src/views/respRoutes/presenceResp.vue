<template>
    <div class="container">
      <RespHeader title="Presence" icon1="mdi-bell" toPage="./notifResp"/>

      <v-row>
        <v-col>
          <v-text-field
    placeholder="trouver une presence.."
    v-model="searchTerm"
    filled
    color="#666"
    rounded
    dense
    class="search"
    append-icon="mdi-magnify"
  ></v-text-field>
        </v-col>
      </v-row>

  <presenceForm v-if="showPresenceFormModal" 
                    @close="showPresenceFormModal = false"
                    @click:outside="showPresenceFormModal=false"/>
    <button
        class="addOfferBtn"
        @click="showForm"
        >nouvelle presence
      </button>

     


    <h3 class=" ml-2 typo1">Présence d'aujourd'hui</h3>
    <p class="sub">Toutes les stagiaires présentes en ce jour </p>
    <v-container grid-list-xs>
      <v-simple-table class="tablePresence" style="border-radius: 10px;">
    <template v-slot:default>
      <thead style="background-color: #63d69371;" class="typo1">
        <tr>
          <th class="text-left">
            Nom
          </th>
          <th class="text-left">
            Prenom
          </th>
          <th class="text-left">
            Heure d'entree
          </th>
          <th class="text-left">
            Heure de sortie
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="presResp in presenceResp"
          :key="presResp.id"
        >
          <td>{{ presResp.nom_Etud }}</td>
          <td>{{ presResp.pre_Etud }}</td>
          <td>{{ presResp.heureEntr }}</td>
          <td>{{ presResp.heureSort }}</td>
          
        </tr>
      </tbody>
    </template>
  </v-simple-table>


  <v-row style="margin: 40px 0 10px;">
    <v-icon @click="showeOldPresence" class="mb-2">{{ oldPresenceControl ? 'mdi-chevron-down' : 'mdi-chevron-right' }}</v-icon>
    <p class="sub">Afficher Toutes les presences precedentes</p>
    <div style="width: 10px;"></div>
    <v-divider class="mt-4"></v-divider>
  </v-row>
    
    <v-simple-table class="tablePresence" style="border-radius: 10px;"  v-if="oldPresenceControl">
    <template v-slot:default>
      <thead style="background-color: #63d69371;" class="typo1">
        <tr>
          <th class="text-left">
            Date
          </th>
          <th class="text-left">
            Nom
          </th>
          <th class="text-left">
            Prenom
          </th>
          <th class="text-left">
            Heure d'entree
          </th>
          <th class="text-left">
            Heure de sortie
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="oldPresResp in oldPresence"
          :key="oldPresResp.id"
        >
          <td>{{ oldPresResp.datePresence }}</td>
          <td>{{ oldPresResp.nom_Etud }}</td>
          <td>{{ oldPresResp.pre_Etud }}</td>
          <td>{{ oldPresResp.heureEntr }}</td>
          <td>{{ oldPresResp.heureSort }}</td>
          
        </tr>
      </tbody>
    </template>
  </v-simple-table>
    </v-container>
    </div>

  </template>
  
  <script>
  import api from '@/api'
  import presenceForm  from "@/components/presenceForm.vue";
  import RespHeader from "@/components/respHeader.vue";
export default {
    components:{
    presenceForm,
    RespHeader
},
    data(){
        return{
            presenceResp:[],
            oldPresence:[],
            oldPresenceControl:false,
            showPresenceFormModal: false,
        }
    },
    computed: {
    user() {
      return JSON.parse(localStorage.getItem('user'));
    },
  },
    methods: {
        showForm() {
      this.showPresenceFormModal = !this.showPresenceFormModal
        },
        async getPresence(){
          try{
      await api.post('/listPresence')
      .then(response => {
        this.presenceResp=response.data
      })
    }
    catch(error) {
        console.log(error);
      }
        },
        async getOldPresence(){
          try{
      await api.post('/listTousPresence')
      .then(response => {
        this.oldPresence=response.data
        console.log(this.oldPresence)
      })
    }
    catch(error) {
        console.log(error);
      }
        },
        showeOldPresence(){
          this.oldPresenceControl=!this.oldPresenceControl
        }
    },
    mounted(){
      this.getPresence();
      this.getOldPresence();
    }
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
  .tablePresence{
    border-radius: 10px;
    border: 1px solid #120030;

  }
  .addOfferBtn{
  padding: 10px 30px; 
  font-size: 25px; 
  font-family: Outfit;
  font-weight: 500;
  color: white;
  background-color: #63D693;
  position: fixed;
  /* border: 2px solid white; */
  right: 5%;
  bottom: 5%;
  z-index: 100;
  border-radius: 15px;
  padding: 15px 30px;
  box-shadow: 0px 0px 20px 0px #12003050;
}
.typo1{
    margin-left: 10px;
    font-family: Outfit;
    font-size: 28px;
    color: #120030;
  }
  .sub{
    margin-left: 10px;
    color: #12003092;
    font-family: Outfit;
    font-size: 18px;
    margin-bottom: 10px;

  }
  </style>
<template>
  <div class="container">
    <respHeader title="Notation" icon1="mdi-bell" toPage="./notifResp"/>
  <v-row>
        <v-col>
          <v-text-field
    placeholder="trouver un stagiaires.."
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
      <notationModel v-if="showNotationModel"
      :selectedItem="selectedItem"
      @click:outside="closeNotatinModel"/>
  <h2 class="typo1">Liste Des stagiaires</h2>
  <p class="sub">Selectioner un stagiaire pour le noter </p>
      <v-simple-table class="tablePresence overflow-auto" style="border-radius: 10px;">
    <template v-slot:default>
      <thead style="background-color: #63d69371;" class="headerTable">
        <tr>
          <th class="text-center">
            Nom
          </th>
          <th class="text-center">
            Prenom
          </th>
          <th class="text-center">
            Specialite
          </th>
          <th class="text-center">
            Email
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="stagiaire in filteredStagiaires"
          :key="stagiaire.id"
          class="text-center "
          @click="showNotatinModelFunc(stagiaire)"
          
          >
              
          <td>{{ stagiaire.nom_Etud }}</td>
          <td>{{ stagiaire.pre_Etud}}</td>
          <td>{{ stagiaire.specialite}}</td>
          <td>{{ stagiaire.email}}</td>
        </tr>
      </tbody>
      
    </template>

  </v-simple-table>
  <v-divider class="my-5"></v-divider>
  <p class="sub">Liste des stagiaires déjà notés</p>
  <v-simple-table class="tablePresence overflow-auto" style="border-radius: 10px;">
    <template v-slot:default>
      <thead style="background-color: #63d69371;" class="headerTable">
        <tr>
          <th class="text-center">
            Nom
          </th>
          <th class="text-center">
            Prenom
          </th>
          <th class="text-center">
            Specialite
          </th>
          <th class="text-center">
            Email
          </th>
          <th class="text-center">
            Note
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="stagiaire in stagiaireDejaNoter"
          :key="stagiaire.id"
          class="text-center"
          @click="attestPrep(stagiaire)">
              
          <td>{{ stagiaire.nom_Etud }}</td>
          <td>{{ stagiaire.pre_Etud}}</td>
          <td>{{ stagiaire.specialite}}</td>
          <td>{{ stagiaire.email}}</td>
          <td>{{ stagiaire.noteTotale}}</td>
        </tr>
      </tbody>
      
    </template>

  </v-simple-table>
  
  </div>
</template>

<script>
import api from "@/api";
import notationModel from "@/components/notationModel.vue";
import respHeader from "@/components/respHeader.vue";
export default {
  name:'notationResp',
  components:{
    notationModel,
    respHeader,
  },
  data(){
    return{
      searchTerm:'',
      showNotationModel:false,
      noterStagiaires:[],
      stagiaireDejaNoter:[],
      selectedItem: null,
    }
  },
  computed: {
  user() {
      return JSON.parse(localStorage.getItem('user'));
    },
    filteredStagiaires() {
  if (this.searchTerm === '') {
    return this.noterStagiaires;
  } else {
    return this.noterStagiaires.filter((stagiaire) => {
      return Object.values(stagiaire).some(
        (val) =>
          val !== null &&
          typeof val === 'string' &&
          val.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
    });
  }
},
  },
  mounted(){
    this.getListStagiaire();
    this.getListStagiaireNoter();
  },
  methods:{
    showNotatinModelFunc(stagiaire){
      this.showNotationModel=!this.showNotationModel
      this.selectedItem=stagiaire
    },
    async attestPrep(stagiaire){
      try {
        console.log(stagiaire)
    const response = await api.post("/genererPdf", { id_Etud: stagiaire.id_Etud , id_Stage:stagiaire.id_Stage}, {responseType: 'blob'});
    const file = new Blob([response.data], { type: 'application/pdf' });
    const fileURL = URL.createObjectURL(file);
    
    const printWindow = window.open(fileURL);
    
    printWindow.onload = function() {
      printWindow.print();
      printWindow.onafterprint = function() {
        printWindow.close();
      };
    };
    
    this.msg2 = "L'attestation a été envoyée";
    this.showAlertCreateModel = !this.showAlertCreateModel;
  } catch (error) {
    console.log(error);
  }
    },
    async getListStagiaire(){
      try{
       await api.post('/listStagiairesPasNoter', {id:this.user.id_Resp})
      .then(response => {
        console.log(response)
        this.noterStagiaires = response.data;
      })
    }
    catch(error) {
        console.log(error);
      }
    },
    async getListStagiaireNoter(){
      try{
       await api.post('/listStagiairesNote', {id:this.user.id_Resp})
      .then(response => {
        console.log(response)
        this.stagiaireDejaNoter = response.data;
      })
    }
    catch(error) {
        console.log(error);
      }
    }
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
    margin-bottom: 20px;
  }
  .tablePresence{
    border-radius: 10px;
    border: 1px solid #120030;

  }
</style>
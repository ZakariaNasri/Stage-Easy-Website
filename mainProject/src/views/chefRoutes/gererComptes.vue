<template>
    <div class="container">

      <showProfil v-if="showInfoEtudModel" 
        :infos="infoCompte" 
        :type="typeCompte"/>


      <chefHeader title="Gerer Les Comptes" icon1="mdi-bell" toPage="./notificationChef"/>
      <v-row style="margin-bottom: 10px;">
        <v-col>
          <v-text-field
    placeholder="trouver un etudiant.."
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
    <v-container grid-list-xs>
        <h2 class="header2">Liste Des Etudiants</h2>
      <v-simple-table class="tablePresence overflow-auto" style="border-radius: 10px; height: 400px;">
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
            numero de carte
          </th>
          <th class="text-center">
            specialite
          </th>
          <th class="text-center">
            Email
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          
          v-for="etudiant in filteredStudents"
          :key="etudiant.numCarte"
          class="text-center "
          @click="showInfoEtud(etudiant.id_Etud)"
          >
          <td>{{ etudiant.nom_Etud }}</td>
          <td>{{ etudiant.pre_Etud }}</td>
          <td>{{ etudiant.numCarte }}</td>
          <td>{{ etudiant.specialite }}</td>
          <td>{{ etudiant.email }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
  <v-divider class="my-5"></v-divider>
  <h2 class="header2">Liste Des Responsables</h2>
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
            Email
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          
          v-for="responsable in responsables"
          :key="responsable.id"
          @click="showInfoResp(responsable.id_Resp)"
          class="text-center ">

          <td>{{ responsable.nom_Resp }}</td>
          <td>{{ responsable.pre_Resp }}</td>
          <td>{{ responsable.email }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
    </v-container>
</div>
</template>

<script>
import api from "@/api";
import chefHeader from "@/components/chefHeader.vue";
import showProfil from "@/components/showProfil.vue";
export default {
  components:{
    chefHeader,
    showProfil,
  },
    data(){
        return{
            etudiants:[],
            responsables:[],
            searchTerm:'',
            typeCompte:'',
            showInfoEtudModel:false,
            infoCompte:{}
        }
    },
    methods:{
      async listEtud(){
  try{
    await api.get('/listEtud' , {id:this.user.id_ChefDep})
      .then(response => {
        this.etudiants=response.data
      })
  }catch(error) {
        console.log(error)
      }
    },
      async listResp(){
  try{
    await api.get('/listResp' , {id:this.user.id_ChefDep})
      .then(response => {
        this.responsables=response.data
      })
  }catch(error) {
        console.log(error)
      }
    },

    async showInfoEtud(idEtud){
      try{
      await api.post('/InfoEtuds' , {id:idEtud})
      .then(response => {
        this.infoCompte=response.data[0]
        this.showInfoEtudModel=!this.showInfoEtudModel
        this.typeCompte='etudiant'
        console.log(this.typeCompte)
      })
  }catch(error) {
        console.log(error)
      }
    },
    async showInfoResp(idResp){
      try{
      await api.post('/InfoResp' , {id:idResp})
      .then(response => {
        this.infoCompte=response.data[0]
        this.showInfoEtudModel=!this.showInfoEtudModel
        this.typeCompte='responsable'
        console.log(this.typeCompte)
      })
  }catch(error) {
        console.log(error)
      }
    }
  },

    async mounted(){
    await this.listEtud();
    await this.listResp();
  },
    computed: {
      user() {
      return JSON.parse(localStorage.getItem('user'));
    },
    filteredStudents() {
  if (this.searchTerm === '') {
    return this.etudiants;
  } else {
    return this.etudiants.filter((etudiant) => {
      return Object.values(etudiant).some(
        (val) =>
          val !== null &&
          typeof val === 'string' &&
          val.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
    });
  }
},

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
.header2{
    font-size: 30px;
    margin-bottom: 10px;
    font-family:'Outfit';
    color: #120030;
}
  .tablePresence{
    border-radius: 10px;
    border: 1px solid #120030;
  }
.router-link{
    text-decoration: none;
}

</style>
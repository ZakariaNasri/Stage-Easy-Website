<template>
  <div class="container">
    <chefHeader title="Liste Des Stagiaires" icon1="mdi-bell" toPage="./notificationChef"/>
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

      <emptyArrays v-if="stagiaires.length === 0" 
      message="Aucun stagiaire ..."
      :source="imagePath"
      />
    <v-container grid-list-xs v-if="stagiaires.length !== 0" >
        <h2 class="header2 ml-3">Liste Des stagiaires</h2>
        <p class="sub">vous pouver trouver toutes les stagiaires dans ce tableau</p>
      <v-simple-table class="tablePresence overflow-auto" style="border-radius: 10px; max-height: 400px;">
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
          
          v-for="stagiaire in filteredStagiaires"
          :key="stagiaire.id"
          class="text-center ">

          <td>{{ stagiaire.nom_Etud }}</td>
          <td>{{ stagiaire.pre_Etud}}</td>
          <td>{{ stagiaire.specialite}}</td>
          <td>{{ stagiaire.email}}</td>
          <td>{{ stagiaire.noteTotale}}</td>
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
import EmptyArrays from '@/components/emptyArrays.vue';
export default {
  components:{
    chefHeader,
    EmptyArrays,
  },
data(){
  return{
    searchTerm:'',
    stagiaires:[],
    imagePath: require('@/assets/empty/1.png'),
  }
},
computed: {
    filteredStagiaires() {
  if (this.searchTerm === '') {
    return this.stagiaires;
  } else {
    return this.stagiaires.filter((stagiaire) => {
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
    this.getStagiaires();
  },
  methods:{
    async getStagiaires(){
      try{
      await api.get('/listStagiaire' )
      .then(response => {
        this.stagiaires=response.data
      })
  }catch(error) {
        console.log(error)
      }
    }
  }
}
</script>

<style scoped>

.header{
  font-size: 60px;
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
  
.router-link{
    text-decoration: none;
}

</style>
<template>
  <div class="container">
    <v-container grid-list-xs style="margin-bottom: 50px;">

      <stdHeader title="Stage Courant" icon1="mdi-bell" toPage="./notificationStd"/>

  
      <v-row>  <h1 class="header ml-5" style="font-size: 40px;">{{ notation.theme }}</h1></v-row>
        <h1 class="ml-3 mt-2" style="font-size: 20px;color: #63D693;font-weight: 400;">{{ notation.nom_Resp }} {{  notation.pre_Resp }}</h1>
        <h3 class="header2 my-5 ml-2">Table de notation</h3>
        <p class="sub">Vous pouvez trouver vos notes sur cette table </p>
        
        <emptyArrays v-if="notation.length === 0 && presence.length === 0" 
      message="Aucun Note disponible"
      :source="imagePath"
      />  
        <v-container v-if="notation.length !== 0 || presence.length !== 0" grid-list-xs style="border: 1px solid #120030; border-radius: 15px;">
          <v-row>
            <v-col md="8" class="aptitudes">
              <!-- Line 0 -->
              <v-row class="headerTable" justify="center" style="border-radius: 15px 0 0 0;"><h2 class="typo1">Aptitudes</h2></v-row>
              <!-- Line1 -->
              <v-row><h2 class="typo1">Discipline</h2></v-row>
              <v-row><h4 class="typo2">générale et relations humaines</h4></v-row>
              <br>
              <v-divider class="mb-3"></v-divider>
              <!-- Line2 -->
              <v-row><h2 class="typo1">Aptitudes</h2></v-row>
              <v-row><h4 class="typo2">au travail et à la manipulation</h4></v-row>
              <br>
              <v-divider class="mb-3"></v-divider>
              <!-- Line3 -->
              <v-row><h2 class="typo1">Initiative</h2></v-row>
              <v-row><h4 class="typo2">Initiative / entreprenariat</h4></v-row>
              <br>
              <v-divider class="mb-3"></v-divider>
              <!-- Line4 -->
              <v-row><h2 class="typo1">Capacités</h2></v-row>
              <v-row><h4 class="typo2">d'imagination et d'innovation</h4></v-row>
              <br>
              <v-divider class="mb-3"></v-divider>
              <!-- Line5 -->
              <v-row><h2 class="typo1">Connaissances</h2></v-row>
              <v-row><h4 class="typo2 mb-3">acquises sur le terrain de stage</h4></v-row>
            </v-col>
            <v-col md="4" class="notes">
              <v-row class="headerTable" justify="center" style="border-radius: 0 15px 0 0;"><h2 class="typo1">Notes</h2></v-row>
              <!-- Line1 -->
              <v-row justify="center"><h2 class="typo1 my-3">{{ notation.discipline }} <span style="color: rgba(0, 0, 0, 0.267);">/5</span></h2></v-row>
              <br>
              <v-divider class="mb-3"></v-divider>
              <!-- Line2 -->
              <v-row justify="center"><h2 class="typo1 my-3">{{ notation.attitude }} <span style="color: rgba(0, 0, 0, 0.267);">/5</span></h2></v-row>
              <br>
              <v-divider class="mb-3"></v-divider>
              <!-- Line3 -->
              <v-row justify="center"><h2 class="typo1 my-3">{{ notation.initiative }} <span style="color: rgba(0, 0, 0, 0.267);">/5</span></h2></v-row>
              <br>
              <v-divider class="mb-3"></v-divider>
              <!-- Line4 -->
              <v-row justify="center"><h2 class="typo1 my-3">{{ notation.capacite }} <span style="color: rgba(0, 0, 0, 0.267);">/5</span></h2></v-row>
              <br>
              <v-divider class="mb-3"></v-divider>
              <!-- Line5 -->
              <v-row justify="center"><h2 class="typo1 my-3 mb-3">{{ notation.connaissance }} <span style="color: rgba(0, 0, 0, 0.267);">/5</span></h2></v-row>
              
              
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <!-- Line 0 -->
              <v-row class="headerTable" justify="center"><h2 class="typo1">Note Totale</h2></v-row>
            </v-col>
          </v-row>
          <v-row justify="center"><h2 class="typo3 my-3">{{ notation.noteTotale }} <span style="color: rgba(0, 0, 0, 0.267);">/20</span></h2></v-row>
          <v-divider></v-divider>
          <v-row justify="center"><h2 class="typo1 my-3">{{ evaluation() }}</h2></v-row>
          </v-container>

    </v-container>
    <v-container grid-list-xs >
      <h3 class="header2 my-5 ml-2">Table de Presence</h3>
    <p class="sub">Vous pouvez trouver voter presence dans le stage courant dans ce tableau</p>
      <v-simple-table class="tablePresence" style="border-radius: 10px;">
    <template v-slot:default>
      <thead style="background-color: #63d69371;" class="typo1">
        <tr>
          <th class="text-center">
            Date
          </th>
          <th class="text-center">
            Heure d'entree
          </th>
          <th class="text-center">
            Heure de sortie
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="pres in presence"
          :key="pres.id"
          class="text-center"
        >
          <td>{{ pres.datePresence }}</td>
          <td>{{ pres.heureEntr }}</td>
          <td>{{ pres.heureSort }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
    </v-container>
  </div>
</template>

<script>
import api from "@/api";
import stdHeader from "@/components/stdHeader.vue";
import EmptyArrays from '@/components/emptyArrays.vue';
export default {
    components:{
      stdHeader,
        EmptyArrays,
    },
  data(){
    return{
      stageTitle:'Data Science',
      respon:'Yamen Houfani',
      notation:[],
      presence:[],
      imagePath: require('@/assets/empty/3.png'),
    }
  },
  methods:{
    evaluation(){
      if (this.notation.noteTotale>16) {
        return 'Excellent !'
      }else if (this.notation.noteTotale>12 && this.notation.noteTotale<=16) {
        return 'Bon !'
      }else if (this.notation.noteTotale>=10 && this.notation.noteTotale<=12) {
        return 'Moyen !'
      }else if (this.notation.noteTotale>5 && this.notation.noteTotale<10) {
        return 'Insuffisant !'
      }else if (this.notation.noteTotale<5) {
        return 'Très Faible !'
      }
    },
    async getNotes(){
      try{
       await api.post('/consulterNotes', {id:this.user.id_Etud})
      .then(response => {
        console.log(response)
        this.notation = response.data[0];
      })
    }
    catch(error) {
        console.log(error);
      }
    },
    async getPresence(){
      try{
       await api.post('/consulterPresence', {id:this.user.id_Etud})
      .then(response => {
        console.log(response)
        this.presence = response.data;
      })
    }
    catch(error) {
        console.log(error);
      }
    }
  },
  computed:{
  user() {
      return JSON.parse(localStorage.getItem('user'));
    }
  },
  mounted(){
    this.getNotes();
    this.getPresence();
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
  .notesContainer{
    width: 45%;
    height: 50%;
    border-radius: 15px;
    border: 2px solid #120030;
  }
  .aptitudes{
    border-right: 2px solid #120030;
  }
  .headerTable{
    background-color: #63d69371;
  }
  .typo1{
    margin-left: 10px;
    font-family: Outfit;
    font-size: 28px;
    color: #120030;
  }
  .typo2{
    margin-left: 10px;
    color: #12003092;
    font-family: Outfit;
    font-size: 17px;
    font-weight: 300;
  }
  .typo3{
    margin-left: 10px;
    font-family: Outfit;
    font-size: 40px;
    color: #120030;
  }
  .tablePresence{
    border-radius: 10px;
    border: 1px solid #120030;
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
</style>
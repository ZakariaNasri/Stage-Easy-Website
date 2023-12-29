<template>
  <div class="container">

    <stdHeader title="Profile" icon1="mdi-bell" toPage="./notificationStd"/>

    <authModel 
    v-if="showAuthModel"
    header="Entrer Votre Mot De Passe"
    :infoEtud="infoEtud"
    :nvMdps="nvMdps"
    />

    <v-container grid-list-xs class="text-center">

      <v-row>
        <v-col md="3" style="background-color: white; border-radius: 20px;">
          <v-avatar class="profilPic align-self-center mb-3" tile size="250">
        <img src="@/assets/StudentProfilePic.jpg" alt="" style="border-radius: 20px;">
      </v-avatar>
      <h3 class="infos">{{user.nom_Etud}} {{user.pre_Etud}}</h3>
      <h2 style="  font-family: Outfit; font-size: 20px;font-weight: 400; color: #63D693;">Etudiant</h2>
      <v-divider class="my-4"></v-divider>
      
      <div class="leftInfo">
        <h1 class="leftInfo"><v-icon style="color: #63D693;">mdi-certificate</v-icon> spécialité : {{ user.specialite }}</h1>
        <h1 class="leftInfo"><v-icon style="color: #63D693;">mdi-school</v-icon> Diplome : {{ user.diplome }}</h1>
      </div>
        <v-row class="mt-3 editContainer">
          <v-col class="editText">Editer</v-col>
          <v-col>
            <button class="toggle-button text-center mr-0" :class="{ 'on': isOn }"  @click="allowEdit">
          <div class="toggle-button__icon" >
          </div>
        </button>
          </v-col>
        </v-row>

        <v-layout row wrap class="justify-center mt-4" v-show="editOn">
        <v-btn class="mr-3" @click="resetInfo">Reset</v-btn>
    <v-btn color="#63D693" @click="showAuthModel=!showAuthModel">Sauvgarder</v-btn>
    </v-layout>

        </v-col>

        <v-spacer></v-spacer>
        <v-col md="8"  style="background-color: white; border-radius: 20px;">
          <h1 style="font-family: Outfit;
                    font-size: 30px;
                    color: #120030;
                    margin-bottom: 10px;
          ">Vos informations</h1>

          <!--informations Personnelles -->
          <h2 class="leftInfo" style="margin-right: 500px;" >informations Personnelles</h2>
          <v-divider></v-divider>
          <v-row class="mt-5">
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%;">Nom</h3>
          <v-text-field
            v-model="infoEtud.nom_Etud"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.nom_Etud"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%;">Prenom</h3>
          <v-text-field
            v-model="infoEtud.pre_Etud"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.pre_Etud"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%; lin">Datedenaissance</h3>
          <v-text-field
            v-model="infoEtud.dateNaiss"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.dateNaiss"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%; lin">Lieudenaissance</h3>
          <v-text-field
            v-model="infoEtud.lieuNaiss"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.lieuNaiss"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%; lin">NumTelephone</h3>
          <v-text-field
            v-model="infoEtud.telEtud"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.telEtud"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%; lin">MON CV</h3>
            
          <v-file-input
            v-model="cv"
            class="elevation-0"
            :value="cv"
            :disabled="!disable"
            @change="uploadCV"
            accept="application/pdf"
          ></v-file-input>
          </div>
        </v-col>
      </v-row>
      <!--informations Univ -->

      <h2 class="leftInfo" style="margin-right: 500px;" >informations d'université</h2>
          <v-divider></v-divider>
          <v-row class="mt-5">
            <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%; lin">Departement</h3>
          <v-text-field
            v-model="infoEtud.nom_Dep"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.nom_Dep"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%;">num Carte</h3>
          <v-text-field
            v-model="infoEtud.numCarte"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.numCarte"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%;">spécialité</h3>
          <v-text-field
            v-model="infoEtud.specialite"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.specialite"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%; lin">Diplome</h3>
          <v-text-field
            v-model="infoEtud.diplome"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.diplome"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
      </v-row>
      <!--informations D'utilisateur -->

      <h2 class="leftInfo" style="margin-right: 500px;" >informations d'utilisateur</h2>
          <v-divider></v-divider>
          <v-row class="mt-5">
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%;">Email</h3>
          <v-text-field
            v-model="infoEtud.email"
            solo
            outlined
            class="elevation-0"
            :value="infoEtud.email"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%;">MotDePasse</h3>
          <v-text-field
            v-model="nvMdps"
            solo
            outlined
            class="elevation-0"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
      </v-row>
        </v-col>
      </v-row>

    </v-container>
  </div>
</template>

<script>
  import api from '@/api';
  import authModel from "@/components/authModel.vue";
  import stdHeader from "@/components/stdHeader.vue";
export default {
  components:{
    authModel,
    stdHeader,
  },
  data(){
    return{
      infoEtud:{},
      isOn:false,
      editOn:false,
      disable:false,
      nvMdps:null,
      msg:'',
      cv:null,
      showAlertCreateModel:false,
      showAuthModel:false
    }
  },
  computed:{
    user() {
      return JSON.parse(localStorage.getItem('user'));
    }
  },
async mounted(){
  await this.getProfilInfo()
  console.log(this.infoEtud)
  },
  methods:{
    
  //   async saveInfo(){
  //     console.log(this.infoEtud.nom_Etud)
  //     try{
  //   await api.post('/updateInfoEtud', {
  //     id:this.user.id_Etud,
  //     currentMdps:'0000',
  //     nom: this.infoEtud.nom_Etud,
  //     prenom: this.infoEtud.pre_Etud,
  //     email: this.infoEtud.email,
  //     nvMdps: this.nvMdps,
  //     dateN: this.infoEtud.dateNaiss,
  //     lieuN: this.infoEtud.lieuNaiss,
  //     telE: this.infoEtud.telEtud,
  //     numCarte: this.infoEtud.numCarte,
  //     diplome: this.infoEtud.diplome,
  //     specialite: this.infoEtud.specialite,
  //     })
  //     .then(response => {
  //       console.log(response)
  //       this.msg = response.data.msg;
  //       this.showAlertCreateModel=!this.showAlertCreateModel
  //       this.disable=!this.disable
  //     })
  // }catch(error) {
  //   console.log(this.infoEtud.nom_Etud)
  //   console.log(this.nvMdps)
  //       console.log(error)
  //     }
  //   },

  async uploadCV() {
        try {
          
          console.log(this.cv)
          await api.post('/uploadCv', {
            id_Etud:this.user.id_Etud,
            cv: this.cv,
            
          },{
            headers:{
              "Content-Type":"multipart/form-data"
            },
          }
          )
            .then(response => {
                console.log(response)
            })
        } catch (error) {
          console.log(error);
        }
      },
    allowEdit() {
      this.isOn = !this.isOn
      this.disable=!this.disable
      this.editOn=!this.editOn
      // You can also emit an event or make an API call to update the dark mode state here
    },
    resetInfo(){
      location.reload();
    },
    async getProfilInfo(){
      try{
      await api.post('/InfoEtud', {id:this.user.id_Etud})
      .then(response => {
        this.infoEtud = response.data[0];
      })
    }
    catch(error) {
        console.log(error);
      }
    }
  }
}
;
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
.profilPic {
  border-radius: 20%;
}
.infos{
  font-family: Outfit;
  font-size: 30px;
  color: #120030;
  font-weight: 600;
}
.leftInfo{
  font-family: Outfit;
  color: #120030;
  font-size: 20px;
}
.field h3 {
  color: #63D693;
  font-family: Outfit;
  font-size: 15px;
}
.toggle-button {
  align-items: center;
  justify-content: left;
  width: 50px;
  height: 30px;
  border: none;
  border-radius: 15px;
  background-color: #4a4a4a;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
}

.toggle-button.on {
  background-color: #63D693;
}

.toggle-button__icon {
  width: 20px;
  height: 20px;
  margin-left: 5px;
  margin-right: 5px;
  border-radius: 50%;
  background-color: #fff;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s ease-in-out;
}

.toggle-button.on .toggle-button__icon {
  transform: translateX(20px);
}
.editText{
  font-family: Outfit;
  color: #120030;
  font-size: 20px;
}
.editContainer{
  margin: 0 auto ;
  width: 95%;
  background-color: #ddd;
  border-radius: 10px;
}
</style>

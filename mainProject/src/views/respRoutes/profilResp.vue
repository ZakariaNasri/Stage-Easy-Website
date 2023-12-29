<template>
  <div class="container">

    <authModelResp
    v-if="showAuthModel"
    header="Entrer Votre Mot De Passe"
    :infoEtud="infoResp"
    :nvMdps="nvMdps"
    />

    <respHeader style="margin-bottom: 50px;" title="Profile" icon1="mdi-bell" toPage="./notificationChef"/>

    <v-container grid-list-xs class="text-center mt-5">

      <v-row>
        <v-col md="3" style="background-color: white; border-radius: 20px;">
          <v-avatar class="profilPic align-self-center mb-3" tile size="250">
        <img src="@/assets/ResponsableProfilePic.jpg" alt="" style="border-radius: 20px;">
      </v-avatar>
      <h3 class="infos">{{user.nom_Resp}} {{user.pre_Resp}}</h3>
      <h2 style="  font-family: Outfit; font-size: 20px;font-weight: 400; color: #63D693;">Etudiant</h2>
      <v-divider class="my-4"></v-divider>
      
      <div class="leftInfo">
        <h1 class="leftInfo"><v-icon style="color: #63D693;">mdi-domain</v-icon> Entreprise : {{ infoResp.nom_Entreprise }}</h1>
        <h1 class="leftInfo"><v-icon style="color: #63D693;">mdi-phone</v-icon> phone : {{ infoResp.telEntreprise }}</h1>
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
            v-model="infoResp.nom_Resp"
            solo
            outlined
            class="elevation-0"
            :value="infoResp.nom_Resp"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%;">Prenom</h3>
          <v-text-field
            v-model="infoResp.pre_Resp"
            solo
            outlined
            class="elevation-0"
            :value="infoResp.pre_Resp"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
      </v-row>
      <!--informations Univ -->

      <h2 class="leftInfo" style="margin-right: 500px;" >informations d'entreprise</h2>
          <v-divider></v-divider>
          <v-row class="mt-5">
        <v-col cols="12" md="4">
          <div class="field">
            <h3 style="padding-right: 80%;">Nom</h3>
          <v-text-field
          v-model="infoResp.nom_Entreprise"
            solo
            outlined
            class="elevation-0"
            :value="infoResp.nom_Entreprise"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="4">
          <div class="field">
            <h3 style="padding-right: 80%;">telephone</h3>
          <v-text-field
            v-model="infoResp.telEntreprise"
            solo
            outlined
            class="elevation-0"
            :value="infoResp.telEntreprise"
            :disabled="!disable"
          ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="4">
          <div class="field">
            <h3 style="padding-right: 80%;">adresse</h3>
          <v-text-field
            v-model="infoResp.addr_Entreprise"
            solo
            outlined
            class="elevation-0"
            :value="infoResp.addr_Entreprise"
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
            v-model="infoResp.email"
            solo
            outlined
            class="elevation-0"
            :value="infoResp.email"
            disabled
          ></v-text-field>
          </div>
        </v-col>

        <v-col cols="12" md="6">
          <div class="field">
            <h3 style="padding-right: 80%;">MotDePasse</h3>
          <v-text-field
            type="password"
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
  import api from '@/api.js';
  import authModelResp from "@/components/authModelResp.vue";
  import respHeader from '@/components/respHeader.vue';
export default {
  components:{
    authModelResp,
    respHeader,
  },
  data(){
    return{
      infoResp:{},
      showAuthModel:false,
      isOn:false,
      editOn:false,
      nvMdps:null,
      disable:false
    }
  },
  computed:{
    user() {
      return JSON.parse(localStorage.getItem('user'));
    }
  },
async mounted(){
  await this.getProfilInfo()
  },
  methods:{
    async getProfilInfo(){
      try{
      await api.post('/InfoRespR',{id:this.user.id_Resp})
      .then(response => {
        this.infoResp = response.data[0];
      })
    }
    catch(error) {
        console.log(error);
      }
    },
    allowEdit() {
      this.isOn = !this.isOn
      this.disable=!this.disable
      this.editOn=!this.editOn
    },
    resetInfo(){
      location.reload();
    },
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

<template>

        <v-dialog
            v-model="dialog"
            width="1200px"
            
        >
        <authModelChef
      v-if="showAuthModel"
      header="Entrer Votre Mot De Passe"
      :infoEtud="infos"
      :nvMdps="nvMdps"
      />
  
  
      <v-container grid-list-xs class="text-center" >
        <v-row >
          <v-col md="3" style="background-color: white;">
            <v-avatar class="profilPic align-self-center mb-3" style="margin-top: 20px;" tile size="250">
          <img v-show="!imgType" src="@/assets/ResponsableProfilePic.jpg" alt="" style="border-radius: 20px;">
          <img v-show="imgType" src="@/assets/StudentProfilePic.jpg" alt="" style="border-radius: 20px;">
        </v-avatar>
        <h3 class="infos"></h3>
        <h1>{{ infos.nom_Etud }} {{ infos.pre_Etud }}{{ infos.nom_Resp }} {{ infos.pre_Resp }}</h1>
        <h2 v-show="imgType" style=" font-family: Outfit; font-size: 20px;font-weight: 400; color: #63D693;">Etudiant</h2>
        <h2 v-show="!imgType" style=" font-family: Outfit; font-size: 20px;font-weight: 400; color: #63D693;">Responsable</h2>
        <v-divider class="my-4"></v-divider>
        
        <div class="leftInfo">
          <!-- <h1 class="leftInfo"><v-icon style="color: #63D693;">mdi-school</v-icon> Specialite : {{ infoChef.nom_Fac }}</h1>
          <h1 class="leftInfo"><v-icon style="color: #63D693;">mdi-briefcase</v-icon> Diplome : {{ infoChef.nom_Dep }}</h1> -->
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
          <v-col md="9" class="rightCol"  style="background-color: white;">
            <div class="close" @click="closeProfil">
                <v-icon dark class="mt-1">mdi-close</v-icon>
            </div>
            <h1 style="font-family: Outfit;
                      font-size: 30px;
                      color: #120030;
                      margin-bottom: 10px;
            ">{{ infos.nom_Etud }} {{ infos.pre_Etud }}{{ infos.nom_Resp }} {{ infos.pre_Resp }}</h1>
  
            <v-divider></v-divider>
            <v-row class="mt-5">
          <v-col cols="12" md="4" v-for="(info,index) in infos" :key="index" >
            <div class="field">
              <h3 style="padding-right: 80%;">{{ index }}</h3>
            <v-text-field
              outlined
              dense
              class="elevation-0"
              :value="infos[index]"
              :disabled="!disable"
            ></v-text-field>
            </div>
          </v-col>
        </v-row>
          </v-col>
        </v-row>
  
      </v-container>
        
        </v-dialog>
      
  </template>
  
  <script>
    import authModelChef from "@/components/authModelChef.vue";
  export default {
    components:{
      authModelChef,
    },

    props:['infos','type'],
    data(){
      return{
        dialog:true,
        infoChef:{},
        showAuthModel:false,
        isOn:false,
        editOn:false,
        nvMdps:null,
        disable:false,
        imgType:false,
      }
    },
    methods:{
        allowEdit(){
            this.isOn=!this.isOn
            this.editOn=!this.editOn
            this.disable=!this.disable
        },
        closeProfil(){
            this.dialog=!this.dialog
        },
        typeCompte(){
            if (this.type==='etudiant') {
                this.imgType=true
            }else this.imgType=false
        }
    },
    mounted(){
        this.typeCompte();
        
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
  .rightCol{
    position: relative;
  }
  .close{
    cursor: pointer;
    position: absolute;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    background: #63D693;
    right: 5px;
    top: 5px;
  }
  </style>
  
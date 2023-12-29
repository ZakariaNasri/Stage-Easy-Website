<template>
    <div class="Holder">

      <alertCreateAccount
      header="Creer!"
      :msg="msg"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>

      <v-row class="my-0">
        <v-col cols="12" md="5" class="d-flex left-col pb-0" style="position: relative;">
            <img class="logo" src="../assets/e.png" alt="">
            <p class="logoName">stageasy.</p>
            <div class="left-text-holder">
                <h1 class="left-header mb-5">Bienvenue à</h1>
                <v-img class="mb-5" style="height: 100px; width: 500px;" src="../assets/whte.png" alt=""></v-img>
                <p style="color: white; font-weight: 300;">Bienvenue sur "Stage Easy", la plateforme ultime conçue pour rendre la scène universitaire facile pour
              tous les acteurs impliqués - étudiants, chefs et directeurs de stage. Notre site web est dédié à fournir des
              ressources et des outils complets pour vous aider à tirer le meilleur parti de votre temps dans l'université.
              <br><br>
              Chez "Stage Easy", nous comprenons que la scène universitaire peut être difficile et écrasante, surtout pour les
              nouveaux venus. C'est pourquoi nous avons créé ce site web où vous pouvez trouver tout ce dont vous avez besoin
              pour réussir pendant votre stage universitaire.
              </p>
                <v-btn class="gobackBtn" rounded color="white" @click="goBack">
                    <v-icon>mdi-arrow-left</v-icon>
                      go back
                </v-btn>
            </div>

        </v-col>


        <v-col style="height: 100vh !important; background-color: #1200302d;" cols="12" md="7" class="d-flex justify-center align-center pb-0">
          <p style="position: absolute; top: 15px; right: 25px;">Avez vous déjà un compte ? <a href="/">se connecter</a></p>
          <v-stepper v-model="e1" style="width: 60% !important; height: 80%; border-radius: 20px;">
            <v-stepper-header>
              <template v-for="n in steps">
                <v-stepper-step
                  color="#63D693"
                  :key="`${n}-step`"
                  :complete="e1 > n"
                  :step="n"
                  editable
                >
                  Step {{ n }}
                </v-stepper-step>
  
                <v-divider
                  v-if="n !== steps"
                  :key="n"
                ></v-divider>
              </template>
            </v-stepper-header>
  
            <v-stepper-items>
              <v-stepper-content
                v-for="n in steps"
                :key="`${n}-content`"
                :step="n"
              >

                <!-- Presonal Infoo -->

                <v-card
                  class="mb-12 text-center"
                  height="390px"
                  elevation="0"
                  v-if="n===1"
                  style="height: 100% !important;"
                >
                <h1 class="headings">Informations personnelles</h1>
                
                <v-row>
  <v-col cols="12" md="6">
    <v-text-field
      v-model="nom_Etud" 
      color="#63D693"
      label="Nom"
      id="id"
      :rules="nomEtudRules"
    ></v-text-field>
  </v-col>
  <v-col cols="12" md="6">
    <v-text-field
      v-model="pre_Etud" 
      color="#63D693"
      label="Prénom"
      id="id"
      :rules="preEtudRules"
    ></v-text-field>
  </v-col>
</v-row>

<v-row>
  <v-col>
    <v-menu
      ref="menu"
      v-model="menu3"
      color="#63D693"
      :close-on-content-click="false"
      :return-value.sync="date"
      transition="scale-transition"
      offset-y
      min-width="auto"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-text-field
          v-model="dateN"
          :error-messages="dateNErrors"
          label="Date de naissance"
          readonly
          color="#63D693"
          v-bind="attrs"
          v-on="on"
          @input="$v.dateN.$touch()"
          @blur="$v.dateN.$touch()"
        ></v-text-field>
      </template>
      <v-date-picker
        v-model="dateN"
        no-title
        color="#63D693"
        scrollable
      >
        <v-spacer></v-spacer>
        <v-btn
          text
          color="#63D693"
          @click="menu3 = false"
        >
          Cancel
        </v-btn>
        <v-btn
          text
          color="#63D693"
          @click="$refs.menu3.save(dateNaiss)"
        >
          OK
        </v-btn>
      </v-date-picker>
    </v-menu>
  </v-col>        
  <v-col cols="12" md="6">
    <v-text-field
      v-model="lieuN" 
      color="#63D693"
      label="Lieu de naissance"
      id="id"
      :rules="lieuNRules"
    ></v-text-field>
  </v-col>
</v-row>

<v-row>
  <v-col cols="12" md="6">
    <v-text-field
      v-model="telE" 
      color="#63D693"
      label="Téléphone"
      id="id"
      :rules="telERules"
    ></v-text-field>
  </v-col>
  <v-col cols="12" md="6">
    <v-text-field
      v-model="numCarte" 
      color="#63D693"
      label="Numéro de carte étudiant"
      id="id"
      :rules="numCarteRules"
    ></v-text-field>
  </v-col>
</v-row>

<v-row>
  <v-col cols="12" md="4">
    <v-text-field
      v-model="diplome"
      color="#63D693"
      label="Votre diplôme"
      id="id"
      :rules="diplomeRules"
    ></v-text-field>
  </v-col>
  <v-col cols="12" md="4">
    <v-text-field
      v-model="specialite"
      color="#63D693"
      label="Votre spécialité"
      id="id"
      :rules="specialiteRules"
    ></v-text-field>
  </v-col>
  <v-col cols="12" md="4">
    <v-text-field
      v-model="nom_Dep"
      color="#63D693"
      label="Votre département"
      id="id"
      :rules="nom_DepRules"
    ></v-text-field>
  </v-col>
</v-row>

            </v-card>

            <!-- Account info -->
            <v-card
                  class="mb-12 text-center"
                  height="390px"
                  elevation="0"
                  v-if="n===2"
                >
                <h1 class="headings">Inforamations de compte</h1>
                <v-row class="pt-5 mt-5">
      <v-col cols="12" md="12">
        <v-text-field
          v-model="email"
          label="Email"
          clearable
          :rules="emailRules"
          prepend-icon="mdi-email"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="12">
        <v-text-field
          v-model="mdps"
          label="Mot de passe"
          clearable
          :rules="passwordRules"
          type="password"
          prepend-icon="mdi-lock"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="12">
        <v-text-field
          v-model="confirmPassword"
          label="Confirme Mot de passe"
          clearable
          :rules="confirmPasswordRules"
          type="password"
          prepend-icon="mdi-lock-check"
        ></v-text-field>
      </v-col>
    </v-row>

            </v-card>
            <div style="float: right !important; margin-bottom: 5px;">
                <v-btn v-if="n===1" class="ml-2" rounded text>
                  Annuler
                </v-btn>

                <v-btn
                v-if="n===1"
                  color="#63D693"
                  rounded
                  @click="nextStep(n)"
                >
                  continue
                </v-btn>
            </div>
                
            <div style="float: right !important; margin-bottom: 5px;">
                <v-btn v-if="n===2" class="ml-2" rounded text>
                  Annuler
                </v-btn>
                <v-btn
                v-if="n===2"
                  color="#63D693"
                  rounded
                  @click="signUp"
                >
                  s'inscrire
                </v-btn>
            </div>
              </v-stepper-content>
            </v-stepper-items>
          </v-stepper>
        </v-col>
      </v-row>
    </div>
  </template>
  

<script>
import api from '@/api'
import AlertCreateAccount from './alertCreateAccount.vue';
export default {
  components: { AlertCreateAccount },
    data() {
        return {
          showAlertCreateModel:false,
          msg:'',
            e1: 1,
            steps: 2,
            menu3: false,
            dateN:'',
            lieuN:'',
            email: '',
            nom_Etud: '',
            pre_Etud: '',
            telE: '',
            numCarte: '',
            diplome: '',
            specialite: '',
            nom_Dep:'',
            mdps: '',
            confirmPassword: '',
            emailRules: [
              value => !!value || 'Email is required',
              value => /.+@.+\..+/.test(value) || 'Email must be valid',
            ],
            passwordRules: [
              value => !!value || 'Password is required',
              value => value.length >= 4 || 'Password must be at least 8 characters',
            ],

            nomEtudRules: [
      (v) => !!v || 'Le nom est requis',
    ],
    preEtudRules: [
      (v) => !!v || 'Le prénom est requis',
    ],
    lieuNRules: [
      (v) => !!v || 'Le lieu de naissance est requis',
    ],
    telERules: [
      (v) => !!v || 'Le numéro de téléphone est requis',
      (v) => /^\d+$/.test(v) || 'Veuillez entrer un numéro valide',
    ],
    numCarteRules: [
      (v) => !!v || 'Le numéro de carte étudiant est requis',
    ],
    diplomeRules: [
      (v) => !!v || 'Le diplôme est requis',
    ],
    specialiteRules: [
      (v) => !!v || 'La spécialité est requise',
    ],
    nom_DepRules: [
      (v) => !!v || 'Le département est requise',
    ],
        };
        
    },
    watch: {
        steps(val) {
            if (this.e1 > val) {
                this.e1 = val;
            }
        },
    },
    methods: {
        nextStep(n) {
            if (n === this.steps) {
                this.e1 = 1;
            }
            else {
                this.e1 = n + 1;
            }
        },
        async signUp() {
            try {
                console.log(this.nom_Etud)
                await api.post("/creeCompte", {
                  nom_Etud: this.nom_Etud,
                  dateN:this.dateN,
                  lieuN:this.lieuN,
                  email: this.email,
                  pre_Etud: this.pre_Etud,
                  telE: this.telE,
                  numCarte: this.numCarte,
                  diplome: this.diplome,
                  specialite: this.specialite,
                  nom_Dep:this.nom_Dep,
                  mdps: this.mdps,
                })
                    .then(response => {
                    console.log(response);
                      this.msg=response.data.msg
                    this.showAlertCreateModel=!this.showAlertCreateModel
                    setTimeout(() => {
                      this.$router.push('/');
                    }, 500);

                });
            }
            catch (error) {
                console.log(error);
            }
        },
        goBack() {
            this.$router.push("/");
        }
    },

}
</script>

<style scoped>
    .Holder {
  width: 100%;
  height: 100vh;
}
    .headings{
        font-family: Outfit;
        color: #3a3849;
    }
    .colors{
    color: #120030;
    color: #63D693;
  }
  .left-header{
    color: white;
    font-family: Outfit;
    font-size: 60px;
  }
  .left-text-holder{
    margin-top:100px;
    margin-left: 60px;
  }
  .left-col{
background-image: url(../assets/green.jpg);
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center; 
  }
  .logo{
    width: 40px;
    height: 40px;
    position: absolute;
    top: 10px;
    left: 30px;
  }
  .logoName{
    position: absolute;
    font-family: outfit;
    font-size: 20px;
    color: white;
    font-weight: 500;
    top: 15px;
    right: 20px;
  }
  .gobackBtn{
    position: absolute;
    bottom: 30px;
  }
</style>
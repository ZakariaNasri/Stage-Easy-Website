<template>
  <v-app>
    <!-- <navBar/> -->
    <checkAtt
    :infoAtt="infoAtt"
    v-if="showAttCheckModel"
          @close="showAttCheckModel = false"
          @click:outside="showAttCheckModel=false"/>
    <div class="welcome">
      <!-- <p class='welcomeTo' id="welcomeTo">Welcome to</p> -->
      <header>
        <nav class="nav">
          <img src="../assets/iconStageasy.png" class="mainLogo">
          <v-text-field
            v-model="tokenAtt"
            class="attCheck"
            name="name"
            label="code de l'attestation"
            dense
            outlined
            id="id"
          ></v-text-field>
          <v-btn class="checkBtn" @click="checkAtt" outlined small>Check</v-btn>

          <a class="navOption" href="#home">Home</a>
          <a class="navOption" href="#developers">Developers</a>
          <a class="navOption" href="#about">About</a>
          <a class="navOption" href="#contact">Contact</a>
        </nav>
    </header>
      <section class="stageasy">
        <img src="@/assets/NewWelcome/clouds1ph.png" id="clouds1">
        <img src="@/assets/NewWelcome/clouds2ph.png" id="clouds2">
        <img src="@/assets/NewWelcome/mountain1ph.png" id="mountain1">
        <img src="@/assets/NewWelcome/mountain2ph.png" id="mountain2">
        <p class="FirstTitle" id="FirstTitle">stag<span>e</span>asy.</p>
        <img src="@/assets/NewWelcome/mountain3ph.png" id="mountain3">
        <img src="@/assets/NewWelcome/mountain4ph.png" id="mountain4">
        <img src="@/assets/NewWelcome/facultyphblue.png" id="faculty">
        <img src="@/assets/NewWelcome/ground-01ph.png" id="ground">
      </section>
      
    </div>
    <!-- Accueil -->
    <div class="navBarReg" id="home"></div>
    <div class="loginM" style="z-index: 100 !important;">
      <loginModel v-if="showLoginModal"
                    @close="showLoginModal = false"
                    @click:outside="showLogin"
                    />
    </div>
    <section class="sec2" style="z-index: 90 !important; ">
    <div class="firstFather mb-8 mt-5">
      <v-layout row wrap class="firstPage">
        <v-flex lg6 md6 sm12 xs12>
          <h1 class="biggerh1"><span>P</span>asser</h1>
          <h1>Votre Stage</h1>
          <h1>Facilement</h1>
          <p>un site de département <span>IFA</span> pour faciliter<br>le passage d'un stage.</p>

          <v-layout row wrap class="mt-8">
            <v-btn text rounded lg2 class="btn1 mr-6"  @click="showLogin">Se connecter</v-btn>
              <v-btn text rounded lg2 @click="signUp" class="btn2">S'inscrire</v-btn>
          </v-layout>
        </v-flex>

        <v-flex lg6 md6 sm12 xs12>
          <v-img src="../assets/mainWebSitePic.png" contain max-height="500" class="mainImg"></v-img>
        </v-flex>
        
      </v-layout>

    </div>

    <!-- Developpeurs -->
    <div class="secondFather" id="developers">
      <h1 class="headings">Développeurs</h1>
      <v-container class="d-flex justify-center">
        <v-row>
          <v-col cols="12" md="6">
            <v-card class="mx-auto devCards" max-width="300" elevation="10" color="#63D693">
              <v-img src="../assets/male.png" height="300"></v-img>
              <v-card-title class="text-center cardName">Zakaria Nasri</v-card-title>
              <v-card-text class="text-center cardRole">Frontend developer/web designer</v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" md="6">
            <v-card class="mx-auto devCards" max-width="300" elevation="10" color="#63D693">
              <v-img src="../assets/female.png" height="300"></v-img>
              <v-card-title class="text-center cardName">Youssera Bousnobra</v-card-title>
              <v-card-text class="text-center cardRole">Backend developer</v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </div>

    <!-- A propos -->

    <div class="thirdFather mt-8" id="about">
      <h1 class="headings mt-6">À propos</h1>
      <v-container class="d-flex justify-center">
        <v-row>
          <v-col cols="8" class="ml-6">
            <p>Bienvenue sur "Stage Easy", la plateforme ultime conçue pour rendre la scène universitaire facile pour
              tous les acteurs impliqués - étudiants, chefs et directeurs de stage. Notre site web est dédié à fournir des
              ressources et des outils complets pour vous aider à tirer le meilleur parti de votre temps dans l'université.
              <br><br>
              Chez "Stage Easy", nous comprenons que la scène universitaire peut être difficile et écrasante, surtout pour les
              nouveaux venus. C'est pourquoi nous avons créé ce site web où vous pouvez trouver tout ce dont vous avez besoin
              pour réussir pendant votre stage universitaire.
              <br><br>
              Que vous soyez un étudiant cherchant à apprendre de nouvelles compétences et techniques,un chef cherchant 
              à encadrer la prochaine génération de talents culinaires ou un directeur de stage
              essayant de créer un environnement fluide et productif, nous avons des ressources adaptées 
              à vos besoins spécifiques.
            </p>
          </v-col>
          <v-col cols="3" class="justify-end ml-6">
            <v-spacer></v-spacer>
              <v-img src="../assets/BLACKstageasy.png" class="logoStage" height="450" width="100"></v-img>
          </v-col>
        </v-row>
      </v-container>
    </div>

    <!-- Contact -->

    <div class="fourthFather mt-8" id="contact">
      <h1 class="headings mt-6">Contact</h1>
      <v-container class="d-flex justify-center">
        <v-row>
          <v-col cols="12" md="8" class="pl-8 pt-0 mt-8">.
            <v-row>
              <v-col cols="8" md="1" class="align-self-center">
                <v-img src="../assets/icons/email2.png" max-height="40" width="40"></v-img>
              </v-col>
              <v-col cols="8" md="7" class="align-self-center">
                <a href="mailto:elearning@univ-constantine2.dz">elearning@univ-constantine2.dz</a>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="8" md="1" class="align-self-center">
                <v-img src="../assets/icons/twitter2.png" max-height="40" width="40"></v-img>
              </v-col>
              <v-col cols="8" md="7" class="align-self-center">
                <a href="https://twitter.com/uc2am" target="_blank">https://twitter.com/uc2am</a>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="8" md="1" class="align-self-center">
                <v-img src="../assets/icons/web2.png" max-height="40" width="40"></v-img>
              </v-col>
              <v-col cols="8" md="7" class="align-self-center">
                <a href="https://elearning@univ-constantine2.dz" target="_blank">https://elearning@univ-constantine2.dz</a>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="8" md="1" class="align-self-center">
                <v-img src="../assets/icons/fb2.png" max-height="40" width="40"></v-img>
              </v-col>
              <v-col cols="8" md="7" class="align-self-center">
                <a href="https://www.facebook.com/universiteconstantine2" target="_blank">https://www.facebook.com/universiteconstantine2</a>
              </v-col>
            </v-row>
            
          </v-col>
          <v-col cols="12" md="4">
            <v-img src="../assets/13.png" max-height="370" width="350"></v-img>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </section>
    <!-- Footer -->

    <v-footer dark padless>
    <v-card
      flat
      tile
      color="#120030"
      class="white--text text-center"
      width="100%"
    >
      <v-card-text>
        <v-btn
      v-for="item in items"
      :key="item.icon"
      class="mx-4 white--text"
      icon
    >
      <a :href="item.link" style="text-decoration: none; color: #63D693;">
        <v-icon size="24px">{{ item.icon }}</v-icon>
      </a>
    </v-btn>
      </v-card-text>

    <v-container class="d-flex justify-center">
      <v-row>
          <v-col cols="12" md="2"></v-col>
          <v-col cols="12" md="2"></v-col>
          <v-col cols="12" md="2">
            <v-img src="../assets/icons/uc2White.png" class="mx-auto" max-height="110" width="110"></v-img>
          </v-col>
          <v-col cols="12" md="2">
            <v-img src="../assets/icons/ifaWhite.png" class="mx-auto" max-height="100" width="100"></v-img>
          </v-col>
          <v-col cols="12" md="2"></v-col>
          <v-col cols="12" md="2"></v-col>
        </v-row>
      </v-container>
      <v-img src="../assets/WHITEstageasy.png" class="mx-auto my-4" max-height="50" width="150"></v-img>
        <v-divider></v-divider>

        <v-card-text class="white--text">
          {{ new Date().getFullYear() }} — <strong>Stage Easy - &copy; all rights reserved</strong>
        </v-card-text>
      </v-card>
    </v-footer>
  </v-app>
</template>

<script>
  // import navBar from '../components/navBar.vue';
  import api from "@/api"
  import loginModel from '../components/loginModel.vue';
import CheckAtt from '@/components/checkAtt.vue';
  export default {

    name: 'welcome-page',
    components:{
      // navBar,
      loginModel,
      CheckAtt,
    },
    data(){
      return{
        showAttCheckModel:false,
      showLoginModal: false,
      tokenAtt:'',
      infoAtt:[],
      items: [
        { icon: 'mdi-facebook', link: 'https://www.facebook.com/universiteconstantine2' },
        { icon: 'mdi-twitter', link: 'https://www.twitter.com' },
        { icon: 'mdi-web', link: 'https://elearning@univ-constantine2.dz' },
        { icon: 'mdi-email', link: 'mailto:elearning@univ-constantine2.dz' },
      ],
      
    }},

    methods: {
      async checkAtt(){
        try {
                await api.get("/certificate", {params:{ token: this.tokenAtt}})
                     .then(response => {
                      console.log(response)
                      this.infoAtt=response.data
                      console.log(this.infoAtt)
                      this.showAttCheckModel=!this.showAttCheckModel
                     });
            }
            catch (error) {
                console.log(error);
            }
      },
      signUp(){
        this.$router.push('/signUp')
      },
      showLogin() {
  const user = JSON.parse(localStorage.getItem('user'));
  
  if (user && ('id_Etud' in user)) {
    // Redirect to "/accueilStd"
    this.$router.push('/accueilStd');
  }else if (user && ('id_ChefDep' in user)) {
    // Redirect to "/accueilStd"
    this.$router.push('/accueilChef');
  } else if (user && ('id_Resp' in user)) {
    // Redirect to "/accueilStd"
    this.$router.push('/accueilResp');
  }else {
    this.showLoginModal = !this.showLoginModal;
  }
},
    animate(){
      let FirstTitle=document.getElementById('FirstTitle');
      let clouds1=document.getElementById('clouds1');
      let clouds2=document.getElementById('clouds2');
      let mountain1=document.getElementById('mountain1');
      let mountain2=document.getElementById('mountain2');
      let welcomeTo=document.getElementById('welcomeTo');


window.addEventListener('scroll',()=>{
  let value=window.scrollY;
  FirstTitle.style.top = value *0.75 +'px';
  clouds1.style.left = value *-1.5 +'px';
  clouds2.style.left = value *0.75 +'px';
  mountain1.style.top = value *0.80 +'px';
  mountain2.style.top = value *0.25 +'px';
  welcomeTo.style.top = value * -1.5 +'px';
})
    }
  },
  mounted(){
    this.animate();
  }

  }
    
    
    

</script>

<style scoped>
.welcome{
  height:100vh;
  background-image: url(@/assets/NewWelcome/bg-01ph.jpg.png);
}
.welcomeTo{
  position: absolute;
  margin-left: 25%;
  margin-top: 180px;
  color: #63D693;
  font-size: 40px;
  font-family: elefant;
}
.stageasy{
  position: relative;
  display: flex;
  justify-content: center;
  height: 100vh;
}
.stageasy img {
 position: absolute;
 top: 0;
 left: 0;
 width: 100%;
 pointer-events: none;
}
.FirstTitle{
  position: absolute;
 margin-top: 120px;
  font-size: 120px;
  color: #120030;
  letter-spacing: 4px;
  font-family: elephant Regular;
}
.FirstTitle span{
  color: #120030;
}
@media (max-width: 1200px) {
  .mainImg{
    max-height: 300px;
    width: 300px;
  }
}
/* FristPage "1/4" */
.navBarReg{
  width: 100%;
  height: 50px;
  margin-top: 20px;
}
.firstPage{
  margin: 0 80px 0 120px;
}
.firstPage h1 {
  font-family:'Outfit';
  font-size: 102px;
  font-weight: 600;
  line-height: 94%;
  color: #120030;
}

.firstPage h1 span{
  color: #63D693;
}
.firstPage p{
  font-size: 28px;
}
.firstPage p span{
  font-weight: 500;
}
.btn1{
  background-color: #63D693;
  color: #120030;
}
.btn1:hover{
  color: #63D693;
    background-color: #120030;
    transition: .5s;
}
.btn2{
  border: 2px solid #63D693;
  color: #120030;
  width: 150px;
  font-weight: 600;
}
.btn2:hover{
    border-color: #120030;
    transition: .5s;
}
/* SecondPage "2/4" */
.secondFather{
  margin-top: 20px;
}
.headings{
  margin-left: 100px;
  margin-bottom: 100px;
  font-family:'Outfit';
  font-size: 102px;
  font-weight: 600;
  line-height: 94%;
  color: #120030;
}
.devCards{
  width: 300px;
  height: 400px;
  border-radius: 20px;
}
.cardName{
  font-family: 'Outfit';
  font-weight: 600;
  font-size: 25px;
  line-height: 102%;
  color: #120030;
  background-color: white;
}
.cardRole{
  font-family: 'Roboto';
  font-weight: 400;
  font-size: 15px;
  line-height: 23px;
  color: #120030;
  background-color: white;
}
/* ThirdPage "3/4" */

.thirdFather p{
  font-size: 20px;
}
.logoStage{
  margin-left: 40%;
}
/* fourthPage "4/4" */
.fourthFather h1{
  margin-bottom: 20px;
}
.fourthFather a{
  display: block;
  font-size: 1.33rem;
  font-weight: bold;
  color: #120030;
  text-decoration: none;
  margin: 0;
  padding: 0;
}
.fourthFather a:hover{
  color: #63D693;
}
.sec2{
  z-index: 1000;
  position: relative;
  background-color: #fff;
}
.mainLogo{
    width: 50px;
    height: 50px;
    margin-top: 30px;
    margin-right: 55%;
}

.nav{
  position: relative;
  display: flex;
  justify-content: space-evenly;
  align-items: center;
}
.nav a{
  
  margin-top: 30px;
    margin-left: 30px;
    right: 15px;
    left: 1500px;
    text-decoration: none;
    color: #120030;
    font-family: elefant;
    font-size: 18px;
}
.attCheck{
  position: absolute;
  margin-top: 60px;
  left: 10%;
}
.checkBtn{
  position: absolute;
  margin-top: 32px;
  left: 25%;
}
.navOption{
  font-family: Outfit !important;
  color: #120030;
  padding: 2px 10px ;
}
.navOption:hover{
  background-color: white;
  border-radius: 50px;
}
</style>

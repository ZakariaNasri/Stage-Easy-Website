<template>
  <div class="container">

    <stdHeader title="Accueil" icon1="mdi-bell" toPage="./notificationStd"/>
      <v-row class="my-5">
        <v-col>
          <v-text-field
            v-model="searchTerm"
            placeholder="trouver une offre..."
            search
            style="width: 95%;margin: 0 auto;"
            filled
            color="#666"
            rounded
            dense
            class="search"
            append-icon="mdi-magnify"
          ></v-text-field>
        </v-col>
      </v-row>

    <addOffer 
    v-if="showFormModal" 
    @close="showFormModal = false"
    @click:outside="showForm"/>

    <existingOffer
      :offerId="offerInfo"
      v-if="showExsistingOfferModal" 
      @close="showExsistingOfferModal = false"
      @click:outside="showForm2"/>
    <v-container grid-list-xs style="position: relative;">

      <emptyArrays v-if="offers.length === 0" 
      message="Aucun offre disponible"
      :source="imagePath"
      />

      <button
        class="addOfferBtn"
        @click="showForm"
        >Ajouter une demande
      </button>
      

      <v-row cols="3" v-if="offers.length !== 0">
        <v-col md="4" lg4 v-for="(offer, id) in filteredOffers" :key="id" :offer-info="offer">
          <v-card
            class="mx-auto"
            max-width="344"
            style="border-radius: 20px;border: 1px solid #120030;"
          >
          
            <v-img
              :src="`${backendUrl}/offerPic/${offer.id_Offre}`"
              height="200px"
            >
            
          </v-img>
            <v-card-title style="color: #120030;font-family:Outfit;font-weight: 600;">
              {{ offer.theme }}
            </v-card-title>
  
            <v-card-subtitle style="font-family:Outfit" class="py-1">
              {{ offer.nom_Resp }} 
              {{ offer.pre_Resp }} / {{ offer.nom_Entreprise}}
            </v-card-subtitle>
            
            <v-card-subtitle class="py-0" style="font-family:Outfit;">
              <v-icon>mdi-map-marker</v-icon>
              {{ offer.addr_Entreprise }} 
            </v-card-subtitle>
            <v-card-actions>
              <v-btn
                color="#63D693"
                text
                @click="showForm2(offer.id_Offre)"
              >
                Envoyer
              </v-btn>
  
              <v-spacer></v-spacer>
  
              <v-btn
                icon
                @click="offer.showDesc = !offer.showDesc"
              >
                <v-icon>{{ offer.showDesc ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
              </v-btn>
            </v-card-actions>
  
            <v-expand-transition>
              <div v-show="offer.showDesc">
                <v-divider></v-divider>
  
                <v-card-text>
                  {{ offer.contenu }}
                </v-card-text>
              </div>
            </v-expand-transition>
          </v-card>
          <h4 class="text-center text-sm-body-2 mr-3 mt-2">{{offer.datePost}}</h4>
        </v-col>
      </v-row>
      
    </v-container>
</div>
  </template>
  
  <script>
  import  addOffer  from "@/components/addOffer.vue";
  import  existingOffer  from "@/components/existingOffer.vue";
  import api from '@/api';
  import stdHeader from "@/components/stdHeader.vue";
  import backendUrl from '@/backendConfig'
import EmptyArrays from '@/components/emptyArrays.vue';
export default {
    name: 'accueilStd',
    components:{
      addOffer,
      existingOffer,
      stdHeader,
        EmptyArrays,
    },
    data(){
      return{
        backendUrl,
        imagePath: require('@/assets/empty/9.png'),
        searchTerm:'',
        userInfo:[],
        showLogin:false,
        offers:[],
        offerInfo:null,
        showFormModal: false,
        showExsistingOfferModal:false,
    }
  },
  async mounted(){
    await this.getOffers();
    this.getOfferImg();
  },
  methods: {
        showForm() {
      this.showFormModal = !this.showFormModal
        },
        showForm2(id_Offre) {
      this.showExsistingOfferModal = !this.showExsistingOfferModal
      this.offerInfo=id_Offre;
      console.log(this.offerInfo)
      
        },
        getOfferImg(path){
          return require(`${path}`);
        },

        async getOffers(){
  try{
    await api.get('/consulterOffre')
      .then(response => {
        console.log(response)
        this.offers = response.data;
      })
  }catch(error) {
        console.log(error)
      }},
}, 
  computed:{
    filteredOffers() {
  if (this.searchTerm === '') {
    return this.offers;
  } else {
    return this.offers.filter((offer) => {
      return Object.values(offer).some(
        (val) =>
          val !== null &&
          typeof val === 'string' &&
          val.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
    });
  }
},
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

.cards{
  border-radius: 50px;
  border: 2px solid #63D693;
}

.addOfferBtn{
  padding: 30px 30px; 
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
  /* box-shadow: 0px 0px 20px 0px #12003050; */
}
.addOfferBtn:hover {
  background-color: #120030;
  color: #63D693;
  border: 2px solid #63D693;
  transition: all 0.3s ease-in-out;
  /* box-shadow: 0px 0px 20px 0px #63D693; */
}

</style>
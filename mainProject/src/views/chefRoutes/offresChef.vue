<template>
    <v-container grid-list-xs>
      <chefHeader title="Les Offres" icon1="mdi-bell" toPage="./notificationChef"/>
        <v-row>
          <v-col>
          <v-text-field
    v-model="searchTerm"
    placeholder="trouver un offre..."
    filled
    color="#666"
    rounded
    dense
    class="search"
    append-icon="mdi-magnify"
  ></v-text-field>
          </v-col>
        </v-row>

        <emptyArrays v-if="offers.length === 0" 
      message="Aucun offre disponible"
      :source="imagePath"
      />
      <v-container grid-list-xs>
        <v-row cols="3">
          <v-col   md="4" lg4 v-for="(offer, id) in filteredOffers" :key="id">
            <v-card
              class="mx-auto"
              max-width="344"
              style="border-radius: 20px;border: 1px solid #120030;"
            >
              <v-img
              :src="`${backendUrl}/offerPic/${offer.id_Offre}`"
                height="200px"
              ></v-img>
    
              <v-card-title style="color: #120030;font-family:Outfit;font-weight: 600;">
                {{ offer.theme }}
              </v-card-title>
    
              <v-card-subtitle class="pb-2" style="font-family:Outfit">
                {{ offer.nom_Resp }} {{ offer.pre_Resp }}
              </v-card-subtitle>
              <v-card-subtitle style="font-family:Outfit" class="py-0">
                <v-icon>mdi-map-marker</v-icon> {{ offer.addr_Entreprise }}
              </v-card-subtitle>
                <v-spacer></v-spacer>
                <v-btn
                  icon
                  @click="offer.showDesc = !offer.showDesc"
                >
                  <v-icon>{{ offer.showDesc ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                </v-btn>
              
    
              <v-expand-transition>
                <div v-show="offer.showDesc">
                  <v-divider></v-divider>
    
                  <v-card-text>
                    {{ offer.contenu }}
                  </v-card-text>
                </div>
              </v-expand-transition>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-container>
    </template>
    
    <script>
    import api from "@/api";
    import chefHeader from "@/components/chefHeader.vue";
import EmptyArrays from '@/components/emptyArrays.vue';
import backendUrl from '@/backendConfig'
export default {
  components:{
    chefHeader,
    EmptyArrays,
  },
      data(){
        return{
          backendUrl,
          offers:[],
          searchTerm:'',
          imagePath: require('@/assets/empty/9.png'),
      }
    },
    async mounted(){
  await this.getOffers()
  },
    computed: {
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
  },
  methods:{
    async getOffers(){
      try{
      await api.get('/consulterListOffre')
      .then(response => {
        console.log(response)
        this.offers = response.data;
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
  
  .cards{
    border-radius: 20px;
    border: 2px solid #120030;
  }
  .router-link{
    text-decoration: none;
}
  </style>
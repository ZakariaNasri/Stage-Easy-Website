<template>
  <v-container grid-list-xs>
    <respHeader title="Les Offres" icon1="mdi-bell" toPage="./notifResp"/>

    <EditOfferResp :InfoOffre="infoOffre"
    v-if="showEditOfferModel"
      @close="showEditOfferModel = false"
      @click:outside="showEditOfferModel = false"
    />

    <AlertCreateAccount 
      header="Supprimee!"
      :msg="msg"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>

      <addOfferResp 
      v-if="showFormCreate"
      @close="showFormCreate = false"
      @click:outside="showFormCreate = false"/>
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
    <v-container grid-list-xs style="position: relative;"  class="mt-3">
      <v-row >
        <v-col cols="12" md="4" lg4 v-for="(offer, id) in filteredOffers" :key="id">
          <v-card
            class="mx-auto"
            max-width="344"
            style="border-radius: 20px; border: 1px solid #120030;"
            @mouseover="offer.isHovered = true"
            @mouseout="offer.isHovered = false"
          >
            <v-img
              :src="`${backendUrl}/offerPic/${offer.id_Offre}`"
              height="200px"
            ></v-img>

            <div class="cardOnHover" :class="{ active: offer.isHovered }">
              <div class="absolute-btns">
                <v-btn @click="deleteOffer(offer.id_Offre)" rounded color="red" dark class="text-center mb-2 mr-5">Supprimer</v-btn>
                <v-btn @click="ModifyOffer(offer)" rounded class="text-center mb-2" dark color="#120030">Modifier</v-btn>
              </div>
            </div>
            <v-card-title style="color: #120030;font-family:Outfit;font-weight: 600;">
              {{ offer.theme }}
            </v-card-title>
  
            <v-card-subtitle style="font-family:Outfit">
              {{ offer.nom_Resp }} {{ offer.pre_Resp }}
            </v-card-subtitle>
              <v-spacer></v-spacer>
              <v-btn
                icon
                @click="offer.showDesc = !offer.showDesc"
                style="z-index: 3;"
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
    <v-btn
        class="addOfferBtn"
        @click="showForm"
        >Ajouter une offre
        
  </v-btn>
  </v-container>
</template>

<script>
import api from "@/api";
import AlertCreateAccount from "@/components/alertCreateAccount.vue";
import respHeader from "@/components/respHeader.vue";
import EditOfferResp from '@/components/editOfferResp.vue';
import AddOfferResp from '@/components/addOfferResp.vue';
import EmptyArrays from '@/components/emptyArrays.vue';
import backendUrl from '@/backendConfig'
export default {
  components: {
    respHeader,
    AlertCreateAccount,
    EditOfferResp,
    AddOfferResp,
    EmptyArrays
},
  data() {
    return {
      backendUrl,
      offers: [],
      infoOffre:{},
      searchTerm: "",
      msg:'',
      showAlertCreateModel:false,
      showEditOfferModel:false,
      showFormCreate:false,
      imagePath: require('@/assets/empty/9.png'),
    };
  },
  async mounted() {
    await this.getOffers();
  },
  computed: {
    user() {
      return JSON.parse(localStorage.getItem("user"));
    },
    filteredOffers() {
      if (this.searchTerm === "") {
        return this.offers;
      } else {
        return this.offers.filter((offer) => {
          return Object.values(offer).some(
            (val) =>
              val !== null &&
              typeof val === "string" &&
              val.toLowerCase().includes(this.searchTerm.toLowerCase())
          );
        });
      }
    },
  },
  methods: {
    async getOffers() {
      try {
        await api.post("/sesOffre", { id: this.user.id_Resp }).then((response) => {
          console.log(response)
          this.offers = response.data.map((offer) => ({
            ...offer,
            isHovered: false,
          }));
        });
      } catch (error) {
        console.log(error);
      }
    },
    async deleteOffer(idOffre){
      try {
        await api.delete("/suppOffre", {data:{id:idOffre}})
        .then((response) => {
          this.msg=response.data.msg
          this.showAlertCreateModel=!this.showAlertCreateModel
          setTimeout(function() {
            location.reload();
        }, 2000);
        });
      } catch (error) {
        console.log(error);
      }
    },
    ModifyOffer(offer){
      this.infoOffre=offer
      this.showEditOfferModel=!this.showEditOfferModel

    },
    showForm(){
      this.showFormCreate=!this.showFormCreate
      console.log(this.showFormCreate)
    }
  },
};
</script>

<style scoped>
.header {
  font-size: 70px;
  font-family: 'Outfit';
  color: #120030;
}

.header span {
  color: #63D693;
}

.cards {
  border-radius: 20px;
  border: 2px solid #120030;
}

.cardOnHover {
  position: absolute;
  z-index: 2;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  padding: 20px;
  display: flex;
  justify-content: space-around;
  background: linear-gradient(to bottom, #63d6939b 20%, transparent);
  border-radius: 20px;
  visibility: hidden;
  opacity: 0;
  transition: visibility 0s, opacity 0.3s ease;
}

.cardOnHover.active {
  visibility: visible;
  opacity: 1;
}

.absolute-btns {
  display: flex;
  justify-content: space-between;
}

.router-link {
  text-decoration: none;
}
.addOfferBtn{
  text-transform: none !important;
  font-size: 25px !important; 
  letter-spacing: 1px;
  font-family: Outfit !important;
  font-weight: 500 !important;
  color: white !important;
  background-color: #63D693 !important;
  position: fixed !important;
  /* border: 2px solid white; */
  right: 5%;
  bottom: 5%;
  z-index: 100;
  border-radius: 15px !important;
  padding: 35px 30px !important;
  /* box-shadow: 0px 0px 20px 0px #12003050; */
}
.addOfferBtn:hover {
  background-color: #120030 !important;
  color: #63D693 !important;
  border: 2px solid #63D693 !important;
  transition: all 0.3s ease-in-out !important;
  /* box-shadow: 0px 0px 20px 0px #63D693; */
}
</style>

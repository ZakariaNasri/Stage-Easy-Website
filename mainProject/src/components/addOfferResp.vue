<template>
    <v-dialog class="overflow-hidden" v-model="dialog" max-width="900">
      <alertCreateAccount
        header="Modification:"
        :msg="msg"
        :idStage="idStage"
        v-if="showAlertCreateModel"
        @close="showAlertCreateModel = false"
        @click:outside="showAlertCreateModel = false"
      />
  
      <v-card class="px-5 py-5" style="border-radius: 10px;">
        <h1 class="bigTitle text-center mb-4">Ajouter un offre</h1>
        <form>

          <v-container grid-list-xs>
            <v-row class="mt-5">
                <!-- <v-col cols="12" md="6">
                    <div class="imgHolder" style="background-color: #eee; width: 90px; height: 90px;">
                    
                    </div>
                </v-col> -->
              <v-col cols="12" md="12" class="text-center">
                <v-file-input
                    v-model="image"
                    label="Select an image"
                    outlined
                    accept="image/*"
                    ref="imageInput"
                    color="#63D693"
                    class="mb-4"
                    prepend-icon="mdi-image"
                    >
                    </v-file-input>
              </v-col>
  
              <v-col cols="12" md="12">
                <v-text-field v-model="theme" label="theme de stage" filled outlined dense></v-text-field>
              </v-col>
  
              <v-col cols="12" md="6">
                <v-text-field v-model="duree" label="duree de stage" filled outlined dense></v-text-field>
              </v-col>
  
              <v-col cols="12" md="6">
                <v-text-field v-model="contenu" label="contenu de stage" filled outlined dense></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </form>
  
        <v-layout row wrap class="justify-end mr-3 mb-2">
          <v-btn class="mr-3" style="border: 2px solid #63D693;">Effacer</v-btn>
          <v-btn color="#63D693" @click="addOffre">Sauvegarder</v-btn>
        </v-layout>
      </v-card>
    </v-dialog>
  </template>
  
  <script>
  import api from '@/api'
  import alertCreateAccount from "@/components/alertCreateAccount.vue";
  export default {
    components: {
      alertCreateAccount,
    },
    data() {
      return {
        dialog: true,
        theme: '',
        image: null,
        duree: '',
        contenu: '',
        showAlertCreateModel: false,
      }
    },
    computed: {
      user() {
        return JSON.parse(localStorage.getItem("user"));
      },
    },
    methods: {
      async addOffre() {
        console.log(this.image)
        try {
          console.log(this.offreInfos)
          console.log(this.image)
          await api.post('/ajouterOffre', {
            id_Resp: this.user.id_Resp,
            img:this.image,
            theme: this.theme,
            duree: this.duree,
            contenu: this.contenu,
          },{
            headers:{
              "Content-Type":"multipart/form-data"
            },
          }
          )
            .then(response => {
              this.msg = response.data.msg;
              this.showAlertCreateModel = !this.showAlertCreateModel;
              setTimeout(function () {
                location.reload();
              }, 2000);
            })
        } catch (error) {
          console.log(error);
        }
      },
    },
    mounted() {
    }
  }
  </script>
  
  <style scoped>
  .bigTitle {
    font-size: 40px;
    font-family: Outfit;
    font-weight: 600;
    color: #63D693;
  }
  
  .bigTitle span {
    color: #120030;
  }
  </style>
  
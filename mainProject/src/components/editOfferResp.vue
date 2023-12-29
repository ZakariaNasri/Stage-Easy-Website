<template>
    <v-dialog class="overflow-hidden" v-model="dialog" max-width="900" >
      <alertCreateAccount 
          header="Modification:"
          :msg="msg"
          v-if="showAlertCreateModel"
          @close="showAlertCreateModel = false"
          @click:outside="showAlertCreateModel = false"/>
    
      <v-card class="px-5 py-5" style="border-radius: 10px;">
        <div class="imgHolder py-2" :style="{ backgroundImage: `url(${backendUrl}/offerPic/${offreInfos.id_Offre})`}">
          <h1 class="bigTitle text-center mb-4">Modifier l'offre <span>{{ offreInfos.theme }}</span></h1>
        </div>
        <form>
            <v-container grid-list-xs>
                <v-row class="mt-5">
            <v-col cols="12" md="6" >
              <v-text-field 
              v-model="offreInfos.theme"
              label="theme de stage"
              filled 
              :value="InfoOffre.theme"
              outlined 
              dense>
              </v-text-field>
            </v-col>
            <v-col cols="12" md="6" >
              <v-file-input 
              v-model="image"
              label="image"
              accept="image/*"
              ref="imageInput"
              filled 
              :value="InfoOffre.img_Offre"
              outlined 
              dense>
              </v-file-input>
            </v-col>
    
            <v-col cols="12" md="6" >
              <v-text-field 
              v-model="offreInfos.duree"
              label="duree de stage"
              filled 
              :value="InfoOffre.duree"
              outlined 
              dense>
              </v-text-field>
            </v-col>
    
            <v-col cols="12" md="6" >
              <v-text-field 
              v-model="offreInfos.contenu"
              label="contenu de stage"
              filled 
              :value="InfoOffre.contenu"
              outlined 
              dense>
              </v-text-field>
            </v-col>
    
            <v-col cols="12" md="6" >
              <v-text-field 
              v-model="offreInfos.nom_Resp"
              label="nom du responsable"
              filled 
              :value="InfoOffre.nom_Resp"
              outlined 
              disabled
              dense>
              </v-text-field>
            </v-col>
    
            <v-col cols="12" md="6" >
              <v-text-field 
              v-model="offreInfos.pre_Resp"
              label="prenom du responsable"
              filled 
              disabled
              :value="InfoOffre.pre_Resp"
              outlined 
              dense>
              </v-text-field>
            </v-col>
    
          </v-row>
            </v-container>
        </form>
        <v-layout row wrap class="justify-end mr-3 mb-2">
            <v-btn class="mr-3"
            style="border: 2px solid #63D693;"
            >Effacer</v-btn>
        <v-btn color="#63D693"
        @click="updateOffre"
        >Sauvgarder</v-btn>
        </v-layout>
        
      </v-card>
    </v-dialog>
    
    </template>
    
    <script>
    import api from '@/api'
    import alertCreateAccount from "@/components/alertCreateAccount.vue";
    import backendUrl from '@/backendConfig'
    export default {
        components:{
            alertCreateAccount,
        },
      props:['InfoOffre'],
        data(){
            return{
                backendUrl,
                dialog: true,
                image:null,
                offreInfos: {},
                showAlertCreateModel:false,
            }
           
        },
        methods:{
          async updateOffre(){
            console.log(this.offreInfos.theme)
            try{
              console.log(this.offreInfos)
              console.log(this.image)
              await api.post('/modifierOffre',{
                idOffre:this.offreInfos.id_Offre,
                theme:this.offreInfos.theme,
                duree:this.offreInfos.duree,
                img:this.image,
                contenu:this.offreInfos.contenu,
                nomR:this.offreInfos.nom_Resp,
                preR:this.offreInfos.pre_Resp,
              },{
            headers:{
              "Content-Type":"multipart/form-data"
            },
          }
              )
              .then(response => {
              
                  this.msg = response.data.msg;
                  this.showAlertCreateModel=!this.showAlertCreateModel;
                setTimeout(function() {
                location.reload();
                }, 2000);
          })
        }
        catch(error) {
            console.log(error);
          }
          }
        },
        mounted(){
          this.offreInfos=this.InfoOffre
        }
    }
    </script>
    
    <style scoped>
      .bigTitle{
        font-size: 40px;
        font-family: Outfit;
        font-weight: 600;
        color: #63D693;
      }
      .bigTitle span{
        color: #120030;
      }
      .imgHolder{
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center; 
      }
    </style>
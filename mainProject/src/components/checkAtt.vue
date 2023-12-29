<template>
    <v-dialog
      v-model="dialog"
      transition="dialog-transition"
      max-width="450px"
      >
      <div style="background-color: white;  width: 450px;  overflow: hidden;">
          <v-row class="d-flex justify-center">
              <h1 :style="{ color: getColor(infoAtt.message) }" primary-title class="heading mt-4">
                 {{ infoAtt.message }}
              </h1>
          </v-row>
          <v-divider class="my-5"></v-divider>
          <v-row class="my-4" v-if="infoAtt.message!=='Certificat introuvable'">
            <v-col md="12" class="text-center">nom : <span>{{ infoAtt.etudiant.nom_Etud}}</span></v-col>
            <v-col md="12" class="text-center">prenom : <span>{{ infoAtt.etudiant.pre_Etud}}</span></v-col>
            <v-col md="12" class="text-center">date naissance : <span>{{ infoAtt.etudiant.dateNaiss}}</span></v-col>
            <v-col md="12" class="text-center">lieu naissance : <span>{{ infoAtt.etudiant.lieuNaiss}}</span></v-col>
            <v-col md="12" class="text-center">email : <span>{{ infoAtt.etudiant.email}}</span></v-col>
            <v-col md="12" class="text-center">telephone : <span>{{ infoAtt.etudiant.telEtud}}</span></v-col>
            <v-col md="12" class="text-center">numero carte : <span>{{ infoAtt.etudiant.numCarte}}</span></v-col>
            <v-col md="12" class="text-center">diplome : <span>{{ infoAtt.etudiant.diplome}}</span></v-col>
            <v-col md="12" class="text-center">specialite : <span>{{ infoAtt.etudiant.specialite}}</span></v-col>
        </v-row>
      </div>
    </v-dialog>
  </template>
  
  <script>
  export default {
      data(){
          return{
              dialog:true,
          }
      },
      props:['infoAtt'],
  methods:{
      getColor(msg){
          if (msg === 'Certificat introuvable') {
              console.log(msg)
              return 'red'
          }else return '#63D693'
      }
  }
  }
  </script>
  
  <style scooped>
      .heading{
          font-family: Outfit;
          font-size: 30px;
          font-weight: 600;
      }
      span{
        font-weight: 700;
      }
  </style>
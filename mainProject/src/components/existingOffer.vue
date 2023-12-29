<template>
  <v-dialog class="overflow-hidden" v-model="dialog" max-width="600" >
    <v-card class="px-5 py-5" style="border-radius: 10px; max-height: 500px;" >
  <h1 class="bigTitle text-center mb-4">Entrer la date debut de stage</h1>
      <v-divider class="my-5" style="margin: 0 auto; width:70%;"></v-divider>


      <alertCreateAccount 
      header="Ajoutee!"
      :msg="msg"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>

      <ErrorPopup
      header="Erreur!"
      :msg="msg"
      v-if="showErrorModel"
      @close="showErrorModel = false"
      @click:outside="showErrorModel = false"/>
      
      <form>
    <v-row>
        <!-- dateDebut -->
        <v-col >
            <v-menu
        ref="menu"
        v-model="menu3"
        :close-on-content-click="false"
        :return-value.sync="date"
        transition="scale-transition"
        offset-y
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="dateD"
            :error-messages="dateDebErrors"
            label="Date Debut de stage"
            prepend-icon="mdi-calendar"
            readonly
            color="#63D693"
            outlined
            v-bind="attrs"
            v-on="on"
            @input="$v.dateD.$touch()"
          @blur="$v.dateD.$touch()"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="dateD"
          no-title
          scrollable
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="menu3 = false"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="primary"
            @click="$refs.menu.save(date)"
          >
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
        </v-col>
    </v-row>

     

    <v-layout row wrap class="justify-end">
        <v-btn 
      class="mr-4 mt-4" 
      flat
      style="border: 2px solid #63D693;"
      @click="clear">
      Effacer
    </v-btn>

        <v-btn
      class="mt-4"
      @click="submit"
      color="#63D693"
    >
      Ajouter
    </v-btn>
    </v-layout>
    
</form>
</v-card>
  </v-dialog>
</template>

<script>
import api from "@/api";
import { validationMixin } from 'vuelidate'
  import { required} from 'vuelidate/lib/validators'
  import alertCreateAccount from "@/components/alertCreateAccount.vue";
import ErrorPopup from './errorPopup.vue';
  export default {
  components:{
    alertCreateAccount,
    ErrorPopup,
  },
    mixins: [validationMixin],
    validations: {
      dateD:{required},
    },
    data(){
        return{
            dialog:true,
            showAlertCreateModel:false,
            showErrorModel:false,
            dateD: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            cv:null,
            menu3: false,
            menu4: false,
            msg:''
        }
    },
    props:['offerId'],
    computed:{
      user() {
      return JSON.parse(localStorage.getItem('user'));
    },
        dateDebErrors () {
        const errors = []
        if (!this.$v.dateD.$dirty) return errors
        !this.$v.dateD.required && errors.push('date Debut is required')
        return errors
      },
    },
    methods: {
        async submit () {
          this.$v.$touch()
          console.log(this.user.id_Etud)
        try{
          await api.post('/postulerOffre',{
            idEtud:this.user.id_Etud,
            idOffre:this.offerId,
            dateD:this.dateD,
          })
          .then(response => {
            console.log(response)
          this.msg = response.data.msg;
          if (this.msg==='Votre demande a était bien envoye') {
            this.dialog=!this.dialog
            this.showAlertCreateModel=!this.showAlertCreateModel
            setTimeout(function() {
            location.reload();
        }, 2000);
          }else{
            this.showErrorModel=!this.showErrorModel
          this.dialog=!this.dialog
          setTimeout(function() {
            location.reload();
        }, 4000);
          }
          })
        }catch(error) {
        console.log(error)
        }},
        clear () {
        this.$v.$reset()
        this.dateD=''
        this.dateF=''
      },

      },
}
</script>

<style scoped>
  .bigTitle{
    font-size: 40px;
    font-family: Outfit;
    font-weight: 600;
    color: #63D693;
  }
  .file-input input[type="file"] {
  display: none;
}
.file-input:active:before {
  background-color: #120030;
}
</style>
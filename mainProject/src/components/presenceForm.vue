<template>
  <div class="container">

    <alertCreateAccount 
      header="Ajoutee!"
      :msg="msg"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>
    <v-dialog class="overflow-hidden" v-model="dialog" max-width="900" >
    <v-card class="px-5 py-5" style="border-radius: 10px; max-height: 500px; overflow-y: auto;" >

      <h1 class="bigTitle text-center mb-4">Entrer une nouvelle presence</h1>
      <v-divider class="my-3" style="width: 60%; margin: 0 auto;"></v-divider>
      <form>
      <v-select
      v-model="selectedStudentId"
    :items="listStagiaire.map(stagiaire => ({
      id: stagiaire.id_Etud,
      text: `${stagiaire.nom_Etud} ${stagiaire.pre_Etud}`
    }))"
    item-text="text"
    item-value="id"
    outlined
    label="Select student..."
    color="#63D693"
    required
          @input="$v.prenomStagiaire.$touch()"
          @blur="$v.prenomStagiaire.$touch()"
        ></v-select>
        <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="date"
        transition="scale-transition"
        offset-y
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            dense
            outlined
            v-model="currentDate"
            label="Date"
            color="#63D693"
            prepend-icon="mdi-calendar"
            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="currentDate"
          no-title
          scrollable
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="menu = false"
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
      <v-row>
        <v-col md="6">
          <v-menu
        ref="menuHour1"
        v-model="menuHour1"
        :close-on-content-click="false"
        :nudge-right="40"
        :return-value.sync="hour1"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="290px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            dense
            outlined
            v-model="hour1"
            :error-messages="hour1Errors"
            label="Date d'entree"
            color="#63D693"
            prepend-icon="mdi-clock-time-four-outline"
            readonly
            v-bind="attrs"
            v-on="on"
            input="$v.hour1.$touch()"
            @blur="$v.hour1.$touch()"
          ></v-text-field>
        </template>
        <v-time-picker
          v-if="menuHour1"
          v-model="hour1"
          color="#120030"
          full-width
          @click:minute="$refs.menuHour1.save(hour1)"
        >
        <v-btn
            text
            color="#63D693"
            @click="menuHour1 = false"
          >
            Cancel
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            text
            color="#63D693"
            @click="$refs.menuHour1.save(hour1)"
          >
            OK
          </v-btn>
      </v-time-picker>
      </v-menu>
        </v-col>

        <v-col>
          <v-menu
        ref="menuHour2"
        v-model="menuHour2"
        :close-on-content-click="false"
        :nudge-right="40"
        :return-value.sync="hour2"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="290px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            dense
            outlined
            v-model="hour2"
            :error-messages="hour2Errors"
            label="Date de sortie"
            color="#63D693"
            prepend-icon="mdi-clock-time-four-outline"
            readonly
            v-bind="attrs"
            v-on="on"
            input="$v.hour2.$touch()"
          @blur="$v.hour2.$touch()"
          ></v-text-field>
        </template>
        <v-time-picker
          v-if="menuHour2"
          v-model="hour2"
          color="#120030"
          full-width
          @click:minute="$refs.menuHour2.save(hour2)"
        >
        <v-btn
            text
            color="#63D693"
            @click="menuHour2 = false"
          >
            Cancel
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            text
            color="#63D693"
            @click="$refs.menuHour2.save(hour2)"
          >
            OK
          </v-btn>
      </v-time-picker>
      </v-menu>
        </v-col>
      </v-row>
      
      
        <v-btn
      class="mr-4 mt-4"
      @click="submit"
      color="#63D693"
    >
      Ajouter
    </v-btn>
    <v-btn 
      class="mt-4" 
      flat
      style="border: 2px solid #63D693;"
      @click="clear">
      Effacer
    </v-btn>
</form>


      </v-card>
      </v-dialog>
    
  </div>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { required, maxLength, } from 'vuelidate/lib/validators'
  import  api  from "@/api";
import alertCreateAccount from './alertCreateAccount.vue';
export default {
  components: { alertCreateAccount },
    mixins: [validationMixin],

validations: {
  prenomStagiaire: { required, maxLength: maxLength(30) },
  nomStagiaire:{required, maxLength: maxLength(30)},
  hour1:{required},
  hour2:{required}
},
data(){
    return{
        listStagiaire:[],
        selectedStudentId:null,
        hour1: null,
        hour2: null,
        menu: false,
        menuHour1:false,
        menuHour2:false,
        dialog: true,
        nomStagiaire:'',
        prenomStagiaire:'',
        currentDate: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        showAlertCreateModel:false,
        msg:''
        
        
    }
},
computed: {
      nameErrors () {
        const errors = []
        if (!this.$v.prenomStagiaire.$dirty) return errors
        !this.$v.prenomStagiaire.maxLength && errors.push('Name must be at most 30 characters long')
        !this.$v.prenomStagiaire.required && errors.push('Name is required.')
        return errors
      },
      fNameErrors () {
        const errors = []
        if (!this.$v.nomStagiaire.$dirty) return errors
        !this.$v.nomStagiaire.maxLength && errors.push('Family Name must be at most 30 characters long')
        !this.$v.nomStagiaire.required && errors.push('Family Name is required.')
        return errors
      },
      hour1Errors () {
        const errors = []
        if (!this.$v.hour1.$dirty) return errors
        !this.$v.hour1.required && errors.push('this field is required.')
        return errors
      },
      hour2Errors () {
        const errors = []
        if (!this.$v.hour2.$dirty) return errors
        !this.$v.hour2.required && errors.push('this field is required.')
        return errors
      },
      user() {
      return JSON.parse(localStorage.getItem('user'));
    },
    },
    mounted(){
    this.getListStagiaire();
  },
  methods:{
    async getListStagiaire(){
      try{
       await api.post('/listStagiaires', {id:this.user.id_Resp})
      .then(response => {
        this.listStagiaire = response.data;
        console.log(this.listStagiaire)
      })
    }
    catch(error) {
        console.log(error);
      }
    },
      async submit () {
        this.$v.$touch();
      try{
       await api.post('/presence', {id:this.selectedStudentId,
        heureE:this.hour1,
        heureS:this.hour2,
      })
      .then(response => {
        this.msg=response.data.msg
        this.showAlertCreateModel=!this.showAlertCreateModel
        setTimeout(function() {
            location.reload();
        }, 2000);
      })
    }
    catch(error) {
        console.log(error);
      }
      },
      clear () {
        this.$v.$reset()
        this.nomStagiaire=''
        this.prenomStagiaire=''
        this.hour1=null
        this.hour2=null
      }
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

</style>
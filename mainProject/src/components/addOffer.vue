<template>
  <v-dialog class="overflow-hidden" v-model="dialog" max-width="900" >
    <v-card class="px-5 py-5" style="border-radius: 10px; max-height: 500px; overflow-y: auto;" >

      <h1 class="bigTitle text-center mb-4">Ajouter une demande</h1>
      <v-divider class="my-3" style="width: 60%; margin: 0 auto;"></v-divider>
      <alertCreateAccount 
      header="Ajoutee!"
      :msg="msg"
      v-if="showAlertCreateModel"
      @close="showAlertCreateModel = false"
      @click:outside="showAlertCreateModel = false"/>
      <form>
        <div class="header">
          <p class="categoriesTitle mb-3">Entreprise</p>
          <v-divider class="mb-5"></v-divider>
        </div>
        <v-text-field
          v-model="entrName"
          :error-messages="nameErrors"
          :counter="30"
          outlined
          label="Name"
          required
          @input="$v.entrName.$touch()"
          @blur="$v.entrName.$touch()"
        ></v-text-field>
        <v-row>
          <v-col md="6">
            <v-text-field
          v-model="adrs"
          :error-messages="addressErrors"
          :counter="50"
          outlined
          label="Adresse"
          required
          @input="$v.adrs.$touch()"
          @blur="$v.adrs.$touch()"
        ></v-text-field>
          </v-col>

          <v-col md="6">
            <v-text-field
          v-model="tel"
          :error-messages="phoneErrors"
          :counter="10"
          outlined
          label="Numero de Telephone"
          required
          @input="$v.tel.$touch()"
          @blur="$v.tel.$touch()"
        ></v-text-field>
          </v-col>
        </v-row>
        <div class="header">
          <p class="categoriesTitle mb-3">Responsable de Stage</p>
          <v-divider class="mb-5"></v-divider>
        </div>
        
        <v-row>
          <!-- Prenom -->
          <v-col md="6">
            <v-text-field
          v-model="resFirstName"
          :error-messages="nameRespErrors"
          :counter="30"
          outlined
          label="Prenom"
          required
          @input="$v.resFirstName.$touch()"
          @blur="$v.resFirstName.$touch()"
        ></v-text-field>
          </v-col>
          <!-- Nom -->
          <v-col md="6">
            <v-text-field
          v-model="resLastName"
          :error-messages="fNameErrors"
          :counter="30"
          outlined
          label="Nom"
          required
          @input="$v.resLastName.$touch()"
          @blur="$v.resLastName.$touch()"
        ></v-text-field>
          </v-col>
        </v-row>

        <v-text-field
          v-model="resEmail"
          :error-messages="emailErrors"
          :counter="50"
          outlined
          label="e-mail"
          required
          @input="$v.resEmail.$touch()"
          @blur="$v.resEmail.$touch()"
        ></v-text-field>
        
        <div class="header">
          <p class="categoriesTitle mb-3">Stage</p>
          <v-divider class="mb-5"></v-divider>
        </div>

        <v-row>
          <v-col md="12">
            <v-text-field
          v-model="theme"
          :error-messages="themeErrors"
          :counter="50"
          outlined
          label="Theme"
          required
          @input="$v.theme.$touch()"
          @blur="$v.theme.$touch()"
        ></v-text-field>
          </v-col>
        </v-row>
        
        <!-- Dates -->

        <v-row>
          <!-- Date Debut -->
          <v-col md="6">
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
            v-model="dateD"
            label="Date Debut de stage"
            prepend-icon="mdi-calendar"
            readonly
            outlined
            v-bind="attrs"
            v-on="on"
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
          </v-col>
          <v-col md="6">
            <v-slider 
          v-model="duree"
          min="7"
          max="90" 
          color="#63D693"
          label="Duree (jours)"
          thumb-label
          class="mt-4"
          @input="$v.duree.$touch()"
          @blur="$v.duree.$touch()"
          >Duree</v-slider>
          </v-col>
          <!-- Date Fin -->
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
</template>

<script>
  import api from "@/api";
  import { validationMixin } from 'vuelidate'
  import { required,minLength, maxLength, email } from 'vuelidate/lib/validators'
  import alertCreateAccount from "@/components/alertCreateAccount.vue";
  export default {
  components:{
    alertCreateAccount,
  },
    mixins: [validationMixin],

    validations: {
      entrName: { required, maxLength: maxLength(30) },
      resLastName:{required, maxLength: maxLength(30)},
      resFirstName:{required, maxLength: maxLength(10)},
      adrs: { required, },
      tel:{required, maxLength: maxLength(10),minLength: minLength(10)},
      resEmail: { required, email },
      theme:{required},
      duree:{required},
      dateD:{required},
      dateFin:{required}
    },

    data: () => ({
        dialog: true,
        showAlertCreateModel:false,
      entrName: '',
      resFirstName:'',
      resLastName:'',
      tel:'',
      resEmail: '',
      adrs:'',
      theme:'',
      duree:'',
      dateD: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
      dateF: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
      menu: false,
      menu2: false,
      msg:''
    }),
    
    computed: {
      user() {
      return JSON.parse(localStorage.getItem('user'));
    },
      nameErrors () {
        const errors = []
        if (!this.$v.entrName.$dirty) return errors
        !this.$v.entrName.maxLength && errors.push('Name must be at most 30 characters long')
        !this.$v.entrName.required && errors.push('Name is required.')
        return errors
      },
      addressErrors () {
        const errors = []
        if (!this.$v.adrs.$dirty) return errors
        !this.$v.adrs.required && errors.push('address is required')
        return errors
      },
      phoneErrors () {
        const errors = []
        if (!this.$v.tel.$dirty) return errors
        !this.$v.tel.maxLength && errors.push('phone must be 10 characters long')
        !this.$v.tel.minLength && errors.push('phone must be 10 characters long')
        !this.$v.tel.required && errors.push('phone is required.')
        return errors
      },
      nameRespErrors () {
        const errors = []
        if (!this.$v.resFirstName.$dirty) return errors
        !this.$v.resFirstName.maxLength && errors.push('Name must be at most 10 characters long')
        !this.$v.resFirstName.required && errors.push('Name is required.')
        return errors
      },
      fNameErrors () {
        const errors = []
        if (!this.$v.resLastName.$dirty) return errors
        !this.$v.resLastName.maxLength && errors.push('Family Name must be at most 30 characters long')
        !this.$v.resLastName.required && errors.push('Family Name is required.')
        return errors
      },
      emailErrors () {
        const errors = []
        if (!this.$v.resEmail.$dirty) return errors
        !this.$v.resEmail.email && errors.push('Must be valid e-mail')
        !this.$v.resEmail.required && errors.push('e-mail is required')
        return errors
      },
      themeErrors () {
        const errors = []
        if (!this.$v.theme.$dirty) return errors
        !this.$v.theme.required && errors.push('theme is required')
        return errors
      },
      dureeErrors () {
        const errors = []
        if (!this.$v.duree.$dirty) return errors
        !this.$v.duree.required && errors.push('duree is required')
        return errors
      },
      dateDebErrors () {
        const errors = []
        if (!this.$v.dateD.$dirty) return errors
        !this.$v.dateD.required && errors.push('date Debut is required')
        return errors
      },
      dateFinErrors () {
        const errors = []
        if (!this.$v.dateF.$dirty) return errors
        !this.$v.dateF.required && errors.push('date Fin is required')
        return errors
      },
    },

    methods: {
      async submit () {
        try{
    await api.post('/creerDemande', {
      idEtud :this.user.id_Etud,
      entrName: this.entrName,
      adrs: this.adrs,
      tel: this.tel,
      resLastName: this.resLastName,
      resFirstName: this.resFirstName,
      resEmail: this.resEmail,
      theme: this.theme,
      duree: this.duree,
      dateD: this.dateD,
      dateF: this.dateF,
      })
      .then(response => {
        this.msg = response.data.msg;
        this.showAlertCreateModel=!this.showAlertCreateModel
        this.dialog=!this.dialog
      })
  }catch(error) {
        console.log(error)
      }
      this.$v.$touch();
      },
      clear () {
        this.$v.$reset()
        this.entrName = ''
        this.resFirstName=''
        this.resLastName=''
        this.tel= ''
        this.resEmail = ''
        this.adrs = ''
        this.theme = ''
        this.duree=''
        this.dateF=''
        this.dateD=''
      },

    },

  }
</script>

<style scoped>

  .categoriesTitle{
    font-size: 20px;
    font-family: Outfit;
    font-weight: 590;
    color: #120030;
  }
  .bigTitle{
    font-size: 40px;
    font-family: Outfit;
    font-weight: 600;
    color: #63D693;
  }

</style>
<template>
  <v-dialog class="overflow-hidden" v-model="dialog" max-width="900" >

    <attestModel 
      :header="header"
      :msg="msg"
      :finalNote="finalNote"
      :id_Etud="selectedItem.id_Etud"
      :id_Stage="selectedItem.id_Stage"
      v-if="showAlertAttest"
      @close="showAlertAttest = false"
      @click:outside="showAlertAttest = false"/>


    <v-card class="px-5 py-5" style="border-radius: 10px; max-height: 600px;" >
        <h1 class="header text-center ">Vous allez prendre note de l'étudiant 
          <span>{{ selectedItem.nom_Etud }} {{ selectedItem.pre_Etud }}</span></h1>
          <v-divider class="mb-5 "></v-divider>
          <form>
            <v-row>
              <v-col md="3"><h2 class="typo1">Dicipline</h2></v-col>
              <v-col md="9">
                <v-select
                  v-model="discipline"
                  label="Dicipline"
                  :items="notes"
                  dense
                  outlined
                  @input="$v.discipline.$touch()"
                  @blur="$v.discipline.$touch()"
                ></v-select>
              </v-col>
            </v-row>

            <v-row>
              <v-col md="3"><h2 class="typo1">Aptitudes</h2></v-col>
              <v-col md="9">
                <v-select
                  v-model="attitude"
                  :items="notes"
                  label="Aptitudes"
                  dense
                  outlined
                  @input="$v.attitude.$touch()"
                  @blur="$v.attitude.$touch()"
                ></v-select>
              </v-col>
            </v-row>
        
            <v-row>
              <v-col md="3"><h2 class="typo1">Initiative</h2></v-col>
              <v-col md="9">
                <v-select
                  v-model="initiative"
                  :items="notes"
                  label="Initiative"
                  dense
                  outlined
                  @input="$v.initiative.$touch()"
                  @blur="$v.initiative.$touch()"
                ></v-select>
              </v-col>
            </v-row>
        
            <v-row>
              <v-col md="3"><h2 class="typo1">Capacités</h2></v-col>
              <v-col md="9">
                <v-select
                  v-model="capacite"
                  :items="notes"
                  label="Capacités"
                  dense
                  outlined
                  @input="$v.capacite.$touch()"
                  @blur="$v.capacite.$touch()"
                ></v-select>
              </v-col>
            </v-row>
        
            <v-row>
              <v-col md="3"><h2 class="typo1">Connaissances</h2></v-col>
              <v-col md="9">
                <v-select
                  v-model="connaissance"
                  :items="notes"
                  label="Connaissances"
                  dense
                  outlined
                  @input="$v.connaissance.$touch()"
                  @blur="$v.connaissance.$touch()"
                ></v-select>
              </v-col>
            </v-row>
        
        <v-layout class="mb-3">
          <v-spacer></v-spacer>
    <v-btn 
      class="mt-4 mr-4" 
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
      Noter
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
import attestModel from "./attestModel.vue";
export default {
  components:{
    attestModel,
  },
    mixins: [validationMixin],
    name: "notationModel",
    props: {
        selectedItem: Object,
    },
    validations: {
        discipline: { required, },
        attitude: { required, },
        initiative: { required, },
        capacite: { required, },
        connaissance: { required, },
    },
    data() {
        return {
            dialog: true,
            notes: [
                0,
                0.25,
                0.5,
                0.75,
                1,
                1.25,
                1.5,
                1.75,
                2,
                2.25,
                2.5,
                2.75,
                3,
                3.25,
                3.5,
                3.75,
                4,
                ],
            showAlertAttest:false,
            discipline: "",
            attitude: "",
            initiative: "",
            capacite: "",
            connaissance: "",
            msg: "",
            finalNote:null,
            header: "",
        };
    },
    methods: {
        async submit() {
            this.$v.$touch();
            try {
                await api.post("/noterEtud", { id: this.selectedItem.id_Etud, 
                  discipline: this.discipline, 
                  attitude: this.attitude, 
                  initiative: this.initiative, 
                  capacite: this.capacite, 
                  connaissance: this.connaissance
                })
                    .then(response => {
                    console.log(response);
                    this.header='Note Envoyer!'
                    this.msg=response.data.msg
                    this.finalNote=response.data.finalNote
                    this.showAlertAttest=!this.showAlertAttest
                });
            }
            catch (error) {
                console.log(error);
            }
        },
        clear() {
            this.$v.$reset();
            this.discipline = "";
            this.attitude = "";
            this.initiative = "";
            this.capacite = "";
            this.connaissance = "";
        },
    },
}

</script>

<style scoped>
.header{
    padding: 10px 0 ;
    font-size: 30px;
    font-family:'Outfit';
    color: #120030;
  }
  .header span{

    color: #63D693;
  }
  .typo1{
    margin-left: 10px;
    font-family: Outfit;
    font-size: 28px;
    color: #120030;
  }
</style>
<template>
  <div class="container">
    <respHeader title="Notification" toPage="./accueilResp"/>
      <v-row>
        <v-col>
          <v-text-field
    placeholder="chercher notification"
    filled
    color="#666"
    rounded
    dense
    class="search"
    append-icon="mdi-magnify"
  ></v-text-field>
        </v-col>
      </v-row>

      <emptyArrays v-if="notifs.length === 0" 
      message="Aucune notification disponible"
      :source="imagePath"
      />
    <v-container grid-list-xs>
      <v-row>
        <v-col>
          <div v-for="notif in notifs" :key="notif">
            <h3 class="text-caption text-right"> {{ notif.timeStamp }}</h3>
            <v-alert
      border="left"
      outlined
      :type="getAlertType( notif.message)"
      text
    >
    <v-row>
      {{ notif.message }}
      <v-spacer></v-spacer>
      <v-icon class="mr-3" color="info"
      @click="deleteNotif(notif.id_Notification)"
      >mdi-close-circle</v-icon>
    </v-row>
    </v-alert>
          </div>
          
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import api from '@/api.js';
import respHeader from "@/components/respHeader.vue";
import EmptyArrays from '@/components/emptyArrays.vue';
export default {
  components:{
    respHeader,
    EmptyArrays,
  },
  data(){
    return{
      notifs :[],
      imagePath: require('@/assets/empty/4.png'),
    }
  },
  methods:{
    getAlertType(message){
      if (message.includes('accept')&&!message.includes('refus')) {
        return 'success'
      }
      else if (message.includes('refus')&&!message.includes('accept')) {
        return 'error'
      }
      else
      return 'info'
    },
    async getNoitfs(){
      try{
       await api.post('/respNotif', {id:this.user.id_Resp})
      .then(response => {
        this.notifs = response.data;
      })
    }
    catch(error) {
        console.log(error);
      }
    },
    async seeNotifs(){
      try{
       await api.post('/seeRespNotif', {id:this.user.id_Resp})
      .then(response => {
        console.log(response)
      })
    }catch(error) {
        console.log(error);
      }
    },
    async deleteNotif(idNo){
      try{
       await api.post('/suppNotif', {idN:idNo})
       setTimeout(function() {
            location.reload();
        }, 200);
       return true
      }
    catch(error) {
        console.log(error);
      }
      
    },
  },
computed:{
  user() {
      return JSON.parse(localStorage.getItem('user'));
    }
},
  async mounted(){
    await this.getNoitfs();
    
  },
  async created(){
    await this.seeNotifs();
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

</style>
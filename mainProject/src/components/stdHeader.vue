<template>
    <div class="nav mt-5">

        <div class="headerDiv"><h1 class="header">
            <span>{{title.substring(0,1)}}</span>{{title.substring(1)}}</h1></div>

            <router-link class="router-link" :to="toPage">
                <v-badge v-if="stdNoSeenNotif" right :content="stdNoSeenNotif" color="red">
                    <v-icon class="icon" color="#120030" size="33">{{ icon1 }}</v-icon>
                </v-badge>
                <v-icon v-else class="icon" color="#120030" size="33">{{ icon1 }}</v-icon>
            </router-link>

        <v-avatar size="45">
            <img src="@/assets/StudentProfilePic.jpg" alt="alt">
        </v-avatar>

        <router-link class="router-link" to="">
                <v-icon @click="logout" class="icon" color="#120030" size="36">mdi-logout</v-icon>
            </router-link>
    </div>
 
</template>

<script>
import api from "@/api";
export default {

    data(){
        return{
            stdNoSeenNotif:null,
        }
    },
props:['title', 'icon1','toPage'],
async mounted(){
        this.fetchNoSeenEtudNotif()
    },
    computed:{
        user() {
            return JSON.parse(localStorage.getItem('user'));
        }
    },
    methods:{
        async fetchNoSeenEtudNotif(){
            try{
                await api.post('/unseenStudentNotifNbr', {id:this.user.id_Etud})
                .then(response => {
                    console.log(response)
                    this.stdNoSeenNotif = response.data;
                })
            }catch(error) {
                console.log(error);
            }
        },
        async logout(){
            try {
        await api.post("/logout")
        .then((response) => {
            console.log(response)
          this.msg=response.data
          this.showAlertLogout=!this.showAlertLogout
          localStorage.clear();
          window.location.href = "/";
        });
      } catch (error) {
        console.log(error);
      }
        },
        seeNotifs(){}
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
.router-link{
text-decoration: none;
}

.nav{
position: relative;
display: flex;
justify-content: space-evenly;
align-items: center;
margin-bottom: 25px;
}
.nav .headerDiv{
margin-left: 30px;
width: 70%;
margin-right: 10%;
}
.nav .router-link{
margin:0 50px;
}
.icon:hover{
    color: #63D693 !important;
}

</style>
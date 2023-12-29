<template>
    <nav class="sidebar">
        <header>
            <div class="img-text">
                <span class="image">
                    <img class="img" src="@/assets/iconStageasy.png">
                </span>

                <div class="text header-text">
                    <span class="name">stageasy</span>
                    <span class="underName">make it easy</span>
                </div>
            </div>

            <v-icon class="toggle">mdi-chevron-right</v-icon>
        </header> 

        <v-divider style="margin: 20px 20px;"></v-divider>
        <div class="userinfo">
            <p class="fullName">{{user.nom_Etud}} {{user.pre_Etud}}{{user.nom_ChefDep}} {{user.pre_ChefDep}}{{user.nom_Resp}} {{user.pre_Resp}}</p>
            <v-list-item-subtitle>{{ type }}</v-list-item-subtitle>
            <br>
        </div>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li v-for="(navItem,index) in navItems "
                    :key="index"
                    >
                    <router-link class="a" :to="navItem.to">
                                <v-icon class="icons">{{navItem.icon}}</v-icon>
                                <span class="text nav-text">{{ navItem.title }}</span>
                            </router-link>
                    </li>   
                </ul>
            </div>
            <div class="bottom-content" @click="logout">
                <li class="nav-link deconn">
                        <a>
                            <v-icon class="icons" style="transform: rotateY(180deg);">mdi-logout</v-icon>
                            <span class="text nav-text">Se Deconnecter</span>
                        </a>
                    </li>
            </div>
        </div>
        <alertCreateAccount v-if="showAlertLogout" :msg="msg" header="Disconnecting"/>
    </nav>
</template>

<script>
import api from '@/api'
import alertCreateAccount from './alertCreateAccount.vue';
export default {
  components: { alertCreateAccount },

    data(){
        return{
            msg:'',
            showAlertLogout:false,
            goBack:'',
        }
    },
    props:['navItems'],
    computed:{
        user() {
        return JSON.parse(localStorage.getItem('user'));
        }
    },
    created(){
        this.type = localStorage.getItem('type');
    },  
    methods:{
        async logout(){
            try {
        await api.post("/logout")
        .then((response) => {
            console.log(response)
          this.msg=response.data.msg
          this.showAlertLogout=!this.showAlertLogout
          localStorage.clear();
          window.location.href = "/";
        });
      } catch (error) {
        console.log(error);
      }
        }
    }
}
</script>

<style scoped>

.sidebar{
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 250px;
    padding: 10px 15px;
    background: white;
}

.img{
    padding-top: 11px;
    width: 50px !important;
    
}
.img-text{
    display: flex;
    align-items: center;
}
.header-text{   
    margin-left: 20px;
    display: flex;
    flex-direction: column;
}
.header-text .name{
    font-family: Outfit;
    font-weight: 700;
    font-size: 20px;
    color: #120030;
    letter-spacing: 1px;
}
.header-text .underName{
    font-family: Outfit;
    font-weight: 00;
    font-size: 20px;
    color: #a4a4a4;
}

.sidebar header .toggle{
    position: absolute;
    top: 4%;
    right: -25px;
    transform: translate(-50%);
    height: 25px;
    background: #120030;
    border-radius: 50%;
    color: white;
}
.sidebar li{
    height: 50px;
    list-style: none;
    display: flex;
    align-items: center;
    font-family: Outfit;
    font-size: 19px;
}
.sidebar li .icons{
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    margin-right: 20px;
}
.sidebar li .icons,
.sidebar li .text {
    color: #120030;
    transition: all 0.2s ease;
    
}

.sidebar li a{
    text-decoration: none;
    width: 100%;
    display: flex;
    align-items: center;
    height: 100%;
    transition: all 0.4s ease;
    border-radius: 8px;
}
.sidebar li a:hover ,.router-link-exact-active{
    background: #63d69384;
    color: white;

}
.router-link-exact-active{
    background: #63D693;
}
.sidebar li a:hover .icons,
.sidebar li a:hover .text,
.sidebar li a:hover{
    color: white;
}
.a .router-link-exact-active{
  color: white;
}
.sidebar ul{
    padding: 0;
}
.fullName{
      font-family: 'Outfit';
      font-weight: 600;
      font-size: 30px;
      line-height: 30px;
      color: #120030;
    }

    .sidebar .menu-bar{
        /* height: calc(100% - 240px); */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .deconn{
        position: absolute;
        width: 88%;
        bottom: 20px;
    }
</style>
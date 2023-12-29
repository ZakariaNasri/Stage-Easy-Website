<template>
    <!-- The dialog box -->
    <v-dialog v-model="dialog" max-width="900">
      <v-card>
        <v-container>
          <v-row>
            <!-- Left side of the dialog box: image and login form -->
            <v-col cols="7" class="px-0 py-0">
              <v-img
                src="../assets/loginImage.png"
                alt="Login Image"
                contain
                style="border-radius: 5px;"
              ></v-img>
            </v-col>
            <v-col cols="5" class="rightInfo" :class="{'wrongCredentials':wrong===true}">
              <v-card-title class="title text-center">
                <p>Sois sûr que votre email doit être <br>
                    un email professionnel appartenant <br>
                    à l'université constantine 2 !</p>
                <h1><span>L</span>ogin</h1>
              </v-card-title>
              <!-- Login form -->
              <v-form>
                <v-text-field
                  v-model="login.email"
                  label="Email"
                  color="#63D693"
                  prepend-icon="mdi-email"
                  required
                  type="email"
                ></v-text-field>
                <v-text-field
                  v-model="login.password"
                  label="Password"
                  prepend-icon="mdi-lock"
                  required
                  color="#63D693"
                  type="password"
                ></v-text-field>
                  <v-alert :type="alertType" v-show="showLoginAlert" value="Succes" outlined class="shake">
                    {{ errorMsg }}
                  </v-alert>
                <v-card-actions>
                  <v-btn color="#63D693" class="btn" @click="logIn" rounded block :loading="isLoading">Login</v-btn>
                  
                </v-card-actions>
              </v-form>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
  </template>

<script>
import { mapActions } from 'vuex';
import api from '@/api'
export default {
    
    data() {
    return {
        show:false,
        dialog: true,
        login:{
        email: '',
        password: '',
        },
        token:'',
        isLoading: false,
        errorMsg:'',
        alertType:'',
        showLoginAlert:false,
        wrong:false,
        userInfo:[],
    }
  },
  methods: {
    async logIn() {
       this.isLoading=!this.isLoading
            try {
                this.wrong=false
                this.showLoginAlert=false
                const response = await api.post('/login', {
                    email: this.login.email,
                    password: this.login.password
                    

                })
                this.alertType='success'
                this.showLoginAlert=true
                this.loggedIn = true
                this.token = response.data.authorisation.token
                console.log(response.data)
                localStorage.setItem('token', response.data.authorisation.token);
                localStorage.setItem('type', response.data.authorisation.type);
                localStorage.setItem('user', JSON.stringify(response.data.user));
                if (response.data.status==='success') {
                  this.userInfo = response.data.user;
                  this.$store.dispatch('updateUserInfo', this.userInfo);
                  
                  if(response.data.authorisation.type==='étudiant'){
                    this.$router.push('/accueilStd')
                  }
                  else if (response.data.authorisation.type==='Responsable') {
                    this.$router.push('/accueilResp')
                  }
                  else{
                    this.$router.push('/accueilChef')
                  }
                }
                this.isLoading=!this.isLoading
                return response.data;
            } catch (error) {
              this.wrong=true
              this.alertType='error'
              this.showLoginAlert=true
                console.log(error.response.data.message);
                this.errorMsg = error.response.data.message
                this.isLoading=!this.isLoading

            }},
            ...mapActions(['updateUserInfo'])
  },
}
</script>

<style scoped>
.router-link{
  width: 100%;
  text-decoration: none;
}
.btn{
  padding-left: 90%;
  padding-right: 90%;
  text-decoration: none;
}
.title h1{
    margin-left: 25%;
    font-size: 60px;
    color: #120030;
    margin-bottom: 20px;
}
.title h1 span{
    color: #63D693;
}
.title p{
    opacity: .5;
    font-size: 10px;
    line-height:15px;
    margin: 0 auto 30px;

}

.rightInfo{
    
    padding: 0 30px;
}
@keyframes shake {
  0% { transform: translateX(0); }
  20%, 40%,60%,80%{ transform: translate(.8em);
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.129);
  }
  10%,30%,50%{transform: translateX(-.2em);}
  70%,100%{transform: translateX(0);}

}

.shake {
  animation: shake 0.6s cubic-bezier(0.36, 0.07, 0.19, 0.97);
}
</style>
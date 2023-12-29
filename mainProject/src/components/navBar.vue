<template>
    <v-app-bar
        app
        flat
        color="#ffffff"
        class="navBar"
        elevation="2"
        v-show="showAppBar"
        :class="{
            'app-bar-transition': appBarTransition,
            'app-bar-hidden': !showAppBar
        }"
    >
    <div class="mainLogoContainer">
            <img src="../../../../forAmine/used/PNG/IA.png" class="mainLogo">
    </div>
    <v-spacer></v-spacer>
    <v-spacer></v-spacer>
    <v-spacer></v-spacer>
    <v-spacer></v-spacer>
    <v-layout row justify-space-between>
        <v-btn text class="navOption" @click="scroll('home')">Home</v-btn>
        <v-btn text class="navOption" @click="scroll('about')">About us</v-btn>
        <v-btn text class="navOption" @click="scroll('contact')">contact</v-btn>
        <v-btn text class="navOption" to="/login" >Login</v-btn>
        <v-btn text class="navOption spec" >Get started </v-btn>
    </v-layout>
    </v-app-bar>
</template>

<script>
    export default {
        data() {
            return {
                lastScrollPosition: 0,
                showAppBar: true,
                appBarTransition: false,
            };
        },
        mounted() {
            window.addEventListener("scroll", this.handleScroll);
        },
        beforeDestroy() {
            window.removeEventListener("scroll", this.handleScroll);
        },
        methods: {
            scroll(id) {
                const element = document.getElementById(id);
                element.scrollIntoView({ behavior: "smooth" });
            },
            handleScroll() {
                const currentScrollPosition = window.pageYOffset;
                if (currentScrollPosition > this.lastScrollPosition) {
                  // Scrolling down
                    this.showAppBar = false;
                    this.appBarTransition = true;
                } else {
                  // Scrolling up
                    this.showAppBar = true;
                    this.appBarTransition = true;
                }
                this.lastScrollPosition = currentScrollPosition;

            },
        },
    };
</script>

<style>
.navBar {
    padding: 0 100px;
}
.app-bar-transition {
    transition: opacity .5s ease-in-out;
}

.app-bar-hidden {
    transform: translateY(-100%);
}
.mainLogoContainer{
    margin: 0 0 0 10px;
    width:70px;
    height: 50px;
}
.mainLogo{
    width: 50px;
    height: 40px;
    margin-top: 5px;
}
.navOption{
    color: #120030;
    border-radius: 50px;
}
.navOption:hover{
    color: #3a3849;
    background-color: #f7ab3e;
    transition: .4s;
}
.spec{
    color: #3a3849;
    background-color: #f7ab3e;
}
.spec:hover{
    color: white;
    background-color: #f58700;
}
.colors{
    color: #f7ab3e;
    color: #3a3849;
  }
</style>

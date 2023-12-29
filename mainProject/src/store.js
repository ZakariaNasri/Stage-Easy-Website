import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state:{
        userInfo:[],
        user:['zaki' ,'moh']
    },
    mutations:{
        setUserInfo(state, userInfo) {
            state.userInfo = userInfo
        }
    },
    actions: {
        updateUserInfo(context, payload) {
          context.commit('setUserInfo', payload);
        }
      },
      getters:{
        getUserId(state){
            return state.userInfo.id_Etud
        }
      }
});
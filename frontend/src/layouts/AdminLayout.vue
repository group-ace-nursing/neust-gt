
<template>
    <q-layout view="lHh LpR lFf">
  
      <q-header reveal bordered class="custom-bg-color-orange">
        <q-toolbar>
          <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
          <q-space />
          <q-btn class="q-mr-sm" round dense flat icon="history_edu"></q-btn>
          <q-btn class="q-mr-sm" round dense flat icon="notifications">
              <q-badge floating color="red" rounded transparent>
                  3
              </q-badge>
          </q-btn>
        </q-toolbar>
      </q-header>
      
  
      <q-drawer 
        show-if-above 
        v-model="leftDrawerOpen" 
        side="left" 
        bordered
      >
        <!-- drawer content -->
        <Profile v-bind="userProfile" />
        <q-separator dark />
        <SideNav 
          v-for="link in filteredMenus"
          :key="link.title"
          v-bind="link"
        />
      </q-drawer>
  
      <q-page-container>
        <q-page class="q-pa-sm">
          <div style="height: 88vh;">
            <router-view />
          </div>
        </q-page>
      </q-page-container>
  
      <!-- <q-footer reveal class="bg-grey-5 text-weight-thin text-blue-white-9 text-center q-pt-lg q-pb-lg">
        <div>{{ `Centralize Distribution and Sales Inventory System ©2023 Created by FWDS` }}</div>
      </q-footer> -->
  
    </q-layout>
  </template>
  
  <script>
  import { LocalStorage, SessionStorage } from 'quasar'
  import jwt_decode from 'jwt-decode'
  import SideNav from '../components/Templates/Sidenav.vue';
  import Profile from '../components/Templates/Profile.vue';
  import Crumbs from '../components/Templates/Breadcrumbs.vue';
  
  const linksList = [
    // Users
    {
      title: 'Home',
      icon: 'dashboard',
      link: 'dashboard',
      code: 2,
    },
    
  ];
  
  export default {
    data(){
      return {
        linksList,
        userProfile: null,
        leftDrawerOpen: true,
        miniState: false
      }
    },
    mounted(){},
    components:{
      SideNav,
      Profile,
      Crumbs
    },
    computed: {
      filteredMenus: function(){
        return this.linksList;
      },
      userDetails(){
        let token = LocalStorage.getItem('userData');
        
        if(token){
          let decode = jwt_decode(token)
          return decode
        } 
  
        return {}
      }
    },
    created(){
    },
    methods: {
      toggleLeftDrawer () {
        this.leftDrawerOpen = !this.leftDrawerOpen
      },
      logout(){
        localStorage.removeItem('userData');
        this.$router.push('/')
      }
    }
  }
  </script>
  
  <style scoped>
  .custom-bg-color-orange{
    color: #000;
    background: #eabc76 !important;
  }
  </style>
<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated class="bg-white">
      <q-toolbar class="custom-bg-color-orange" inset>
        <!-- <q-space v-if="!$q.screen.xs" />
        <q-tabs
          :class="$q.screen.xs ? 'mobileViewMenu' : ''" 
          inline-label 
          mobile-arrows 
          outside-arrows
          v-model="menuSelected"
        >
          <q-tab  name="home" label="Home" />
          <q-tab name="slides" label="About" />
          <q-tab name="portfolio" label="Features" />
          <q-tab name="doctors" label="Visualization" />
        </q-tabs>
        <q-space v-if="!$q.screen.xs" /> -->
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { LocalStorage, SessionStorage } from 'quasar'
import jwt_decode from 'jwt-decode'
import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'MainLayout',
  data(){
    return {
      menuSelected: 'home',
      contacts: [
        {
          icon: 'contact_phone',
          iconColor: 'orange',
          title: 'Call',
          value: '(0987) 654 3214',
        },
        {
          icon: 'schedule',
          iconColor: 'blue',
          title: 'Working Time',
          value: 'Mon - Fri 7:00 AM - 6:00 PM',
        },
        {
          icon: 'location_on',
          iconColor: 'red',
          title: 'Address',
          value: 'Lorem Ipsum Address Sample Test',
        },
      ]
    }
  },
  created(){
    let profile = LocalStorage.getItem('userData');

    if(profile){
      this.userProfile = jwt_decode(profile);
      if(Number(this.userProfile.utype) === 2){
        this.$router.push('user/dashboard')
      } else {
        this.$router.push('admin/dashboard')
      }
          
    } else {
      this.$router.push('/')
    }
    
  },
  methods: {
    scrollHandler(val){
      // some actiom
    }
  }
})
</script>

<style scoped>
.imageSmall{
  width: 2%;
}
.imageMedium{
  width: 50%;
}
.searchSmall{
  width: 30%;
}
.searchMedium{
  width: 70%;
}
.mobileViewMenu{
  width: 85%;
}
.custom-bg-color-orange{
  color: #000;
  background: #eabc76 !important;
}


</style>

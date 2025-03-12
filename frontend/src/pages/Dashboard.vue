<template>
  <div >
    <Dashboard />
  </div>
</template>

<script>
import { LocalStorage } from 'quasar'
import { defineComponent } from 'vue';
import jwt_decode from 'jwt-decode'
import Dashboard from '../components/Dashboard/Index';

export default defineComponent({
  name: 'PageDashboard',
  components: {
    Dashboard
  },
  created(){
    
  }
})
</script>

<template>
  <div>
    <div v-if="!askUserType" class="row">
      <div class="col-12 text-grey-8 q-mb-md q-mt-lg">Please select what kind of user you to be to access the ASCOT Scholarship Information System?</div>
      <div class="col-12 text-center">
        <div class="q-gutter-sm">
          <q-radio 
            v-model="isStudent" 
            checked-icon="task_alt" 
            unchecked-icon="panorama_fish_eye" 
            val="student" 
            label="Graduate Student of NEUST Nursing" 
          />
          <q-radio 
            v-model="isStudent" 
            checked-icon="task_alt" 
            unchecked-icon="panorama_fish_eye" 
            val="staff" 
            label="I am Staff of NEUST" 
          />
        </div>
      </div>
      <div class="col-12">
        <q-checkbox 
          v-model="rememberAnswwer" 
          label="Remember my Answer"
          size="sm"
        />
      </div>
      <div class="col-12 q-mt-lg">
        <q-btn
            class="float-right btn-custom-border" 
            unelevated
            rounded
            no-caps 
            color="primary" 
            label="Continue"
            @click="askUserType = true"
        />
      </div>

    </div>
    <div v-if="askUserType" class="fit row wrap justify-center items-center content-center q-pa-xl">
        <div class="col-12 text-grey-8">Embrace the new year with a fresh opportunity!</div>

        <div class="col-12 text-grey-8 q-mt-lg">
            <q-input
                v-model="form.username"
                @keypress.enter="submitLogin"
                :label="'Username'"
                v-bind="formRules.username"
                :placeholder="'Enter Username'"
                outlined  
                stack-label
            >
                <template v-slot:prepend>
                <q-icon name="account_circle" />
                </template>
            </q-input>
        </div>
        <div class="col-12 text-grey-8 q-mt-sm">
            <q-input 
                v-model="form.password"
                v-bind="formRules.password"
                @keypress.enter="submitLogin"
                :type="isPwd ? 'password' : 'text'"
                label="Password"
                placeholder="***********"
                outlined  
                stack-label
            >
                <template v-slot:prepend>
                  <q-icon name="lock" />
                </template>
                <template v-slot:append>
                  <q-icon
                    :name="isPwd ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer"
                    @click="isPwd = !isPwd"
                  />
                </template>
            </q-input>
        </div>
        <div class="col-12 q-mt-sm">
            <q-checkbox v-model="keepLogin" label="Remember me" />
            <q-btn class="float-right" flat color="orange" no-caps label="Switch User" @click="changeUserType"  />
        </div>
        <div class="col-12 q-mt-lg">
            <q-btn
                type="submit"
                class="full-width q-pa-md btn-custom-border" 
                unelevated
                :loading="loginLoad"
                no-caps 
                color="primary" 
                label="Login"
                @click="submitLogin"
            />
        </div>
    </div>
  </div>
</template>

<script>
import { LocalStorage } from 'quasar'

export default {
  name:"LoginComponent",
  data() {
    return {
      tab: "login",
      isPwd: true,
      keepLogin: false,
      loginLoad: false,
      askUserType: false,
      rememberAnswwer: false,
      isStudent: "student",
      form: {
        username: "",
        password: "",
      },
      formRules: {
        username: {
          rules: [
            value => !!value || 'This field must be filled!',
          ]
        },
        password: {
          rules: [
            val => !!val || 'This field must be filled!',
          ]
        },
      },
    }
  },
  watch:{
    keepLogin(newVal){
      if(newVal){
        LocalStorage.set('rememberUserName', this.form.username);
      } else {
        LocalStorage.remove('rememberUserName');
      }
    },
    rememberAnswwer(newVal){
      if(newVal){
        LocalStorage.set('rememberUserType', this.isStudent);
      } else {
        LocalStorage.remove('rememberUserType');
      }
    }
  },
  created(){
    this.checkRememberUserType().then(() => {
      this.checkRememberUser()
    })
  },
  methods: {
    changeUserType(){
      LocalStorage.remove('rememberUserType');
      this.askUserType = false
    },
    async checkRememberUserType(){
      let remUser = LocalStorage.getItem('rememberUserType')

      if(remUser){
        this.isStudent = remUser
        this.askUserType = true
      }
    },
    async checkRememberUser(){
      let remUser = LocalStorage.getItem('rememberUserName')

      if(remUser){
        this.form.username = remUser
        this.keepLogin = true
      }
    },
    async submitLogin(){
      this.$q.loading.show();
      this.loginLoad = true;
      let vm = this;
      let payload = vm.form;

      this.$api.post('auth/login', payload).then(async (response) => {
        const data = {...response.data};
        if(!data.error){
          await LocalStorage.set('userData', data.jwt);
          if(Number(data.utype) === 2){
            this.$router.push('user/dashboard')
          } else {
            this.$router.push('admin/dashboard')
          }
          
        } else {
          this.$q.notify({
            position: 'top-left',
            type: 'negative',
            message: data.title,
            caption: data.message,
            icon: 'report_problem'
          })
        }
      })

      this.loginLoad = false;
      this.$q.loading.hide();
    }
  }
}
</script>

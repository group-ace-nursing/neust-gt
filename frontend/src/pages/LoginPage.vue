<template>
    <div class="row q-mt-md">
      <div class="col-12 col-md-7 q-pa-sm">
        <q-card
          flat
          class="my-card bg-white"
        >
          <q-card-section>
            <q-img :src="`/imgs/banner.png`" />
            <div class="row">
                <div class="col col-12">
                    <h4>About the Graduate Tracer</h4>
                    The NEUST College of Nursing Graduate Tracer is designed to track and analyze the career progress of nursing graduates. This initiative seeks to assess employment outcomes, job satisfaction, and the alignment of the nursing curriculum with real-world professional demands. By gathering these insights, we aim to improve nursing education and ensure that our graduates are well-equipped for the evolving healthcare landscape.
                </div>
                <div class="col col-12">
                    <h4>About Us</h4>
                    We are ACES – Alliance for Care, Excellence, and Safety, a dedicated team from BSN 3-C committed to advancing nursing education through research and innovation. Our mission is to contribute to the improvement of nursing practices by assessing graduate outcomes and ensuring that our curriculum remains relevant and effective in preparing future healthcare professionals.
                    <br/><br/>
                    This project builds on the foundation laid by Zentinels, the pioneering researchers who first developed this website. We acknowledge and appreciate their efforts in initiating this vital study, and we are committed to continuing their legacy by expanding and refining the research to benefit both current students and future graduates.
                </div>
                
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-5 q-pa-sm">
        <q-card
          flat
          class="my-card bg-white"
        >
          <q-card-section>
            <q-tabs
              v-model="tab"
              inline-label
              no-caps
              dense
              align="justify"
              indicator-color="warning"
            >
              <q-tab name="login" icon="ti-user" label="Login" />
              <q-tab name="register" icon="ti-write" label="Register" />
            </q-tabs>
  
            <q-tab-panels v-model="tab">
              <q-tab-panel name="login">
                <LoginForm />
              </q-tab-panel>
              <q-tab-panel name="register">
                <RegisterForm @goToLogin="setTab" />
              </q-tab-panel>
            </q-tab-panels>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </template>
  
  <script>
  import moment from 'moment'
  import LoginForm from '../components/Login/Login.vue'
  import RegisterForm from '../components/Login/Registration.vue'
  
  export default {
    name:"IndexPage",
    components:{
      LoginForm,
      RegisterForm
    },
    data() {
      return {
        tab: "login",
        keepLogin: false,
        loginLoad: false,
        tableLoading: false,
      }
    },
    created(){
    },
    methods: {
      moment,
      setTab(){
        this.tab = "login";
      }
    }
  }
  </script>
  
  <style scoped>
  .my-card{
      border-radius: 10px;
      box-shadow: 0px 0px 3px -2px !important;
  }
  </style>
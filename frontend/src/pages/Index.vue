<template>
  <q-page class="">
    <!-- Banner -->
    <q-carousel
      animated
      v-model="slide"
      arrows="false"
      navigation
      infinite
      height="95vh"
    >
      <q-carousel-slide :name="1" img-src="/imgs/banner.png" />
    </q-carousel>

    <!-- Start Content -->
    <div class="row q-mb-xl" style="height: 20vh;">
      <div class="col col-xs-12 col-md-12"></div>
      <div class="col col-xs-2 col-sm-2 col-md-2"></div>
      <div class="col col-xs-10 col-sm-10 col-md-4 flex items-center" style="z-index:999;">
        <!-- <span class="text-bold" :class="$q.screen.xs ? 'text-h3' : 'text-h2' ">WELCOME TO <span class="text-green">TENMEI BACKOFFICE</span></span>
        <span class="text-subtitle1">Trust your pet's health to a reliable clinic. Regular check-ups, vaccination, and emergency care ensure their well-being. Choose quality veterinary services.</span> -->
        
        <q-btn to="login" color="green" size="xl" icon-right="app_registration" label="Get Started" />
        <!-- <q-input rounded outlined stack-label v-model="text" placeholder="Search" style="width: 100%;"/> -->
      </div>
      <div class="col col-xs-6 col-sm-6 col-md-6"></div>

      <!-- <div class="backgroundHeader"></div> -->
    </div>

    <div class="row q-mt-xl q-pl-xl q-pr-xl">
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
  </q-page>
</template>

<script>
import { defineComponent } from 'vue';
import { api } from 'boot/axios'

export default defineComponent({
  name: 'PageIndex',
  components: {},
  data(){
    return {
      text: '',
      slide: 1,
      programs: [
        {
          icon: 'vaccines',
          iconColor: 'orange',
          title: 'Awesome Medicines',
          value: 'Vivamus interdum, mauris interdum quis curdum sodales',
        },
        {
          icon: 'verified',
          iconColor: 'red',
          title: 'Certificate',
          value: 'Etiam risus neque, volutpat vel laoreet a, finibus volutpat non',
        },
        {
          icon: 'assignment_ind',
          iconColor: 'green',
          title: 'Best Doctors',
          value: 'Etiam risus neque, volutpat vel laoreet a, finibus volutpat non',
        },
        {
          icon: 'medical_services',
          iconColor: 'blue',
          title: 'Best Services',
          value: 'Etiam risus neque, volutpat vel laoreet a, finibus volutpat non',
        },
      ],
      announcements: [],
    }
  },
  created(){
    this.getAnnouncements();
  },
  methods:{
    async getAnnouncements(){
      let vm = this;
      
      api.get('announcement/public/getList').then((response)=>{
          let data = {...response.data}

          if(!data.error){
            this.announcements = response.status < 300 ? data.list : [];
          } else {
              this.$q.notify({
                  color: 'negative',
                  position: 'top-right',
                  title:data.title,
                  message: this.$t(`errors.${data.error}`),
                  icon: 'report_problem'
              })
          }
      });
    },
  }
})
</script>

<style scoped>
.adjustableFontHeader {
  font-size-adjust: 0.3 !important;
}
.adjustableFont {
  font-size-adjust: 0.58;
}
.backgroundHeader{
  position: absolute;
  background: url('/imgs/banner.png') no-repeat;
  height: 100dvh;
  width: 100%;
  /* background-position: 50% 60%; */
  background-size: 100%;
  /* opacity: 0.2; */
}
.backgroundMid{
  position: absolute;
  background: url('/imgs/banner.png') no-repeat;
  height: 60vh;
  width: 34%;
  background-position: -25% 66%;
  /* background-size: 60%;
  opacity: 0.2; */
}
.footerBackground{
  height: 30vh;
}
</style>

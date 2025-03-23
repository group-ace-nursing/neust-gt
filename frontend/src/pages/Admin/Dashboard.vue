<template>
    <div >
        <div class="row">
            <div v-if="isLoading" class="col text-center">
                <q-spinner-orbit
                    color="primary"
                    size="3em"
                />
            </div>
            <div v-if="tableRow.length == 0" class="col-12 text-center">
                <noData />
            </div>
            <div v-if="tableRow.length > 0" class="col-12">
                
                <q-table
                    :rows="tableRow"
                    :columns="tableColumns"
                    row-key="name"
                >
                    <template v-slot:top>
                        <label class="text-h4">Response List</label>
                        <q-space />
                    </template>
                </q-table>
            </div>
            <div  class="col-12 q-mt-md">
                <q-table
                    :rows="tableRowNoRes"
                    :columns="tableColumns"
                    row-key="name"
                >
                    <template v-slot:top>
                        <label class="text-h4">Not Yet Responded</label>
                        <q-space />
                    </template>
                </q-table>
            </div>
        </div>
    </div>
  </template>
  
  <script>
  import { LocalStorage } from 'quasar'
  import moment from 'moment';
  import { defineComponent } from 'vue';
  import jwt_decode from 'jwt-decode'
  
  export default defineComponent({
    name: 'PageDashboard',
    data(){
        return {
            tableRow: [],
            tableRowNoRes: [],
        }
    },
    created(){
      this.getSurveyResponses()
      this.getSurveyNoResponses()
    },
    methods: {
        moment,
        getSurveyResponses(){
            this.tableRow = []

            this.$api.post('users/survey/response').then(async (response) => {
                const data = {...response.data};

                this.tableRow = data.list || []
            })
        },
        getSurveyNoResponses(){
            this.tableRowNoRes = []

            this.$api.post('users/survey/noresponse').then(async (response) => {
                const data = {...response.data};

                this.tableRowNoRes = data.list || []
            })
        },
    }
  })
  </script>
  
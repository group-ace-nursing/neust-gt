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
                <q-btn
                    class="q-mb-md"
                    color="primary"
                    label="Export CSV"
                    @click="exportCSV"
                />
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
        async exportCSV(){
            let colmuns = []
            for (const key in this.tableRow[0]) {
                colmuns.push(key)
            }

			const content = [colmuns.map(col => this.wrapCsvValue(col))].concat(
				this.tableRow.map(row => colmuns.map(col => this.wrapCsvValue(
					row[col],
					col.format,
					row
				)).join(','))
			).join('\n')

			const anchor = document.createElement('a');
			anchor.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(content);
			anchor.target = '_blank';
			anchor.download = 'DataReport.csv';
			anchor.click();
		},
		wrapCsvValue (val, formatFn, row) {
			let formatted = formatFn !== void 0
				? formatFn(val, row)
				: val

			formatted = formatted === void 0 || formatted === null
				? ''
				: String(formatted)

			formatted = formatted.split('"').join('""')
			/**
			 * Excel accepts \n and \r in strings, but some other CSV parsers do not
			 * Uncomment the next two lines to escape new lines
			 */
			// .split('\n').join('\\n')
			// .split('\r').join('\\r')

			return `"${formatted}"`
		},
    }
  })
  </script>
  
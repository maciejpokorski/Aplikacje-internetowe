<style scoped>
    .Test-list{
        margin: 0;
        padding: 0;
    }
    .Test-list--element {
        display: flex;
        flex-wrap: no-wrap;
        flex-direction: row;
        justify-content: space-between;
        padding: 5px 10px;
    }
    .Test-list--element-btn{
        background-color: #E75151;
        color: #fff;
    }
    .Test-list--editable{
        padding-right: 5px;
    }
</style>

<template>
    <simple-panel-wrapper title="Go back to tests list"  :href="back_to_tests_href" size="col-md-12" offset="col-md-offset-0">
      <div>
       Name: <editable class="Test" :value.sync='test.name' @change='update(test)'></editable>
      </div>
      <div>
        description: <editable class="Test" :value.sync='test.description' @change='update(test ,0)'></editable>
      </div>
      <div>
        Start date:
       <datepicker class="Test" :format="date_format" v-model="test.start_date" v-on:input='update(test, 1)'></datepicker> 
      </div>
      <div>
        Finish date:
        <datepicker class="Test" :format="date_format"  v-model="test.finish_date" v-on:input='update(test, 1)'></datepicker>
      </div>
        
    </simple-panel-wrapper>
  
</template>

<script>
import Editable from 'vue-xeditable/src/Editable.vue'
import Datepicker from 'vuejs-datepicker';
export default {

  data() {
    return {
      back_to_tests_href: "",
      date_format: 'yyyy-MM-dd',
      test: {
      }
    };
  },

props: [ 'test_id' ],

  mounted() {
    this.getTest();
  },

 components: {
    'editable': Editable,
    'datepicker': Datepicker
  },


  methods: {
    getTest() {
     axios.get("/api/tests/"+this.test_id).then(response => {
         this.test = response.data
         this.back_to_tests_href = "/tests"
      });
    },
    update(test, format_date){
      if(format_date)
        this.prepareDateFormat(test);
      console.log(test);
      return;
      axios({
            method:'put',
            url:'/api/tests/'+test.id,
            data: {
                name: test.name,
                description: test.description,
                start_date: test.start_date,
                finish_date: test.finish_date,
            }
        })
        .then(function(response) {
            
        });
    },
    prepareDateFormat(test){
      test.start_date = new Date(test.start_date);
      test.finish_date = new Date(test.finish_date);
      test.start_date = test.start_date.toISOString().slice(0, 19).replace('T', ' ');
      test.finish_date = test.finish_date.toISOString().slice(0, 19).replace('T', ' ');
    }
  }
};
</script>

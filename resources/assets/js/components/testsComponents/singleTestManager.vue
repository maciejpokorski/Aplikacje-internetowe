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
    <simple-panel-wrapper :title="test.name"  size="col-md-12" offset="col-md-offset-0">
       aaa
    </simple-panel-wrapper>
  
</template>

<script>
import Editable from 'vue-xeditable/src/Editable.vue'
import vSelect from "vue-select"
export default {

  data() {
    return {
      test: {
          
      }
    };
  },

props: [ 'test_id' ],

  mounted() {
    this.getTest();
  },

 components: {
    'editable': Editable
  },


  methods: {
    getTest() {
     axios.get("/api/tests/"+this.test_id).then(response => {
        this.test = response.data
      });
    }
  }
};
</script>

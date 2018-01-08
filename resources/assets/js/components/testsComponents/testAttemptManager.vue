<style scoped>
.Test-list {
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

.right{
  float: right;
}

.Test-list--element-btn {
  background-color: #e75151;
  color: #fff;
}
.Test-list--editable {
  padding-right: 5px;
}
@import "~slick-carousel/slick/slick.css";
</style>
<template>
    <simple-panel-wrapper :title="test.name" size="col-md-12" offset="col-md-offset-0">
    <h1 v-if="show_r">
      Result: <strong>{{score}}</strong>
    </h1>
    <slick v-if="Object.keys(test).length > 0" ref="slick" :options="slickOptions">
      <div v-for="question in test.questions">
        <single-question-manager :enableUpdates="false" :title="question.name" :href="null" :question_id='question.id'>
        
         <answer-manager slot="answers" :user_attempt_id='user_attempt_id' :show_results="show_r" :test_id="test.id" :enableUpdates="false" :question_id="question.id"></answer-manager>
        </single-question-manager>
    </div>
    </slick>
     <button v-if="!show_r && !user_attempt_id" @click="finish(1)" type="button" class="btn right btn-success">
          <span class="glyphicon glyphicon-send" aria-hidden="true">
          </span>
          Finish and see results
    </button>
    </simple-panel-wrapper>
    
</template>

<script>
import Slick from "vue-slick";
import SingleQuestionManager from "./singleQuestionManager";
import AnswerManager from "./answerManager";
export default {
  data() {
    return {
      test: {},
      show_r: false,
      test_answers: [],
      score: 0,
      slickOptions: {
        slidesToShow: 1,
        dots: true,
        infinite: false
      }
    };
  },

  mounted() {
    this.checkIfAlreadyFilled();
    this.show_r = this.show_results;
  },

  components: {
    Slick,
    SingleQuestionManager,
    AnswerManager
  },

  props: ["test_id","show_results",'user_attempt_id'],

  methods: {
    getTest() {
      axios.get("/api/tests/" + this.test_id).then(response => {
        this.test = response.data[0];
      });
    },

    finish(){
      this.checkIfAlreadyFilled(1);
    },

   checkIfAlreadyFilled(save = 0){
    axios.post("/api/tests/save-attempt/" + this.test_id, {save: save}).then(response => {
       if(response.data.is_finished && !this.show_r){
         this.show_r = true;
         this.score = response.data.score;
       }
    });
    this.getTest();
   },

    next() {
      this.$refs.slick.next();
    },

    prev() {
      this.$refs.slick.prev();
    },

    reInit() {
      // Helpful if you have to deal with v-for to update dynamic lists
      this.$nextTick(() => {
        this.$refs.slick.reSlick();
      });
    }
  }
};
</script>

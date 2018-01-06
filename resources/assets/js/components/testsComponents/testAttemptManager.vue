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
    <slick v-if="Object.keys(test).length > 0" ref="slick" :options="slickOptions">
      <div v-for="question in test.questions">
        <single-question-manager :enableUpdates="false" :title="question.name" :href="null" :question_id='question.id'>
         <answer-manager slot="answers" :enableUpdates="false" :question_id="question.id"></answer-manager>
        </single-question-manager>
    </div>
    </slick>
     <button  @click="finish()" type="button" class="btn right btn-success">
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
      test_attempt: {},
      test_answers: [],
      slickOptions: {
        slidesToShow: 1,
        dots: true,
        infinite: false
      }
    };
  },

  mounted() {
    this.getTest();
  },

  components: {
    Slick,
    SingleQuestionManager,
    AnswerManager
  },

  props: ["test_id"],

  methods: {
    getTest() {
      axios.get("/api/tests/" + this.test_id).then(response => {
        this.test = response.data[0];
        this.startAttempt();
      });
    },

    startAttempt(){
       axios.get("/api/tests/" + this.test_id + "/attempt").then(response => {
        this.test_attempt = this.test
        var savedQuestions = JSON.parse(response.data.question_and_answers);
        if(Object.keys(savedQuestions).length)
         this.test_attempt.questions = savedQuestions;

        var obj = {};
        this.test_attempt.questions.forEach(function(question){
          question.answers.forEach(function(answer){
            answer.is_picked = false;
          })
          obj[question.id] = question.answers;
        });
        this.test_answers = obj;
        this.test_attempt.attempt_id = response.data.id;
      });
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

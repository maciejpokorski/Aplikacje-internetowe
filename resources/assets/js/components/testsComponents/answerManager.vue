<style scoped>
.relative {
  position: relative;
}

.txt-center{
  text-align: center;
}

.Answer-btn {
  background-color: #e75151;
  color: #fff;
  position: absolute;
  top: 5px;
  right: 25px;
}

.Answer-btn-check.active{
  background-color:  #00dd81;
}

.Answer-btn-check {
  background-color: #e4e4e4;
  color: #fff;
  position: absolute;
  top: 5px;
  right: 25px;
}
.Answer-btn-check.picked_answer.push_left{
  right: 125px;
}

</style>

<template>
<div>
    <simple-panel-wrapper title="Answers" size="col-md-12" offset="col-md-offset-0">
      <div v-for="(answer, index) in answers" class="relative" >
         <simple-panel-wrapper :title="index+1" size="col-md-6 col-xs-12" offset="col-md-offset-0">
          <h2 v-if="enableUpdates">
            <editable  :value.sync='answer.name' @change='update(answer)'></editable>
          </h2>
          <h2 v-else class="txt-center">
            {{answer.name}}
          </h2>
          <label v-if="enableUpdates" class="checkbox-inline"><input type="checkbox" v-model='answer.is_correct' @click="update(answer)" :checked="answer.is_correct">Is correct</label>
           <button v-if="enableUpdates" @click="remove(answer, index)" type="button" class="btn btn-default Answer-btn">
                  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
             <button v-if='!show_results' @click="checkAnswer(answer, index)" type="button" :class="{'btn btn-default Answer-btn-check' : 1, 'active' : answer.is_picked}">
                  <span class="glyphicon glyphicon-ok" aria-hidden="true">
                  </span>
            </button>
            <button v-if='answer.is_picked && show_results' type="button" :class="{'btn btn-default Answer-btn-check picked_answer' : 1, 'push_left' : answer.is_correct}">
                 Given
                  <span class="glyphicon glyphicon-ok" aria-hidden="true">
                  </span>
            </button>
            <button v-if='show_results && answer.is_correct' type="button" :class="{'btn btn-default Answer-btn-check' : 1, 'active' : 1}">
                 Correct
                  <span class="glyphicon glyphicon-ok" aria-hidden="true">
                  </span>
            </button>
          </simple-panel-wrapper>
        </div>
       <div class=col-md-12 v-if="enableUpdates">
            <a class="action-link" @click="showCreateAnswerForm">
                Create New Answer
            </a>
        </div>
       <div class="modal fade" id="modal-create-Answer" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Create Answer
                        </h4>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="form.errors.length > 0">
                            <p>
                                <strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in form.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Form Success info -->
                        <div class="alert alert-success" v-if="form.success.length > 0">
                            <p>
                                <strong>Done!</strong></p>
                                <div v-for="message in form.success">
                                    {{ message }}
                                </div>
                        </div>

                        <form class="form-horizontal" role="form" @submit.prevent="store">
                            <!-- Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" v-model="form.name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Is correct</label>

                                <div class="col-md-6">
                                  <label class="checkbox-inline"><input type="checkbox" value="1" v-model="form.is_correct">&nbsp</label>
                                  
                                </div>
                            </div>
                        
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="store">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </simple-panel-wrapper>
</div>
</template>

<script>
import Editable from "vue-xeditable/src/Editable.vue";
export default {
  data() {
    return {
      answers: [],
      test_answers: [],
      form:{
        name: '',
        question_id: '',
        is_correct: 0,
        errors: [],
        success: []
      }
    };
  },

 props: {
   question_id: '',
   show_results: {
     default: false
   },
   enableUpdates: {
     default: true
   },
   user_attempt_id: {
     default: ""
   },
   test_id: {

   }
},


  mounted() {
    this.getTestAnswers();
    this.getAnswers();
    this.form.question_id = this.question_id;
  },

  components: {
    editable: Editable
  },

  methods: {
    getAnswers() {
      axios.get("/api/answers/question/" + this.question_id).then(response => {
        var ta = this.test_answers
        response.data.forEach(function(answer){
          answer.is_picked = false;
          ta.forEach(function(test_answer){
            if(test_answer.answer_id == answer.id)
              answer.is_picked = true;
          })
        })
        this.answers = response.data;
      });
    },

    getTestAnswers(){
     axios.get("/api/test-answers/", {params: {test_id: this.test_id, question_id: this.question_id, user_attempt_id:this.user_attempt_id}}).then(response => {
       this.test_answers = response.data;
      });
    },

    remove(answer, index) {
      axios
        .delete("/api/answers/" + answer.id)
        .then(response => {
          if (response.data == answer.id) {
            Vue.delete(this.answers, index);
          }
        })
        .catch(error => {
          if (typeof error.response.data === "object") {
            this.form.errors = _.flatten(_.toArray(error.response.data));
          } else {
            this.form.errors = ["Something went wrong. Please try again."];
          }
        });
    },
    update(answer) {
       axios({
            method:'put',
            url:'/api/answers/'+answer.id,
            data: {
              name: answer.name,
              is_correct: !answer.is_correct
            }
        })
        .then(function(response) {
            
        });
    },

    store() {
      axios
        .post("/api/answers/", this.form)
        .then(response => {
          this.form.errors = [];
          this.form.success.push(
            "Answer " + response.data.name + " has been created."
          );
          this.answers.push(response.data);
          this.clearFormInputs();
        })
        .catch(error => {
          if (typeof error.response.data === "object") {
            this.form.errors = _.flatten(_.toArray(error.response.data));
          } else {
            this.form.errors = ["Something went wrong. Please try again."];
          }
        });
    },

    checkAnswer(answer, event) {
      if(this.show_results)
        return;

      if(!this.enableUpdates){
        answer.is_picked = !answer.is_picked;
        this.saveAnswerToResults(answer);
      }
    },

    saveAnswerToResults(answer){
      var data = {
        answer_id: answer.id,
        answer_is_picked: answer.is_picked,
        question_id: this.question_id,
        test_id: this.test_id
      }
       axios
        .post("/api/answers/save-result", data)
        .then(response => {
          
        })
        .catch(error => {
        
        });
    },

    showCreateAnswerForm() {
      $("#modal-create-Answer").modal("show");
    }
  }
};
</script>

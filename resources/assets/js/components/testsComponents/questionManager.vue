<style scoped>
    .Question-list{
        margin: 0;
        padding: 0;
    }
    .Question-list--element {
        display: flex;
        flex-wrap: no-wrap;
        flex-direction: row;
        justify-content: space-between;
        padding: 5px 10px;
    }
    .Question-list--element-btn{
        background-color: #E75151;
        color: #fff;
        position: relative;
    }
    .Question-list--editable{
        padding-right: 5px;
    }
    .checkbox{
        position: absolute;
        left: 23px;
    }
</style>

<template>
    <simple-panel-wrapper title="Questions" :size="size_prop" offset="col-md-offset-0">
        <div>
        <ul id="Question-list" class="Question-list">
            <li v-for="(question, index) in questions" class="Question-list--element">
                <input v-if='sync_prop && test_id >= 0' v-model="question.checked" class="checkbox" @click="toggleQuestionTest(question.id)" type="checkbox">
                <a :href="'/questions/' + question.id">{{question.name}}</a>
                <button @click="remove(question, index)" type="button" class="btn btn-default Question-list--element-btn">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>
            </li>
        </ul>
        <div>
            <a class="action-link" @click="showCreateQuestionForm">
                Create New Question
            </a>
        </div>

        <div class="modal fade" id="modal-create-Question" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Create Question
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
                                <label class="col-md-4 control-label">Description</label>

                                <div class="col-md-6">
                                   <textarea name="description" class="form-control" v-model="form.description" rows="5" id="description"></textarea>
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

    </div>
    </simple-panel-wrapper>
  
</template>

<script>
import Editable from 'vue-xeditable/src/Editable.vue'
export default {

  data() {
    return {
      questions: [],
      checkbox: '',
      form: {
        name: "",
        description: "",
        errors: [],
        success: []
      }
    };
  },


  mounted() {
    this.getQuestions();
  },

 components: {
    'editable': Editable
  },

props: {
  size_prop: {
    default: 'col-md-12'
  },
  sync_prop: {
      type: Boolean,
      default: false
  },
  test_id: {
      default: -1
  }
},
  methods: {
    getQuestions() {
      axios.get("/api/questions", {params:{test_id: this.test_id}}).then(response => {
        this.questions = response.data
      });
    },

    clearFormInputs() {
      this.form.name = "";
    },

    showCreateQuestionForm() {
      this.clearFormInputs();
      $("#modal-create-Question").modal("show");
    },

    remove(question, index) {
      axios
        .delete("/api/questions/" + question.id)
        .then(response => {
            if(response.data == question.id){
                Vue.delete(this.questions, index);                
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

    store() {
      axios
        .post("/api/questions/", this.form)
        .then(response => {
          this.form.errors = [];
          this.form.success.push(
            "Question " + response.data.name + " has been created."
          );
          this.questions.push(response.data);
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

    toggleQuestionTest(question_id){
        axios
        .post("/api/questions/toggle-question-test", { test_id: this.test_id, question_id:question_id})
        .then(response => {
            
        });
    },

    update(question){
        axios({
            method:'put',
            url:'/api/question/'+question.id,
            data: {
                name: question.name
            }
        })
        .then(function(response) {
           
        });
    }
  }
};
</script>

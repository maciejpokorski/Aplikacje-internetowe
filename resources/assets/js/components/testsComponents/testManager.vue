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
.Test-list--element-btn {
  background-color: #e75151;
  color: #fff;
}
.Test-list--editable {
  padding-right: 5px;
}

</style>

<template>
    <simple-panel-wrapper title="Tests" size="col-md-12" offset="col-md-offset-0">
        <div>
        <ul id="Test-list" class="Test-list">
            <li v-for="(test, index) in tests" class="Test-list--element">
                <div>
                    <a :href="'/tests/' + test.id">{{test.name}}</a>
                    <div>
                     <ul>
                        <h3>Attempts</h3>
                        <li class="Attempt-list-el" v-for="testAttempt in test.test_attempts">
                            <span>Date: {{testAttempt.updated_at}}, </span>
                            <span>score: {{testAttempt.score}}, </span>
                            <span>by: {{testAttempt.user.email}}</span>
                             <a :href='"/tests/" + test.id + "/results/"+testAttempt.user.id' class="btn btn-default" role="button">
                                 <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                            </a>  
                        </li> 
                    </ul>
                    </div>
                </div>
                <div>
                    <a :href='"/tests/" + test.id + "/attempt"' class="btn btn-success" role="button">
                        <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
                    </a>                    
                    <button @click="remove(test, index)" type="button" class="btn btn-default Test-list--element-btn">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>

               
            </li>
        </ul>
        <div>
            <a class="action-link" @click="showCreateTestForm">
                Create New Test
            </a>
        </div>

        <div class="modal fade" id="modal-create-Test" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Create Test
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
                                   <textarea name="description" class="form-control" rows="5" id="description" v-model="form.description"></textarea>
                                </div>
                            </div>
                          <div class="form-group">
                              <label for="" class="col-md-4 control-label">Start date</label>
                              <div class="col-md-6">
                                  <datepicker v-model="form.start_date"></datepicker>
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="" class="col-md-4 control-label">Finish date</label>
                              <div class="col-md-6">
                                  <datepicker v-model="form.finish_date"></datepicker>
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
import Editable from "vue-xeditable/src/Editable.vue";
import vSelect from "vue-select";
import Datepicker from "vuejs-datepicker";
export default {
  data() {
    return {
      tests: [],
      form: {
        name: "",
        description: "",
        start_date: new Date(),
        finish_date: new Date(new Date().getTime() + 24 * 60 * 60 * 1000),
        errors: [],
        success: []
      }
    };
  },

  mounted() {
    this.getTests();
  },

  components: {
    editable: Editable,
    datepicker: Datepicker
  },

  methods: {
    getTests() {
      axios.get("/api/tests").then(response => {
        this.tests = response.data;
      });
    },

    clearFormInputs() {
      this.form.name = "";
    },

    showCreateTestForm() {
      this.clearFormInputs();
      $("#modal-create-Test").modal("show");
    },

    remove(test, index) {
      axios
        .delete("/api/tests/" + test.id)
        .then(response => {
          if (response.data == test.id) {
            Vue.delete(this.tests, index);
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
      this.form.start_date = this.form.start_date
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
      this.form.finish_date = this.form.finish_date
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
      axios
        .post("/api/tests/", this.form)
        .then(response => {
          this.form.errors = [];
          this.form.success.push(
            "Test " + response.data.name + " has been created."
          );
          this.tests.push(response.data);
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

    update(test) {
      axios({
        method: "put",
        url: "/api/test/" + test.id,
        data: {
          name: test.name
        }
      }).then(function(response) {});
    }
  }
};
</script>

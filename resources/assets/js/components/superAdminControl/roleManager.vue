<style scoped>
    .Role-list{
        margin: 0;
        padding: 0;
    }
    .Role-list--element {
        display: flex;
        flex-wrap: no-wrap;
        flex-direction: row;
        justify-content: space-between;
        padding: 5px 10px;
    }
    .Role-list--element-btn{
        background-color: #E75151;
        color: #fff;
    }
    .Role-list--editable{
        padding-right: 5px;
    }
</style>

<template>
    <div>
        <ul id="Role-list" class="Role-list">
            <li v-for="(role, index) in roles" class="Role-list--element">
                <editable class="User-list--editable" :value.sync='role.name' @change='update(role, $event)'></editable>
                <button @click="remove(role, index)" type="button" class="btn btn-default Role-list--element-btn">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>
            </li>
        </ul>
        <div>
            <a class="action-link" @click="showCreateRoleForm">
                Create New Role
            </a>
        </div>

        <div class="modal fade" id="modal-create-Role" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Create Role
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
</template>

<script>
import Editable from 'vue-xeditable/src/Editable.vue'
export default {

  data() {
    return {
      roles: [],
      form: {
        name: "",
        errors: [],
        success: []
      }
    };
  },

  mounted() {
    this.getRoles();
  },

    components: {
    'editable': Editable
  },

  methods: {
    getRoles() {
      axios.get("/api/roles").then(response => {
        this.roles = response.data;
        this.$emit('getRoles', this.roles);
      });
    },

    clearFormInputs() {
      this.form.name = "";
    },

    showCreateRoleForm() {
      this.clearFormInputs();
      $("#modal-create-Role").modal("show");
    },

    remove(role, index) {
      axios
        .delete("/api/roles/" + role.id)
        .then(response => {
            if(response.data == role.id){
                Vue.delete(this.roles, index);                
                Event.$emit('deleteRole');
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
        .post("/api/roles/", this.form)
        .then(response => {
          this.form.errors = [];
          this.form.success.push(
            "Role " + response.data.name + " has been created."
          );
          this.roles.push(response.data);
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

    update(role){
        axios({
            method:'put',
            url:'/api/roles/'+role.id,
            data: {
                name: role.name
            }
        })
        .then(function(response) {
             Event.$emit('markSucces', clickedElement, 2, '', 'completed');
        });
    }
  }
};
</script>

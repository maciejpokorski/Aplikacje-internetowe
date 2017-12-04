<style scoped>
    .Permission-list{
        margin: 0;
        padding: 0;
    }
    .Permission-list--element {
        display: flex;
        flex-wrap: no-wrap;
        flex-direction: row;
        justify-content: space-between;
        padding: 5px 10px;
    }
    .Permission-list--element-btn{
        background-color: #E75151;
        color: #fff;
    }
    .Permission-list--editable{
        padding-right: 5px;
    }
</style>

<template>
    <div>
        <ul id="Permission-list" class="Permission-list">
            <li v-for="(permission, index) in permissions" class="Permission-list--element">
                <editable class="Permission-list--editable" :value.sync='permission.name' @change='update(permission)'></editable>
                <button @click="remove(permission, index)" type="button" class="btn btn-default Permission-list--element-btn">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>
            </li>
        </ul>
        <div>
            <a class="action-link" @click="showCreatePermissionForm">
                Create New Permission
            </a>
        </div>

        <div class="modal fade" id="modal-create-permission" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Create Permission
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
      permissions: [],
      form: {
        name: "",
        email: "",
        password: "",
        errors: [],
        success: []
      }
    };
  },

  mounted() {
    this.getPermissions();
  },

    components: {
    'editable': Editable
  },

  methods: {
    getPermissions() {
      axios.get("/api/permissions").then(response => {
        this.permissions = response.data;
        this.$emit('getPermissions', this.permissions);
      });
    },

    clearFormInputs() {
      this.form.name = "";
      this.form.email = "";
      this.form.password = "";
    },

    showCreatePermissionForm() {
      this.clearFormInputs();
      $("#modal-create-permission").modal("show");
    },

    remove(permission, index) {
      axios
        .delete("/api/permissions/" + permission.id)
        .then(response => {
            if(response.data == permission.id){
                Vue.delete(this.permissions, index);
                Event.$emit('deletePermission');
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
        .post("/api/permissions/", this.form)
        .then(response => {
          this.form.errors = [];
          this.form.success.push(
            "Permission " + response.data.name + " has been created."
          );
          this.permissions.push(response.data);
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

    update(permission){
        axios({
            method:'put',
            url:'/api/permissions/'+permission.id,
            data: {
                name: permission.name
            }
        })
        .then(function(response) {
             
        });
    }
  }
};
</script>

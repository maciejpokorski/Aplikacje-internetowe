<style scoped>
    .User-list{
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .User-list--element, .User-list--sync-btn-wrappper {
        display: flex;
        flex-wrap: no-wrap;
        flex-direction: row;
        justify-content: space-between;
        padding: 5px 10px;
    }
    .User-list--element-btn{
        background-color: #E75151;
        color: #fff;
    }
    .User-list--editable{
        padding-right: 5px;
    }
    .User-list--v-select{
        margin: 3px 7px;
        width: 100%;
    }
</style>

<template>
    <div>
        <ul id="User-list" class="User-list">
            <li v-for="(user, index) in users">
                <span class="User-list--element">
                    <editable class="User-list--editable" :value.sync='user.email' attr="email" @change='update(user, $event)'></editable>
                    <button @click="remove(user, index)" type="button" class="btn btn-default User-list--element-btn">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </span>
                <span class="User-list--sync-btn-wrappper">                
                 <v-select class="User-list--v-select" multiple label="name" :options.sync="roles" :value.sync="user.roles"></v-select>
                 <button class="btn btn-default" @click="sync(user, 'roles', $event)"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                </span>
                <span class="User-list--sync-btn-wrappper">                
                 <v-select class="User-list--v-select" multiple label="name" :options.sync="permissions" :value.sync="user.permissions"></v-select>
                 <button class="btn btn-default" @click="sync(user, 'permissions', $event)"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                </span> 
                  <hr>
            </li>
        </ul>
        <div>
            <a class="action-link" @click="showCreateUserForm">
                Create New User
            </a>
        </div>

        <div class="modal fade" id="modal-create-user" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Create User
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
                            <!-- Email -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Email</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="email" v-model="form.email">
                                </div>
                            </div>
                            <!-- password -->
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" v-model="form.password">
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
import vSelect from "vue-select"
export default {

  data() {
    return {
      users: [],
      form: {
        name: "",
        email: "",
        password: "",
        errors: [],
        success: []
      }
    };
  },

  props: ['roles', 'permissions'],

  mounted() {
    this.getUsers();
    Event.$on('deleteRole', this.getUsers);
    Event.$on('deletePermission', this.getUsers);
    
  },

    components: {
    'editable': Editable,
    'v-select': vSelect
  },

  methods: {
    getUsers() {
      axios.get("/api/users").then(response => {
        this.users = response.data
      });
    },

    sync(user, type, event){
        var clickedButton = event.target;
        var roles, permissions = null;
        if(type =='roles')
            //change object to array        
            roles = Object.keys(user.roles).map(key => user.roles[key].name)
        else
            permissions = Object.keys(user.permissions).map(key => user.permissions[key].name)
         axios
        .post("/api/users/"+user.id+"/sync/"+type, roles?roles:permissions)
            .then(response => {
                Event.$emit('markSuccess', clickedButton);
        })
        .catch(error => {
          if (typeof error.response.data === "object") {
            this.form.errors = _.flatten(_.toArray(error.response.data));
          } else {
            this.form.errors = ["Something went wrong. Please try again."];
          }
        });
    },

    clearFormInputs() {
      this.form.name = "";
      this.form.email = "";
      this.form.password = "";
    },

    showCreateUserForm() {
      this.clearFormInputs();
      $("#modal-create-user").modal("show");
    },

    remove(user, index) {
      axios
        .delete("/api/users/" + user.id)
        .then(response => {
            if(response.data == user.id)
                 Vue.delete(this.users, index);
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
        .post("/api/users/", this.form)
        .then(response => {
          this.form.errors = [];
          this.form.success.push(
            "User " + response.data.name + " has been created."
          );
          this.users.push(response.data);
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

    update(user, event){
        var clickedElement = event.target;
        axios({
            method:'put',
            url:'/api/users/'+user.id,
            data: {
                email: user.email
            }
        })
        .then(function(response) {
             Event.$emit('markSucces', clickedElement);
        });
    }
  }
};
</script>

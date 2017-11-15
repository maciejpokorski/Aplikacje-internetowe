<template>
    <div>
        <ul id="example-1">
            <li v-for="(user, index) in users">
                {{ user.email }}
                <span @click="remove(user, index)">delete</span>
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
export default {
    props: ['size', "offset"],

    data() {
        return {
            users: [],
            form: {
                name: '',
                email: '',
                password: '',
                errors: [],
                success: []
                }
        };
    },

    mounted() {
        this.getUsers();
    },

    methods: {
        getUsers() {
            axios.get('/api/users')
                .then(response => {
                    this.users = response.data;
                });
        },

        clearFormInputs(){
            this.form.name = '';
            this.form.email = '';
            this.form.password = '';
        },

        showCreateUserForm(){
            this.clearFormInputs();
            $('#modal-create-user').modal('show');
        },

        remove(user, index){
            Vue.delete(this.users, index);
            console.log(user);
        },

        store(){
             axios.post('/api/users/', this.form).then(response => {
                            this.form.errors = [];
                            this.form.success.push('User '+response.data.name + ' has been created.')
                            this.users.push(response.data);
                            this.clearFormInputs();
                        })
                        .catch(error => {
                            if (typeof error.response.data === 'object') {
                                this.form.errors = _.flatten(_.toArray(error.response.data));
                            } else {
                                this.form.errors = ['Something went wrong. Please try again.'];
                            }
                        });
        }

    }
}
</script>


<template>
   <div class="container">
	    <div class="row">
            <simple-panel-wrapper ref="usermanager" v-cloak title="Mange users" size="col-md-4" offset="col-md-offset-0">
                <user-manager :roles.sync="roles" :permissions.sync="permissions" v-cloak></user-manager>
            </simple-panel-wrapper>
            <simple-panel-wrapper v-cloak title="Manage roles" size="col-md-4" offset="col-md-offset-0">
                <role-manager v-on:getRoles="setRoles" v-cloak></role-manager>
            </simple-panel-wrapper>
            <simple-panel-wrapper v-cloak title="Manage permissions" size="col-md-4" offset="col-md-offset-0">
                <permission-manager v-on:getPermissions="setPermissions" v-cloak></permission-manager>
            </simple-panel-wrapper>
	    </div>
    </div>
</template>

<script>
import UserManager from './userManager.vue'
import RoleManager from './roleManager.vue'
import PermissionManager from './permissionManager.vue'

export default {

  data() {
    return {
       roles: [],
       permissions: []
    };
  },

  components:{
      'user-manager': UserManager,
      'permission-manager': PermissionManager
  },

  mounted() {
      Event.$on('markSuccess', function(element, numberOfSeconds = 2, classNameToDelete ='btn-default', classNameToApply = 'btn-success'){
          if(element.classList.contains(classNameToDelete))
            element.classList.remove(classNameToDelete);

            element.classList.add(classNameToApply);
            setTimeout(function(){ //go to previous color after 3 second
                element.classList.remove(classNameToApply);
                element.classList.add(classNameToDelete);
            }, numberOfSeconds*1000);  
      });
  },

  methods: {
      setRoles: function(roles){
          this.roles = roles;
      },
        setPermissions: function(permissions){
          this.permissions = permissions;
      }
  },

  components: {
    
  }
};
</script>

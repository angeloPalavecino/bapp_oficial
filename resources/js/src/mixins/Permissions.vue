<script>
  export default {
    methods: {
      $can(permissionName) {
        //console.log(Permissions);
        return atob(localStorage.getItem("userRole")).indexOf(permissionName) !== -1;
      },    
    },
  };
</script>
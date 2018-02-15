// Simple example of a vue.js form, using the Axios library and bootstrap 3 markup.

<style scoped>

</style>


<template>

    <form id="new_google_task_list" class="form-inline my-2 my-lg-0" v-on:submit.prevent="createList">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="action" value="add_task_list">
        <div class="form-group">
            <label for="task_list_name">Create a new Task List</label>
            <input type="text" class="form-control" id="task_list_name" placeholder="New list title" v-model="task_list_name">
        </div>
        <button type="submit" class="btn btn-default">Create</button>
    </form>

</template>

<script>
export default {
    components: {},
    mixins: [],
    data () {
      return {
        task_list_name: ''
      }
    },
    mounted() {},
    props: [
     
    ],
    computed: {
    
    },
    methods: {
        createList(){
          var fd = $("#new_google_task_list").serialize();
          axios.post('/gtasks/new_list', fd)
            .then(function(response){
                console.log(response);
            })
            .catch(function(error){
                console.log(error);
            });
        }
    }
};
</script>



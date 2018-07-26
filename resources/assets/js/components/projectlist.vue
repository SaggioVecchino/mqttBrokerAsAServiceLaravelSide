<template>
    <div class="card">
        <div class="card-header" id="form-div">My Projects
            <br><br>
            <newprojectform ></newprojectform>

        </div>
        <div class="card-body">
            <project v-for="project in projectsList" :key="project.id" :project="project"></project>
        </div>
    </div>

</template>

<script>
    import newprojectform from './newprojectform.vue';
    import Project from './project.vue';
    import EventBus from '../event-bus'

    export default {
        name: "projectlist",
        components:
            {
                'newprojectform': newprojectform,
                'project':Project
            },
        data() {
            return {
                projectsList: this.projects
            }
        },
        props: ['projects'],
        methods:{

        },
        mounted(){
            var that=this
            EventBus.$on('projectNameChanged',(newName,project_id)=>{
                alert(newName)
                var i=0
                for(i=0;i<that.projectsList.length;i++ ){
                    if (that.projectsList[i].id==project_id)
                    {
                        that.projectsList[i].project_name=newName;
                        break;
                    }
                }
            })
        }
        // {projects:Object},
    }
</script>

<style scoped>

</style>
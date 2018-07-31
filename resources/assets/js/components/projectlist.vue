<template>
    <div class="card">
        <div class="card-header" id="form-div">My Projects
            <br><br>
            <newprojectform ></newprojectform>

        </div>
        <div class="card-body">
            <project @projectDeleted="deleteProject" v-for="project in projectsList" :key="project.id" :userid="userid" :project="project"></project>
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
        props: ['projects','userid'],
        methods:{
            deleteProject(project_id){
                for(let i=0;i<this.projectsList.length;i++ ){
                    if (this.projectsList[i].id==project_id)
                    {
                        this.projectsList.splice(i,1);
                        break;
                    }
                }
            }
        },
        mounted(){
            var that=this
            EventBus.$on('projectNameChanged',(newName,project_id)=>{
                alert(newName)
                for(let i=0;i<that.projectsList.length;i++ ){
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
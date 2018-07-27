<template>
    <div class="card">
        <div class="card-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        Groups of devices in the project:
                        <br>
                        <b>ID:</b> {{project.id}}<br>
                        <b>Project name:</b> {{project.project_name}}
                        <br>
                    </div>
                    <div class="col-lg-6">
                        <br>
                        <newgroupform :project_id="project.id">
                        </newgroupform>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <group @groupDeleted="deleteGroup" v-for="group in groupsList" :key="group.id" :group="group"></group>
        </div>
    </div>
</template>

<script>
    import newgroupform from './newgroupform.vue';
    import Group from './group';

    import EventBus from '../event-bus'
    export default {
        name: "grouplist",
        components:
            {
                'newgroupform': newgroupform,
                'group':Group
            },
        data() {
            return {
                groupsList: this.groups
            }
        },
        props: ['project','groups'],
        methods:{
            deleteGroup(group_id){
                for(let i=0;i<this.groupsList.length;i++ ){
                    if (this.groupsList[i].id==group_id)
                    {
                        this.groupsList.splice(i,1);
                        break;
                    }
                }
            }
        },
        mounted(){
            var that=this
            EventBus.$on('groupNameChanged',(newName,group_id)=>{
                for(let i=0;i<that.groupsList.length;i++ ){
                    if (that.groupsList[i].id==group_id)
                    {
                        that.groupsList[i].group_name=newName;
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
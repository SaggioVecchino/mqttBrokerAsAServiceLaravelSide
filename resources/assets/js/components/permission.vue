<template>
    <div class="card">
        <div class="card-header">
            <b v-text="title"></b>
            <br>
            <addpermission :group_id="group_id" :type="type" :allow="allow" :modalid="modalid">
            </addpermission>
        </div>
        <div class="card-body">
            <ul v-if="permissions.length>0">
                <li v-for="element in thepermission">
                    {{element.topic_name}}
                    <confirm btncontent="Delete" :modalId="'delete'+element.id+type+allow"
                             :title="'Are you sure to delete the'+title+' on the topic: '+element.topic_name"
                             confirmButtonText="YES" denyButtonText="No" type="danger" @confirm="deletePermision(element)"></confirm>
                </li>
            </ul>
            <p v-else>
                No {{title}} attached to this group
            </p>
        </div>
    </div>
</template>

<script>
    import addpermission from './addpermission'
    import confirm from './confirm'
    export default {
        name: "permission",
        props: ['project_id','title','permissions','type', 'allow', 'group_id', 'modalid'],
        components:{'addpermission':addpermission,'confirm':confirm},
        data(){
          return {
              thepermission:this.permissions
          }
        },
        methods:{
            deletePermision(e){
                let url=`http://localhost:8000/device_groups_topics/${e.id}`
                alert(url)
                axios.delete(url)
                    .then(
                        response => {
                            $("#".concat("delete",e.id,this.type,this.allow)).modal('hide')
                            for(let i=0;i<this.thepermission.length;i++ ){
                                if (this.thepermission[i].id==e.id)
                                {
                                    this.thepermission.splice(i,1);
                                    break;
                                }
                            }
                        })
                    .catch(error => {
                        //this.form.onFail(error.response.data.errors);//without .errors same result
                    })
            }
        }
    }
</script>

<style scoped>

</style>
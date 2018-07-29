<template>
    <modalcontainer :modalId="modalid" btncontent="Contributors">
        <template slot="header">Manage contributors for the project: {{projectname}}
        </template>
        <template slot="body">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action='/device_groups' accept-charset="UTF-8" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                        <div class="form-group">
                            <label for="add_conrtibutor_input">Add a Contributor name </label>
                            <input  id="add_conrtibutor_input" @input="updateListNames"
                                    class="form-control" v-model="userName" type="text"
                                    name="name" placeholder="Type a user name"
                                    list="searchresults" autocomplete="off">
                            <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('group_name')" v-text="form.errors.get('group_name')"></span>


                            <datalist id="searchresults">
                                <option v-for="user in usersSuggestion" :value="user.name" :label="user.name"></option>
                            </datalist>
                            <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                        </div>
                        <input type="submit" :disabled="form.errors.any()" class="btn btn-primary" value="submit">
                    </form>
                </div>
                <!--<div class="card-footer">-->
                    <!--<div class="alert alert-danger" role="alert" v-if="form.errors.has('otherError')" v-text="form.errors.get('otherError')">-->
                    <!--</div>-->
                <!--</div>-->
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>
                        Contributors
                    </h4>


                    <ul>
                        <li v-for="contributor in contributors">
                            {{contributor.name}}
                            <span v-if="userid===contributor.id">
                                <b>(OWNER)</b>
                            </span>
                            <button v-if="userid!==contributor.id" type="button" class="btn btn-sm btn-danger"
                                    @click="deleteContributor(contributor)" >X</button>
                        </li>
                    </ul>
                </div>
            </div>

        </template>
    </modalcontainer>
</template>

<script>
    import modalcontainer from './modalcontainer.vue';
    import Form from "../forms/form";

    var timeout

    export default {
        name: "contributors",
        components:{'modalcontainer':modalcontainer},
        props:['modalid','userid','projectid','projectname'],
        data(){
            return {
                contributors:{},
                usersSuggestion:{},
                userName:'',
                form:new Form({
                    user_id:'',
                    project_id:this.projectid
                })
            }
        },
        methods:{
            onSubmit() {
                this.form.user_id=this.userIdHavingName()
                if((typeof this.form.user_id) !== "number"){
                    this.form.onFail(
                        {
                            "name":["choose a valid name"]
                        }
                    )
                }
                else{
                    this.form.post(`http://localhost:8000/project_users/`)
                        .then(response =>{
                            // window.location.href = `http://localhost:8000${response}`
                            this.contributors.push({
                                id:this.form.user_id,
                                name:this.userName
                            })
                        });
                }

            },
            deleteContributor(e){

               if (confirm(`are you sure to delete the contributer: ${e.name}`))
               {
                   let url=`http://localhost:8000/projects/${this.projectid}/contributors/${e.id}`
                   axios.delete(url)
                       .then(
                           response => {
                               for(let i=0;i<this.contributors.length;i++ ){
                                   if (this.contributors[i].id==e.id)
                                   {
                                       this.contributors.splice(i,1);
                                       break;
                                   }
                               }
                           })
               }
            },
            updateListNames(){
               clearInterval(timeout)
                timeout = setTimeout(this.fetchNames,500)
            },
            fetchNames() {
                let url = "http://localhost:8000/count"
                if (this.userName.length > 0)
                    axios.post(url, {name: this.userName}).then(response => {
                        this.usersSuggestion = response.data
                    })
            },
            getContributors(){
                let url = `http://localhost:8000/projects/${this.projectid}/contributors`
                axios.get(url).then(response => {
                    this.contributors = response.data
                })
            },
            userIdHavingName(){
                var that=this
                for(let i=0;i<this.usersSuggestion.length;i++){
                    if (this.usersSuggestion[i].name===that.userName)
                    {
                        return this.usersSuggestion[i].id
                    }
                }
            }
        },
        mounted(){
            this.getContributors();
        }

    }
</script>

<style scoped>

</style>
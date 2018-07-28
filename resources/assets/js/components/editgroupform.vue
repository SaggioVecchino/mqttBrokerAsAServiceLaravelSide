<template>
        <modalcontainer :modalId="modalId" btncontent="edit">
            <template slot="header">Change the group name
            </template>
            <template slot="body">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action='/device_groups' accept-charset="UTF-8" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                            <div class="form-group">
                                <label for="edit_group_name_input">Group name</label>
                                <input id="edit_group_name_input" v-model="form.group_name"  class="form-control" name="group_name" type="text" >
                                <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('group_name')" v-text="form.errors.get('group_name')"></span>
                            </div>
                            <input type="submit" :disabled="form.errors.any()" class="btn btn-primary" value="submit">
                        </form>
                    </div>

                    <div class="card-footer">
                        <div class="alert alert-danger" role="alert" v-if="form.errors.has('otherError')" v-text="form.errors.get('otherError')">
                        </div>
                    </div>
                </div>
            </template>
        </modalcontainer>
</template>

<script>
    import modalcontainer from './modalcontainer.vue';
    import Form from "../forms/form";
    import EventBus from '../event-bus'

    export default {
        name: "editgroup",
        components:{'modalcontainer':modalcontainer},
        props:['group_id','group_name'],
        data(){
            return {
                form: new Form({
                    group_name:this.group_name
                })
            }},
        // props:{project_id:Number},

        methods: {
            onSubmit() {
                var that=this

                that.newName=this.form.group_name
                this.form.patch(`http://localhost:8000/device_groups/${this.group_id}`)
                    .then(response =>{
                        EventBus.$emit('groupNameChanged',that.newName,that.group_id)
                        $("#".concat(`${that.modalId}`)).modal('hide')
                        // window.location.href = `http://localhost:8000${response}`
                    });
            }
        },
        computed:{
            modalId(){
                return this.group_id
            }
        },
        mounted(){
            var that=this
            $("#".concat(`${this.modalId}`)).on('hidden.bs.modal', function (e) {
                that.form.reset();
                that.form.group_name=that.group_name;
            })
        }
    }
</script>


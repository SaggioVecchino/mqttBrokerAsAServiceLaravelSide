<template>
    <div>
        <!--v-if="isVisible"-->
        <modal  :modalId="idModal" v-show="isVisible">
            <template slot="header">
                <slot name="header"></slot>
            </template>
            <template slot="body">
                <slot name="body"></slot>
            </template>
        </modal>
        <button type="button" class="btn btn-primary" @click="showModal" v-text="btncontent"></button>
    </div>
</template>

<script>
    import modal from './modal.vue';
    export default {
        name: "modalcontainer",
        props:['btncontent','modalId'],
        data(){
            return {
                isVisible:false,
                idModal:this.modalId
            }
        },
        components:{'modal':modal},
        methods:{
            showModal(){
                this.isVisible=true;
                $("#".concat(`${this.modalId}`)).modal({show:true})
            },
            hideModal(){
                this.isVisible=false;
            }
        },
        mounted(){
            var f=this.hideModal
            $("#".concat(`${this.modalId}`)).on('hidden.bs.modal', function (e) {
                f()
            })
        }
    }
</script>

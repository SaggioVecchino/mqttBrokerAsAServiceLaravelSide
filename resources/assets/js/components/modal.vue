<template>
    <div class="modal fade bd-example-modal-lg" :id="modalId" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        <slot name="header"></slot>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "modal",
        props:["modalId"]
    }
</script>

<!--<style scoped>-->

<!--</style>-->
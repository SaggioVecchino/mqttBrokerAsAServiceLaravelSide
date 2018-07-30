<template>
    <div class="card">
        <div class="card-header">
            <b v-text="title"></b>
            <br>
            <addpermission :group_id="group_id" :type="type" :allow="allow" :modalid="modalid">
            </addpermission>
        </div>
        <div class="card-body">
            <ul v-if="permissionspublications.length>0">
                <li v-for="element in permissionspublications">{{element.topic_name}}</li>
            </ul>
            <p v-else>
                No permission on publications attached to this group
            </p>
        </div>
    </div>
</template>

<script>
    import addpermission from './addpermission'
    export default {
        name: "permission",
        props: ['title','permissionspublications','type', 'allow', 'group_id', 'modalid'],
        components:{'addpermission':addpermission}
    }
</script>

<style scoped>

</style>

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

// require('./forms/newProject')
window.Vue = require('vue');

import newgroupform from './components/newgroupform'

import permission from './components/permission'

import editprojectpassword from './components/editprojectpassword'

import editprojectname from './components/editprojectname'

import editgroup from './components/editgroupform'

import newprojectform from './components/newprojectform'

import projectlist from './components/projectlist'

import grouplist from './components/grouplist'

// import Form from "./forms/form";
// import modal from './components/modal';
// import modalcontainer from './components/modalcontainer';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components:{
        newprojectform,
       newgroupform,
        editgroup,
        editprojectname,
        editprojectpassword,
        permission,
        projectlist,
        grouplist
        // modal,modalcontainer
    }
    // ,
    // data: {
    //     form: new Form({
    //         project_name: '',
    //         password: ''
    //     })
    // },
    //
    // methods: {
    //     onSubmit() {
    //         this.form.post('http://localhost:8000/projects')
    //             .then(response => window.location.href = `http://localhost:8000${response}`);
    //     }
    // }
});

// new Vue({
//     el: '#app',
//     // data: {
//     //     form: new Form({
//     //         project_name: '',
//     //         password: ''
//     //     })
//     // },
//     //
//     // methods: {
//     //     onSubmit() {
//     //         this.form.post('http://localhost:8000/projects')
//     //             .then(response => window.location.href = `http://localhost:8000${response}`);
//     //     }
//     // }
// });

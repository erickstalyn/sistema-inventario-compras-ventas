/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('adm_usuario', require('./components/administrador/Usuario.vue').default);
Vue.component('adm_superproducto', require('./components/administrador/SuperProducto.vue').default);
Vue.component('adm_producto', require('./components/administrador/Producto.vue').default);
Vue.component('adm_material', require('./components/administrador/Material.vue').default);
Vue.component('adm_abasto', require('./components/administrador/Abasto.vue').default);
Vue.component('adm_verpuesto', require('./components/administrador/VerPuesto.vue').default);
Vue.component('adm_veralmacen', require('./components/administrador/VerAlmacen.vue').default);
Vue.component('adm_estadistica', require('./components/administrador/Estadistica.vue').default);

Vue.component('pue_producto', require('./components/puesto/Producto.vue').default);
Vue.component('pue_venta', require('./components/puesto/Venta.vue').default);
Vue.component('pue_vale', require('./components/puesto/Vale.vue').default);

Vue.component('alm_produccion', require('./components/almacen/Produccion.vue').default);
Vue.component('alm_producto', require('./components/almacen/Producto.vue').default);

Vue.component('enviorecibido', require('./components/EnvioRecibido.vue').default);
Vue.component('enviorealizado', require('./components/EnvioRealizado.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);

Vue.component('noti_venta_puesto', require('./components/Notificacion/Notification_vp.vue').default);
Vue.component('noti_venta_alm', require('./components/Notificacion/Notification_va.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:{
        menu: 1,
        notifications:[],
    },
    created(){
        let me = this;
        axios.post('notification/get').then(function(response){
            // console.log(response.data);
            me.notifications = response.data;
        }).catch(function(error){
            console.log(error);
        });
        var userId = $('meta[name="userId"]').attr('content');
        Echo.private('App.Usuario.' + userId).notification((notification) =>{
            console.log(notification);
            me.notifications.unshift(notification);
        });
    }
});

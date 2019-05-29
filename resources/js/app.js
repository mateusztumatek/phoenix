
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.min.css';
import 'vue-material/dist/theme/default-dark.css' // This line here
Vue.use(VueMaterial);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('order-component', require('./components/orderComponent.vue').default);


Vue.component('draw-component', require('./components/DrawComponent.vue').default);
Vue.component('createItem', require('./components/CreatorItems.vue').default);
Vue.component('items', require('./components/Items.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:function () {
        return{
            "is_show": false,
            "api_url": '127.0.0.1:8000',
            selectedItem: null,
            token: '',
        }
    },
    mounted(){
      this.token = document.head.querySelector('meta[name="csrf-token"]').content;
    },
    methods:{
      show(){
      },
      selectItem(item){
          if(!item.id) item = {};
          this.selectedItem = item;
      },
      deleteItem(json){
          let v = this;
          axios.post(base_url+'/admin/remove_project', {id: json.id})
              .then(function (response) {
                  window.location.href = base_url+'/admin/projektuj';
              })
      }
    }
});

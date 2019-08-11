
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.min.css';
import 'vue-material/dist/theme/default.css' // This line here
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
Vue.component('make-order', require('./components/MakeOrder').default);
Vue.component('cart', require('./components/CartComponent').default);
Vue.component('Products', require('./components/Products/Products').default);
Vue.component('Product', require('./components/SingleProduct').default);
import VueRouter from 'vue-router';
var router = new VueRouter({
    mode: 'history',
    routes: []
});
Vue.use(VueRouter);
Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('eu', {
        style: 'currency',
        currency: 'PLN',
        minimumFractionDigits: 2
    });
    return formatter.format(value);
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app',
    data:function () {
        return{
            cartMessage: null,
            "is_show": false,
            "api_url": '127.0.0.1:8000',
            selectedItem: null,
            token: '',
            showCart: false,
            cart: [],
            base_url: '',
        }
    },
    mounted(){
        this.base_url = base_url;
      this.token = document.head.querySelector('meta[name="csrf-token"]').content;
      this.getCart();
    },
    watch:{
        cartMessage: function (data) {
            let v  =this;
            setTimeout(function () {
                v.cartMessage = null;
            }, 4000)
        }
    },
    methods:{
        getImage(src){
            return base_url+'/str/'+src;
        },
      show(){

          this.showCart = !this.showCart;
      },
        itemsChange(data){
          this.cart = data;

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
      },
        getProductUrl(product){
          return base_url+'/produkt/'+product.id+'/'+product.name;
        },
        getCart(){
            let v =this;
            axios.get(base_url+'/cart')
                .then(function (response) {
                    v.cart = response.data;
                    v.items = v.cart.itemsCount;
                })
        },
        addToCart(product ,length = null, quantity = 1){
          let v = this;
          axios.post(base_url+'/cart', {
              product_id: product.id,
              quantity: quantity,
              length: length,
              type: 'product'
            })
              .then(function (response) {
                  if(response.data){
                      v.cart = response.data;
                      v.cartMessage = 'Dodano przedmiot pomyślnie';
                  }else{
                      v.cartMessage = 'Ten przedmiot juz jest w koszyku';
                  }
              })
        },
        addToCartDesign(design, quantity = 1){
            let v = this;
            axios.post(base_url+'/cart', {
                design: design,
                quantity: quantity,
                type: 'design'
            })
                .then(function (response) {
                    if(response.data){
                        v.cart = response.data;
                        v.cartMessage = 'Dodano przedmiot pomyślnie';
                    }else{
                        v.cartMessage = 'Ten przedmiot juz jest w koszyku';
                    }
                })
        }
    }
});

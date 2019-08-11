<template>
    <div class="row">
        <div class="col-md-3">
            <filters v-on:change-filters="changeFilter" :tags="tags" :inputs="input" :collections="collections" :productsForFilters="productsForFilters"></filters>
        </div>
        <div class="col-md-9 d-flex flex-wrap position-relative">

            <div class="col-md-4 col-sm-6"  v-for="product in products" v-if="products.length > 0">
                <div class="product-grid4 border-muted animated fadeIn" :class="{'muted' : product.quantity == 0}">
                    <div class="product-image4">
                        <div style="cursor: pointer" @click="window.location.href = '/produkt/'+product.link" class="overlay"></div>
                        <a :href="'/produkt/'+product.link">
                            <img class="pic-1" :src="$parent.getImage(JSON.parse(product.images)[0])" style="max-height: 100%">
                        </a>
                        <div class="product-labels">

                            <span v-if="product.new == 1">Nowość</span>

                            <span v-if="product.quantity == 0">Niedostępny</span>

                        </div>
                    </div>
                    <div class="product-content">
                        <h2 class="title"><a :href="$root.base_url+'/produkt/'+product.link" class="white-color" style="cursor: pointer">{{product.name}}</a></h2>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <div class="price" :class="{'line-through' : product.prices_sellout}">
                                   <span class="price-small" style="font-size: 1rem">{{product.price | toCurrency}}</span>
                                </div>

                                <div class="price ml-3" v-if="product.prices_sellout">
                                    <span class="price-small" style="font-size: 1rem">{{product.prices_sellout | toCurrency}}</span>
                                </div>

                            </div>
                            <div>
                                <md-button @click="quick_view('/quick_view/'+product.link)" class="md-icon-button  md-raised md-primary">
                                    <i  class="fas fa-eye"></i>
                                </md-button>
                              <!--  <md-button @click="$parent.addToCart(product)" class="md-icon-button  md-raised md-primary">
                                    <i  class="fas fa-shopping-cart"></i>
                                </md-button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="products.length == 0" class="w-100 animated fadeIn text-center pt-3">
                <h2 style="font-size: 2rem; font-weight: 200" class="white-color">Brak znalezionych produktów</h2>
            </div>
            <infinite-loading v-if="confirm && !done && products.length > 0" @infinite="infiniteHandler"></infinite-loading>
            <md-button v-if="!confirm && !done && products.length > 0" @click="confirm = true" class="md-raised md-primary w-100 m-0 mt-2">Zobacz więcej</md-button>

        </div>
    </div>
</template>

<script>
    import InfiniteLoading from '../../../../node_modules/vue-infinite-loading';
    import Filters from './ProductFilters';
    import AOS from '../../../../node_modules/aos';
    import '../../../../node_modules/aos/dist/aos.css';

    export default {
        props:['allproducts', 'input', 'tags', 'collections'],
        components:{
            InfiniteLoading : InfiniteLoading,
            filters: Filters,
        },
        data(){
            return{
                done: false,
                confirm: true,
                page: 1,
                per_page:12,
                filteredProducts: [],
                filters:{},
                sales: false,
                productsForFilters: [],
            }
        },
        computed:{
            products: function() {
                let v =this;
                var arr =  v.allproducts.filter(function(i) {
                    if(v.filters.search && v.filters.search != ''){
                        var term = v.filters.search.toLowerCase();
                        if(i.name.toLowerCase().indexOf(term) == -1 && i.content.toLowerCase().indexOf(term) == -1) return null;
                    }
                    if(v.sales){
                        if(i.prices_sellout == null) return null;
                    }
                    if(v.filters.selectedPrice){
                        if((v.filters.selectedPrice[0] > i.price)) return null
                        if((v.filters.selectedPrice[1] < i.price)) return null
                    }
                    if(v.filters.selectedTags && v.filters.selectedTags.length > 0){
                        var findTags = [];
                        v.filters.selectedTags.forEach(function (data) {
                            if(typeof i.tags.find(x => x.tag == data.tag) != "undefined"){
                                findTags.push(data);
                            }
                        })
                        if(findTags.length < v.filters.selectedTags.length) return null
                    }
                    if(v.filters.selectedMaterials && v.filters.selectedMaterials.length != 0){
                        let check = false;
                        v.filters.selectedMaterials.forEach(function (material) {
                            if(i.materials.find(x => x.name == material)){
                                check = true;
                            }
                        });
                        if(!check) return null;
                    }
                    if(v.filters.selectedCollections && v.filters.selectedCollections.length != 0){
                        let check = false;
                        v.filters.selectedCollections.forEach(function (collection) {
                            if(i.collections.find(x => x.name == collection)){
                                check = true;
                            }
                        });
                        if(!check) return null;
                    }
                    return i;
                });
                if(v.filters.selectedSort == 'priceAsc'){
                    arr.sort(function (a, b) {
                        return a.price - b.price;
                    })
                }
                if(v.filters.selectedSort == 'priceDesc'){
                    arr.sort(function (a, b) {
                        return b.price - a.price;
                    })
                }
                if(v.filters.selectedSort == 'mostViewed'){
                    arr.sort(function (a, b) {
                        return b.count - a.count;
                    })
                }
                this.productsForFilters = arr;
                var copy = Object.assign([], arr);
                var to_return = copy.splice(0 , this.per_page * this.page);
                if(to_return.length == arr.length){
                    this.confirm = true;
                    this.done = true;
                }
                return to_return;
            }
        },
        mounted() {
            var copy = Object.assign([], this.products);
            this.filteredProducts = copy.splice((this.page-1)*this.per_page, this.per_page);
        },
        watch:{
          /*  page: function () {
                console.log('fwafwa');

            },*/
        },
        methods:{
            quick_view(link) {
                $.get(link, function( data ) {
                    $('#product_view').replaceWith(data);
                    $('#product_view').modal();
                });
            },
            changeFilter(filters, sales){
                this.done = false;
                this.page = 1;
                this.filters = filters;
                this.sales = sales;
                AOS.init();

            },
            infiniteHandler($state) {
                this.page = this.page + 1;
                AOS.init();
                if(this.page % 3 == 0){
                    this.confirm = false;
                }
                if(this.done){
                    $state.complete();
                }else{
                    $state.loaded();
                }
            },
        }

    }
</script>
<style lang="scss">
    .product-loader{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        top: 0px;
        left: 0px;
        position: absolute;
        padding-top: 200px;
    }
    .lds-dual-ring {
        display: inline-block;
        width: 64px;
        height: 64px;
    }
    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 46px;
        height: 46px;
        margin: 1px;
        border-radius: 50%;
        border: 5px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }
    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>
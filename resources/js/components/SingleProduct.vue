<template>
    <div class="give-me-space">
        <div class="container">
            <div class="row">
                <div class="col-md-5 px-4 position-relative animated fadeIn" v-viewer>
                    <div class="signs">
                        <div class="sign" v-if="product.new == 1">
                            Nowość
                        </div>
                      <!--  <div class="sign" v-if="product.quantity > 0 && product.quantity < 2">
                            Ostatnie sztuki
                        </div>-->
                        <div class="sign" v-if="product.quantity == 0">
                            Brak sztuk
                        </div>
                    </div>
                    <img class="w-100 hover-image" :src="getSrc(JSON.parse(product.images)[0])">
                    <div class="row mt-2">
                        <div class="col-md-3 col-4" v-for="image in JSON.parse(product.images)">
                            <img class="w-100 hover-image" :src="getSrc(image)">
                        </div>
                    </div>
                </div>
                <div class="col-md-7 animated fadeIn">
                    <div class="d-flex flex-wrap justify-content-between">
                        <div class="col-md-9">
                            <h1 class="font-weight-bold white-color">{{product.name}}</h1>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="fb-share-button" :data-href="$root.base_url+'/produkt/'+product.id+'/'+product.name" data-layout="button_count" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Udostępnij</a></div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <span class="badge badge-pill badge-primary py-2 px-3 white-background" v-for="tag in product.tags">{{tag.tag}}</span>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div style="font-size: 2.5rem; line-height: 2.5rem; font-weight: 200" class="white-color">{{product.price | toCurrency}}</div>
                    </div>
                    <div class="col-md-12 mt-3 white-color">
                        <span style="opacity:0.5">Opis:</span>
                        <br>
                        <p style="letter-spacing: 1px; font-weight: 300">{{product.intro}}</p>
                        <p v-if="product.weight" style="letter-spacing: 1px; font-weight: 300">Waga: <strong>{{product.weight}}</strong></p>
                        <p v-if="product.size" style="letter-spacing: 1px; font-weight: 300">Rozmiar: <strong>{{product.size}}</strong></p>
                        <p v-if="product.color" style="letter-spacing: 1px; font-weight: 300">Kolor: <strong>{{product.color}}</strong></p>


                    </div>
                    <div class="col-md-12">
                        <p class="text-center white-color font-weight-bold">Zapytaj o ten produkt:</p>
                        <md-field>
                            <label>Zapytaj o ten produkt</label>
                            <md-textarea v-model="message"></md-textarea>
                        </md-field>
                        <md-field>
                            <label>Podaj swój adres e-mail, na niego wyślę odpowiedź</label>
                            <md-input type="email" v-model="email"></md-input>
                        </md-field>
                        <md-button @click="sendMessage()" class="md-raised md-primary white-color m-0 mt-1 w-100">Wyślij zapytanie</md-button>
                    </div>
                   <!-- <div class="col-md-12 d-flex align-items-center flex-wrap">
                        <div class="col-md-4 d-flex align-items-center">
                            <span @click="quantity = quantity -1" class="control">-</span>
                            <input v-model="quantity" class="number-input" type="number" min="0" >
                            <span @click="quantity = quantity + 1" class="control">+</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center mob-m-0">
                            <img :src="$parent.base_url+'/default/size.png'" style="max-height: 30px" class="mr-2">
                            <md-field>
                                <label>Podaj długość łańcuszka</label>
                                <md-input v-model="length"></md-input>
                            </md-field>
                        </div>
                        <div class="w-100 mt-3">
                            <md-button @click="addToCart()" class="md-raised md-primary m-0" style="border-radius: 20px; padding: 5px 15px;"><i class="fa fa-shopping-bag mr-2"></i>Dodaj do koszyka</md-button>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
        <div class="container animated fadeIn flex-wrap" v-if="errors.length > 0">
            <span class="alert alert-info w-100 my-3 d-block" v-for="error in errors">{{error}}</span>
        </div>

        <div class="section-white mt-5 position-relative">
            <div class="container">
                <div class="d-flex">
                    <md-tabs @md-changed="changeTab" md-sync-route class="col-md-10" md-dynamic-height>
                        <md-tab id="opis" md-label="Opis" to="?tab=opis"exact>
                            <p v-html="product.content"></p>
                            <p class="mb-0">Materiały: <span v-for="material in product.materials">{{material.name}}, </span></p>
                        </md-tab>
                        <md-tab id="oceny" md-label="Oceny" to="?tab=oceny"exact>
                            <p>Ocena tego produktu: <span style="font-size: 2rem;font-weight: bold" class="ml-2" v-if="rate != null">{{(rate).toFixed(1)}}</span></p>
                            <hr>
                            <div class="animated fadeIn" v-if="!comment_done">
                                <p>Zostaw swoją ocenę</p>
                                <div class="wrapper">
                                    <i @click="setRate(1)" class="far fa-star star-label" :class="{'fa' : currentRate >= 1}" data-star="1"></i>
                                    <i @click="setRate(2)" class="far fa-star star-label" :class="{'fa' : currentRate >= 2}" data-star="2"></i>
                                    <i @click="setRate(3)" class="far fa-star star-label" :class="{'fa' : currentRate >= 3}" data-star="3"></i>
                                    <i @click="setRate(4)" class="far fa-star star-label" :class="{'fa' : currentRate >= 4}" data-star="4"></i>
                                    <i @click="setRate(5)" class="far fa-star star-label" :class="{'fa' : currentRate >= 5}" data-star="5"></i>
                                </div>
                               <!-- <md-field class="mt-3">
                                    <label>Twój komentarz</label>
                                    <md-textarea v-model="comment"></md-textarea>
                                </md-field>-->
                                <md-button @click="addComment()" class="md-raised md-primary m-0 white-color my-3">Dodaj ocenę</md-button>
                            </div>
                            <span v-if="comment_done" class="animated fadeIn">Dodałeś swoją ocenę!</span>
                            <div class="col-md-12 pl-0 d-flex flex-wrap my-2" style="align-items: baseline" v-for="(comment, key) in comments_filtered">
                                <div class="position-relative">
                                    <div class="wrapper" v-if="comment.rate != null">
                                        <i class="far fa-star star-label" :class="{'fa' : comment.rate >= 1}" data-star="1"></i>
                                        <i class="far fa-star star-label" :class="{'fa' : comment.rate >= 2}" data-star="2"></i>
                                        <i  class="far fa-star star-label" :class="{'fa' : comment.rate >= 3}" data-star="3"></i>
                                        <i  class="far fa-star star-label" :class="{'fa' : comment.rate >= 4}" data-star="4"></i>
                                        <i class="far fa-star star-label" :class="{'fa' : comment.rate >= 5}" data-star="5"></i>
                                    </div>
                                    <div class="not-hover"></div>
                                </div>
                                <p class="ml-3 "> Dodane dnia: {{comment.created_at.substr(0,11)}}</p>
                                <div class="w-100">
                                    <p>{{comment.description}}</p>
                                </div>
                                <hr>
                            </div>
                            <md-button @click="show_all = true" class="md-raised md-primary" v-if="!show_all && comments.length > 5">Zobacz więcej</md-button>
                        </md-tab>
                    </md-tabs>
                    <div class="col-md-2">
                        <img class="tab-image"  :src="$root.base_url+'/default/'">
                    </div>
                </div>

            </div>
        </div>
        <h3 class="header1 mt-5">Podobne przedmioty</h3>
        <div class="container">
            <div id="featured_slider">
                <div class="product-slide" v-for="product in featured">
                    <img class="w-100" :src="$root.getImage(JSON.parse(product.images)[0])">
                    <div class="content">

                        <h2 class="header">{{product.name}}</h2>
                        <md-button class="md-raised md-primary white-color" :href="$root.getProductUrl(product)">Zobacz produkt</md-button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import 'viewerjs/dist/viewer.css'
    import Viewer from 'v-viewer'
    Vue.use(Viewer)
    export default {
        props:['product', 'featured'],
        data(){
          return{
              comment: '',
              rate:null,
              currentRate: null,
              comments: [],
              tab_images:{
                  Opis: 'hastag.png',
                  Oceny: 'star.png',
                  Wiecej: 'info.png'

              },
              quantity: 1,
              length: '',
              activeTab: 'opis',
              errors: [],
              comment_done: false,
              show_all: false,
              message: '',
              email: '',
              isMessageSend: false,
          }
        },
        mounted() {
            if(this.$route.query.tab != null){
                this.activeTab = this.$route.query.tab;
            }
        },
        watch:{
            quantity: function (data) {
                if(data < 1){
                    this.quantity = 1;
                }
                this.quantity = parseInt(this.quantity);
            },
            activeTab: function (data) {
                let v =this;
                if(this.activeTab == 'oceny'){
                    axios.get(base_url+'/product/'+this.product.id+'/rates')
                        .then(function (response) {
                            v.rate = response.data.rate;
                            v.comments = response.data.comments;
                        });
                }
            },
            errors: function (data) {
                let v =this;
                setTimeout(function () {
                    v.errors = [];
                }, 4000)
            }

        },
        computed:{
            comments_filtered: function () {
                let v =this;
                var count = 0
                let arr = this.comments.filter(function (i) {
                    if(!v.show_all){
                       if(count < 5){
                           console.log(count);
                           count = count + 1;
                           return i;
                       }
                       return null
                    }
                    return i;
                });
                return arr;
            }
        },
        methods:{
            sendMessage(){
                let v =this;
                var errors = false;
                if(this.email == ''){
                    this.errors.push('Musisz podać adres emial zwrotny.');
                    errors = true;
                }
                if(this.message == ''){
                    this.errors.push('Wpisz treść wiadomości.');
                    errors = true;
                }
                if(!errors){
                    axios.post(base_url+'/store/order', {
                        email: this.email,
                        text: this.message,
                        product_id: this.product.id,
                    }).then(function(response){
                        v.isMessageSend = true;
                        $('#thanks_modal').replaceWith(response.data);
                        $('#thanks_modal').modal();
                    })
                }
            },
            addToCart(){
                this.$root.addToCart(this.product, this.length, this.quantity);
            },
            setRate(rate){
                this.currentRate = rate;
            },
            changeTab(tab){
                this.activeTab = tab;
            },
            getSrc(src){
                return base_url+'/str/'+src;
            },
            addComment(){
                let v =this;
                axios.post(base_url+'/add_comment/'+this.product.id, {
                    rate: this.currentRate,
                    comment: this.comment
                })
                    .then(function (response) {
                        if(response.data.errors){
                            v.errors = response.data.errors;
                        }else{
                            v.comments.push(response.data);
                            v.comment_done = true;
                        }

                    })
            },
            shareProduct(){
              $('#fb_share').click();
            }
        }
    }
</script>
<style lang="scss">
    @import '../../sass/variables';

    .wrapper{
       i{
           font-size: 1.3rem;
       }
    }
    .md-textarea{
        -webkit-text-fill-color: $white-color !important;

    }
    .md-field.md-theme-default.md-has-textarea:not(.md-autogrow):after, {
        border-color: $white-color !important;
    }
    .md-field.md-theme-default.md-has-textarea:not(.md-autogrow):before{
        border-color: $primary-color !important;
    }
    .md-tabs{
        .md-field{
            label, textarea {
                color: $primary-color;
                -webkit-text-fill-color: $primary-color !important;
            }
            &:after, &:before{
                border-color: $primary-color !important;
            }
        }
        .md-tabs-navigation{
            background-color: transparent !important;

        }
        .md-button-content{
            color: $secondary-color !important;
        }
        .md-content{
            background-color: transparent !important;
        }
        .md-tab{
            color: $secondary-color !important;
        }
        .md-tabs-indicator{
            background-color: $secondary-color !important;
        }
    }
    .tab-image{
        top: 0px;
        position: absolute;
        height: 100%;
        opacity: 0.2;
    }
    .badge{
        color: $secondary-color;
        margin: 3px 10px 3px 0px;
    }
    .section-white{

        background-color: $white-color;
        color: $secondary-color;
        width: 100%;
        padding: 30px 0px;
    }
    .hover-image{
        cursor:pointer;
        transition: all 200ms;
        &:hover{
            -webkit-box-shadow: 1px 1px 5px 0px rgba(122,103,87,0.24);
            -moz-box-shadow: 1px 1px 5px 0px rgba(122,103,87,0.24);
            box-shadow: 1px 1px 5px 0px rgba(122,103,87,0.24);
        }
    }
    .control{
        &:hover{
            background-color: $primary-color;
            color: $white-color;
        }
        transition: all 200ms;
        cursor: pointer;
        background-color: $white-color;
        color: $secondary-color;
        font-weight: bold;
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 30px;
    }

    /* Hide HTML5 Up and Down arrows. */
    input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
    .number-input{
        font-size: 2rem;
        width: 100px;
        text-align: center;
        background-color: transparent !important;
        border: none !important;
        color: $white-color !important;
    }
    .signs{
        display: flex;
        position: absolute;
        top: -20px;
        left: 20px;
        padding: 10px;
        .sign{
            margin: 7px;
            width: 70px;
            height: 70px;
            border-radius: 35px;
            text-align:center;
            padding: 15px;
            background-color: $white-color;
            color: $primary-color;
            display:flex;
            justify-content:center;
            align-items:center;
            font-weight: bold;
            font-size: 0.7rem;
            -webkit-box-shadow: 3px 3px 14px -1px rgba(0,0,0,0.6);
            -moz-box-shadow: 3px 3px 14px -1px rgba(0,0,0,0.6);
            box-shadow: 3px 3px 14px -1px rgba(0,0,0,0.6);
        }
    }
</style>
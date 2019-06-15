<template>
    <div>
        <transition name="cartTransition">
            <div tabindex="0" class="cart" v-show="$parent.showCart" >

                <div v-if="cartDone && order == null" class="cart-left">
                    <h3 class="fadeInLeft animated delay-100">Wypełnij formularz zamówienia</h3>
                    <div class="col-md-12">
                        <div class="animated fadeInLeft delay-200">
                            <md-radio v-model="shipping" value="poczta">Poczta Polska 10 zł</md-radio>
                            <md-radio v-model="shipping" value="inpost">InPost / Paczkomat 10zł</md-radio>
                            <md-radio v-model="shipping" value="odbior">Odbiór osobisty (Wrocław)</md-radio>
                        </div>
                    </div>

                    <div class="col-md-12 animated fadeInLeft delay-300">
                        <md-field>
                            <label>Imię i nazwisko</label>
                            <md-input v-model="name"></md-input>
                        </md-field>
                    </div>
                    <div class="d-flex flex-wrap animated fadeInLeft delay-400">
                        <div class="col-md-3">
                            <md-field>
                                <label>Miasto</label>
                                <md-input v-model="city"></md-input>
                            </md-field>
                        </div>
                        <div class="col-md-3">
                            <md-field>
                                <label>Kod pocztowy</label>
                                <md-input v-model="postal_code"></md-input>
                            </md-field>
                        </div>
                        <div class="col-md-3">
                            <md-field>
                                <label>Ulica</label>
                                <md-input v-model="street"></md-input>
                            </md-field>
                        </div>
                        <div class="col-md-3">
                            <md-field>
                                <label>Nr ulicy</label>
                                <md-input v-model="street_number"></md-input>
                            </md-field>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap animated fadeInLeft delay-500">
                        <div class="col-md-6">
                            <md-field>
                                <label>Email</label>
                                <md-input v-model="email"></md-input>
                            </md-field>
                        </div>
                        <div class="col-md-6">
                            <md-field>
                                <label>Telefon</label>
                                <md-input v-model="phone"></md-input>
                            </md-field>
                        </div>
                    </div>
                    <div v-if="form_invalid">
                        <div v-for="(item, key) in $v.$params">
                            <div class="col-md-12 my-3"  v-if="$v[key].$invalid">
                                <span style="padding: 7px 17px" class="animated fadeIn alert alert-info">Pole {{key}} jest niepoprawne</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 animated fadeInLeft delay-600">
                        <md-button @click="makeOrder()" class="md-raised md-accent m-0 w-100 white-background font-weight-bold">
                            Wyślij zapytanie
                        </md-button>
                    </div>
                    <div class="col-md-12 mt-2 animated fadeInLeft delay-600">
                        <md-button @click="cartDone = false" class="md-raised md-accent m-0 w-100 white-background font-weight-bold">
                            Cofnij
                        </md-button>
                    </div>
                </div>
                <div v-if="order == null" class="cart-right animated fadeInRight" style="animation-duration:300ms;">
                    <div class="d-flex flex-wrap">
                        <div class="col-10">
                            <h2>Twój koszyk</h2>
                        </div>
                        <div class="col-2">
                            <md-button @click="$emit('my-event')" class=" float-right md-icon-button md-accent">
                                <i class="fas fa-times"></i>
                            </md-button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div v-if="cart != null">
                        <div v-for="(item, key) in cart.items" class="cart-item w-100 mb-3">
                            <div class="d-flex flex-wrap">
                                <div class="col-md-3 pr-0 mob-pl-0">
                                    <img v-if="item.type == 'product'" class="w-100" :src="getImage(JSON.parse(item.images)[0])">
                                    <img v-if="item.type == 'design'" class="w-100" :src="getImage(item.previewImage)">
                                </div>
                                <div class="col-md-7">
                                    <h3 class="mb-2">{{item.name}}</h3>
                                    <p class="mb-0">Ilość: {{item.quantity}}</p>
                                    <p class="mb-0">Cena za sztukę: {{item.price | toCurrency}}</p>
                                    <p class="mb-0">Długość zawieszki: {{item.length}}</p>
                                </div>
                                <div class="col-md-2 flex-column">
                                    <md-button :disabled="cartDone" @click="(editItem.id == item.id)? editItem={} : editItem = Object.assign({}, item)" class="md-mini md-icon-button md-raised md-primary m-0 mt-1">
                                        <i class="fas fa-cogs"></i>
                                    </md-button>
                                    <md-button :disabled="cartDone" @click="deleteItem(key)" class="md-mini md-icon-button md-raised md-accent m-0 mt-1">
                                        <i class="fas fa-trash"></i>
                                    </md-button>
                                </div>
                            </div>
                            <transition name="cartTransition">
                                <div class="edit-box" v-if="editItem.id == item.id">
                                    <h3 class="color-black">Personalizuj</h3>
                                    <div class="form-group">
                                        <label>Długość łańcuszka:</label>
                                        <input type="text" v-model="editItem.length">
                                    </div>
                                    <div class="form-group">
                                        <label>Ilość:</label>
                                        <input type="text" v-model="editItem.quantity">
                                    </div>
                                    <md-button @click="updateItem(key)" class="md-raised md-accent m-0 mt-2 w-100">Zapisz</md-button>
                                </div>
                            </transition>
                        </div>
                        <div  v-if="typeof cart.items != 'undefined'">
                            <div class="col-md-12 py-4" v-if="cart.items.length == 0">
                                <div class="row justify-content-center" >
                                    <span style="font-size: 1.5rem">Brak przedmiotów</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <div class="mt-3">
                            <h3 class="font-weight-bold">Podsumowanie</h3>
                            <p class="mb-1">Ilość produktów w koszyku: {{cart.itemsCount}}</p>
                            <p class="mb-1">Cena za całość: {{cart.totalPrice | toCurrency}}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <md-button :disable="cartDone" @click="cartDone =!cartDone" class="md-raised md-accent m-0 mt-2 w-100">{{(cartDone)? 'Edytuj koszyk' : 'Przejdź dalej'}}</md-button>
                    </div>
                </div>
                <div v-if="order != null" class="col-md-8 m-auto col-12 animated fadeIn text-center" style="padding:50px">
                    <h2 class="header1">Dziękuję za złożenie zapytania. Skontaktuję sie z tobą w ciągu kilku godzin.</h2>
                    <a :href="$root.base_url+'/order/'+order.hash" class="btn my-button white-color">Sprawdź swoje zamówienie tutaj</a>
                </div>
            </div>
        </transition>

    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import {
        required,
        email,
        minLength,
        maxLength,
        numeric
    } from 'vuelidate/lib/validators'
    export default {
        props: ['cart'],
        mixins: [validationMixin],
        validations: {
            shipping: {
                required,
            },
            name: {
                required,
                maxLength: 255,
            },
            email: {
                required,
                email,
            },
            postal_code:{
                required,
            },
            street: {
              required,
            },
            street_number:{
              required ,
              numeric,
            }
        },
        data(){
            return {
                order: null,
                editItem: {},
                cartDone: false,
                shipping: null,
                name: null,
                city: null,
                postal_code: null,
                street: null,
                street_number: null,
                email: null,
                phone: null,
                form_invalid : false,
            }
        },
        mounted() {
            let v =this;
            $(document).ready(function () {
                $('.cart').bind('keydown', function(event){
                  if(event.keyCode == 27){
                      v.close();
                  }
                })
            })
        },

        methods:{
            makeOrder(){
                let v =this;
                this.$v.$touch()
                if (this.$v.$invalid) {
                    console.log(this.$v);
                    this.form_invalid = true;
                }else{
                    axios.post(base_url+'/make_order', {
                        'shipping': this.shipping,
                        'name' : this.name,
                        'city' : this.city,
                        'postal_code' : this.postal_code,
                        'street' : this.street,
                        'street_number' : this.street_number,
                        'email' : this.email,
                        'phone' : this.phone
                    })
                        .then(function (response) {
                            v.order = response.data;
                        })
                }


            },
            close(){
                this.$emit('my-event');
            },
            getImage(src){
                return base_url+'/storage/'+src;
            },
            deleteItem(key){
                let v =this;
                  axios.delete('/cart/'+key)
                  .then(function (response) {
                      if(response.data){
                          v.$emit('change-cart', response.data);
                      }
                  })
            },
            updateItem(key){
                let v = this;
                axios.post(base_url+'/cart/'+key, {item: this.editItem})
                    .then(function (response) {
                        v.$emit('change-cart', response.data);
                        v.editItem = {};
                    })
            }
        }

    }
</script>
<style lang="scss">
    @import "../../sass/variables";
    .cartTransition-leave-active,
    .cartTransition-enter-active {
        transition: 200ms;
        opacity: 1;
    }
    .cartTransition-enter {
        opacity: 0;
    }
    .cartTransition-leave-to {
        opacity: 0;
    }
    .md-button-content{
        color:$white-color;
    }
    .cart-item{
        padding: 5px 0px;
        position: relative;
        h3{
            font-size: 1rem;
            font-weight: bold;
        }
        .edit-box{
            h3{
                color: black;
            }
            label{
                font-size: 0.8rem;
            }
            input{
                width: 100%;
                background-color: transparent;
                border: none;
                color: $primary-color;
                border-bottom: 1px solid $primary-color;
            }
            .md-field{
                margin-bottom: 10px;
            }
            padding: 5px;
            padding-right: 15px;
            position: absolute;
            top:0px;
            width: 100%;
            left: -100%;
            background-color: $white-color;
            .md-field{
                input{
                    color: $primary-color !important;
                    -webkit-text-fill-color: unset !important;
                }
            }
            .md-focused{

                label, input{
                    color: $primary-color !important;
                }
                &:before{
                    background-color: $primary-color !important;
                }
                &:after{
                    background-color: $white-color !important;
                }
            }
        }
    }
    .md-mini{
        width: 30px;
        height: 30px;
        min-width: 30px;
    }
</style>
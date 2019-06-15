<template>
    <div class="give-me-space">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h4>Dokończ konfigurację</h4>
                <md-field>
                    <label for="material">Na czym ma być zawieszona zawieszka</label>
                    <md-select v-model="material" name="material" id="material">
                        <md-option value="Łańcuszek srebrny">Łańcuszek srebrny</md-option>
                        <md-option value="Łańcuszek brąz">Łańcuszek brąz</md-option>
                        <md-option value="Rzemyk czarny">Rzemyk czarny</md-option>
                        <md-option value="Rzemyk brązowy">Rzemyk brązowy</md-option>
                        <md-option value="Aksamitka">Aksamitka</md-option>
                        <md-option value="Dusik">Dusik</md-option>
                    </md-select>
                </md-field>

                <md-field>
                    <label>Długość zawieszki</label>
                    <md-input v-model="length"></md-input>
                </md-field>

                <transition name="slide-fade">
                    <div v-show="showErrors" class="my-2">
                        <span class="alert alert-dark" v-if="!$v.material.required">Wybierz jakieś pole</span>
                        <span class="alert alert-dark" v-if="!$v.length.required">Podaj długość zawieszki</span>
                    </div>
                </transition>

                <md-button @click="validateOrder()" class="md-raised md-accent" style="margin: 10px 0px 0px; height: 80px; width: 100%;">Dodaj do koszyka</md-button>

            </div>
            <div class="col-md-6">
                <img v-if="img != null" :src="img.src">
            </div>
        </div>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import {
        required,
        email,
        minLength,
        maxLength
    } from 'vuelidate/lib/validators'
    export default {
        mixins: [validationMixin],
        validations: {
            length: {
                required,
            },
            material: {
                required,
            }
        },
        props: ['design', 'img', 'sItem'],
        data(){
            return{
                showErrors: false,
                desc: null,
                length: null,
                material: null,
            }
        },
        watch:{
          showErrors: function () {
              let v = this;
              setTimeout(function () {
                  v.showErrors = false;
              }, 5000)
          }
        },
        mounted() {
            console.log(this.img);
        },
        methods:{
            saveOrder(){
                var formData = new FormData();
                let v =this;
                var blob = dataURItoBlob(this.img.src);
                formData.append('file', blob);

                formData.append('desc', this.desc);
                formData.append('length', this.length);
                formData.append('material', this.material);
                formData.append('design', JSON.stringify(this.design));
                formData.append('sItem',  JSON.stringify(this.sItem));
                axios.post(base_url+'/designs', formData)
                    .then(function (response) {
                        v.$root.addToCartDesign(response.data, 1);
                    })
            },
            validateOrder () {
                this.$v.$touch()
                if (!this.$v.$invalid) {
                    this.saveOrder();
                }else{
                    this.showErrors = true;
                }
            }
        }
    }
</script>
<style lang = 'scss'>
    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
        /* .slide-fade-leave-active below version 2.1.8 */ {
        transform: translateY(20px);
        opacity: 0;
    }
</style>

<template>
    <div class="filters">
        <transition name="fade">
            <div class="mb-4" v-if="isSelectedAnyFilter()">
                <p class="mb-2">Wybrane filtry:</p>
                <span v-for="(item) in selectedFilters.selectedMaterials">
                    <transition-group name="fade">
                        <span :key="item" @click="deleteFilter(item, 'selectedMaterials')" class="badge badge-pill badge-primary">{{item}}</span>
                    </transition-group>
                </span>
                <span v-for="(item) in selectedFilters.selectedTags">
                    <transition-group name="fade">
                        <span :key="'tag'+item.id" @click="deleteFilter(item, 'selectedTags')" class="badge badge-pill badge-primary">{{item.tag}}</span>
                    </transition-group>
                </span>
                <span v-for="(item) in selectedFilters.selectedCollections">
                    <transition-group name="fade">
                        <span :key="'collection'+item.id" @click="deleteFilter(item, 'selectedCollections')" class="badge badge-pill badge-primary">{{item}}</span>
                    </transition-group>
                </span>
                <transition-group name="fade">
                    <span key='pricefilter' @click="deletePrice()" v-if="priceSet" class="badge badge-pill badge-primary">{{selectedFilters.selectedPrice[0]}} - {{selectedFilters.selectedPrice[1]}}</span>
                    <span key='selectedsort' v-if="selectedFilters.selectedSort != null" @click="selectedFilters.selectedSort = null" class="badge badge-pill badge-primary">{{getSortName()}}</span>
                </transition-group>
            </div>
        </transition>
        <p class="sortHeader" data-toggle="collapse" data-target="#price_collapse">Cena: </p>
        <div class="collapse w-100 show" id="price_collapse">
            <vue-slider v-on:change="setPrice()" v-model="selectedFilters.selectedPrice" :min="0" :max="200" :height='10' :dotSize="20" :enable-cross="false">
                <template #tooltip="{ index }">
                    <span v-if="index === 0" class="vue-slider-dot-tooltip-text">{{selectedFilters.selectedPrice[0]}} zł</span>
                    <span v-else class="vue-slider-dot-tooltip-text">{{selectedFilters.selectedPrice[1]}} zł</span>
                </template>
            </vue-slider>
        </div>
        <div v-if="collections">
            <p class="mt-4 sortHeader" data-toggle="collapse" data-target="#collections_collapse">Kolekcje: </p>
            <div class="collapse w-100 show" id="collections_collapse">
                <md-checkbox v-on:change="changeFilters()" class="w-100 mt-2 mb-0" v-for="collection in collections" v-model="selectedFilters.selectedCollections" :value="collection.name">{{collection.name}}</md-checkbox>
            </div>
        </div>

        <p class="mt-4 sortHeader" data-toggle="collapse" data-target="#materials_collapse">Materiały: </p>
        <div class="collapse w-100 show" id="materials_collapse">
         <md-checkbox v-on:change="changeFilters()" class="w-100 mt-2 mb-0" v-for="material in materials" v-model="selectedFilters.selectedMaterials" :value="material.name">{{material.name}}</md-checkbox>
        </div>
        <p class="mt-4 sortHeader" data-toggle="collapse" data-target="#sort_collapse">Sortuj według:</p>
        <div class="collapse show w-100" id="sort_collapse">
            <md-field class="m-0 pt-3">
                <label>Wybierz opcję</label>
                <md-select :md-selected="changeFilters()" v-model="selectedFilters.selectedSort">
                    <md-option value="priceAsc">Cena rosnąco</md-option>
                    <md-option value="priceDesc">Cena malejąco</md-option>
                    <md-option value="mostViewed">Najczęściej oglądane</md-option>
                </md-select>
            </md-field>
        </div>
        <div v-if="tags">
            <p class="mt-4 sortHeader" data-toggle="collapse" data-target="#tags_collapse">Tagi:</p>
            <div class="collapse show" id="tags_collapse">
                <span @click="addTag(tag)" class="badge badge-pill badge-primary badge-tag" :class="{'badge-active' : isTagSelected(tag)}" v-for="tag in tags">{{tag.tag}}</span>
            </div>
        </div>
        <md-field>
            <label><i class="fa fa-search mr-2" style="font-size:0.8rem"></i>Wpisz szukany element</label>
            <md-input v-on:change="changeFilters()" v-model="selectedFilters.search"></md-input>
        </md-field>

    </div>
</template>

<script>
    import VueSlider from '../../../../node_modules/vue-slider-component';
    export default {
        components:{
            VueSlider: VueSlider
        },
        props:['tags', 'inputs', 'collections'],
        data(){
            return{
                priceSet: false,
                materials: [],
                selectedFilters: {
                    selectedCollections: [],
                    selectedMaterials: [],
                    selectedPrice: [0,150],
                    selectedSort: [],
                    selectedTags: [],
                    search: '',
                },
            }
        },

        mounted() {
            this.getMaterials();
            let v =this;
            if(this.inputs.collections){
                this.inputs.collections.forEach(function (data) {
                    v.selectedFilters.selectedCollections.push(data);
                })
            }
        },
        created(){
            if(Object.keys(this.inputs).length > 0){
                if(this.inputs.sort_by && this.inputs.sort_by == 'priceAsc'){
                    this.selectedFilters.selectedSort = 'priceAsc';
                }
                if(this.inputs.sort_by && this.inputs.sort_by == 'priceDesc'){
                    this.selectedFilters.selectedSort = 'priceDesc';
                }
                if(this.inputs.sort_by && this.inputs.sort_by == 'mostViewed'){
                    this.selectedFilters.selectedSort = 'mostViewed';
                }
                if(this.inputs.price_from && this.inputs.price_to){
                    this.selectedFilters.selectedPrice[0] = this.inputs.price_from;
                    this.selectedFilters.selectedPrice[1] = this.inputs.price_to;
                    this.priceSet = true;
                }
                if(this.inputs.tags){
                    let v =this;
                    this.inputs.tags.forEach(function (data) {
                        var obj = v.tags.find(x => x.tag == data);
                        if(obj) v.selectedFilters.selectedTags.push(obj);
                    });
                }
            }
        },
        methods:{
            getSortName(){
              switch (this.selectedFilters.selectedSort) {
                  case 'priceAsc':
                      return 'Cena rosnąco';
                      break;
                  case 'priceDesc':
                      return 'Cena malejąco';
                      break;
                  case 'mostViewed':
                      return 'Najczęściej oglądane';
                      break;
              }
            },
            addTag(tag){
                this.selectedFilters.selectedTags.push(tag);
            },
            isTagSelected(tag){
              if(this.selectedFilters.selectedTags.find(x => x.id == tag.id)) return true;
              return false;
            },
            deleteFilter(item, key){
              this.selectedFilters[key].splice(this.selectedFilters[key].indexOf(item), 1);
            },
            deletePrice(){
              this.selectedFilters.selectedPrice = [0,150];
              this.priceSet = false;
            },
            isSelectedAnyFilter(){
              if(this.selectedFilters.selectedMaterials.length > 0) return true;
              if(this.priceSet) return true;
              if(this.selectedFilters.selectedTags.length > 0) return true;
              if(this.selectedFilters.selectedSort != null) return true;
              if(this.selectedFilters.selectedCollections.length > 0) return true;

                return false;
            },
            setPrice(){
                this.priceSet = true;
                this.changeFilters();
            },
            changeFilters(){
                var statemant = '?';
                if(this.selectedFilters.selectedMaterials){
                    this.selectedFilters.selectedMaterials.forEach(function (data) {
                        statemant = statemant+'materials[]='+data+'&';
                    })
                }
                if(this.selectedFilters.selectedCollections){
                    this.selectedFilters.selectedCollections.forEach(function (data) {
                        statemant = statemant+'collections[]='+data+'&';
                    })
                }
                if(this.selectedFilters.selectedPrice && this.priceSet){
                    statemant = statemant+'price_from='+this.selectedFilters.selectedPrice[0]+'&price_to='+this.selectedFilters.selectedPrice[1]+'&';
                }
                if(this.selectedFilters.selectedTags){
                    this.selectedFilters.selectedTags.forEach(function (data) {
                        statemant = statemant+'tags[]='+data.tag+'&';
                    })
                }
                history.replaceState(null, null, statemant);
                this.$emit('change-filters', this.selectedFilters);
            },
            getMaterials(){
                let v =this;
                axios.get(base_url+'/materials')
                    .then(function (response) {
                        v.materials = response.data;
                        if(v.inputs.materials){
                            v.inputs.materials.forEach(function (data) {
                                let obj = v.materials.find(x => x.name == data);
                                if(obj) v.selectedFilters.selectedMaterials.push(data);
                            })
                        }
                    })
            }
        }
    }
</script>
<style lang="scss">
    @import '../../../sass/_variables';
    .md-field{
        label{
            color: $white-color !important;
        }
        &:after{
            background-color: $white-color !important;
        }
        input{
            -webkit-text-fill-color: $white-color !important;
        }
    }
        .md-checkbox-container{
            border-color:$white-color !important;
        }
    .md-checked{
        .md-checkbox-container{
            background-color:$white-color !important;
        }
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
    .filters{
        hr{
            margin: 5px 0px 5px 0px;
            background-color: rgba($white-color, 0.3);
        }
        p, label{
            color: $white-color;
        }
        .md-ripple{
            color: $primary-color !important;
        }

        .collapsed{
                &:after{
                    transform: rotate(0deg);
                }

        }
        .badge-tag{
            &:after{
                content: "" !important;
            }
        }
        .badge-active{
            background-color: white !important;
            color: $primary-color !important;
        }
        .badge{
            margin: 0px 5px 5px 0px;
            padding: 5px 8px;
            cursor: pointer;
            transition: all 200ms;
            background-color: $primary-color;
            &:hover{
                background-color: white;
                color: $primary-color;
            }
            &:after{
                font-family: "Font Awesome 5 Free";
                font-weight: 900;
                transform: rotate(180deg);
                content: "\f00d";
                margin-left: 5px;
                transform: scale(1);
            }
        }
    }

    .sortHeader{
        cursor: pointer;
        position: relative;
        &:after{
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            transform: rotate(180deg);
            content: "\f0d7";
            position:absolute;
            right: 0px;
            opacity: 0.3;
            transition: all 300ms;
        }
    }
</style>
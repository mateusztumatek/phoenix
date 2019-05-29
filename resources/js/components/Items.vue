<template>
    <div class="row justify-content-center">
        <md-card v-for="item in items" md-with-hover >
            <img class="w-100" :src="getItemImage(item)">
            <md-card-actions>
                <md-button v-if="selectedItem != item" @click="selectItem(item)" class="md-raised md-primary">Wybierz</md-button>
            </md-card-actions>
        </md-card>
    </div>
</template>

<script>
    export default {
        props:['selectedItem'],

        data(){
            return{
                items: null,
            }
        },
        mounted() {
            this.getItems();
        },
        methods:{
            selectItem(item){
                this.$emit('changeSItem', item);
            },
            getItems(){
                let v = this;
                axios.get(base_url+'/admin/items')
                    .then(function (response) {
                        v.items = response.data;
                    })
            },
            getItemImage(item){
               return base_url+'/creator_items/'+item.project_photo;
            }
        },

    }
</script>

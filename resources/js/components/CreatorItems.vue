<template>
    <div class="draw-component" @keydown="keyD">
        <md-snackbar :md-position="'center'" :md-duration="4000" :md-active.sync="errors" @md-closed="errors = null">
            <span v-for="error in errors">{{error}}</span>
        </md-snackbar>
        <div class="row justify-content-between">

            <div class="col-md-6">
                <transition name="slide-fade">
                    <div v-if="form_errors.length>0" class="alert alert-danger" role="alert">
                        <div v-for="err in form_errors" style="color: black">{{err}}</div>
                    </div>
                </transition>

                <md-autocomplete v-model="selectedProduct" :md-options="products">
                    <label>Select Product (not required)</label>
                    <template slot="md-autocomplete-item" slot-scope="{ item }">{{ item.name }}</template>
                </md-autocomplete>
                <md-content class="md-elevation-7">

                    <div class="draw-block-content">
                        <h3 style="font-size: 1rem">Wybierz maskę</h3>
                    </div>
                    <div class="d-flex">
                        <div class="col-sm-1"  @click="changeMask('circle')">
                            <i :class="{active: circle.selected}" style="font-size: 40px" class="fa fa-circle icc"></i>
                        </div>
                        <div class="col-sm-1" @click="changeMask('rect')">
                            <i :class="{active: rect.selected}" style="font-size: 40px" class="fa fa-square icc"></i>
                        </div>
                    </div>
                    <p>Zaokrąglone rogi: </p>
                    <input type="range" min="0" max="50" step="1" v-model="rect.cornerRadius" />
                </md-content>

                <md-content class="md-elevation-7">
                    <div class="draw-block-content">
                        <h3 style="font-size: 1rem">Dodaj zdjęcie produktu</h3>
                    </div>
                    <md-field>
                        <label>Kliknij aby dodać zdjęcie produktu</label>
                        <md-file :disabled="loading" @change="uploadImage('photo', $event)" accept="image/*" />
                    </md-field>
                </md-content>
                <md-content class="md-elevation-7">
                    <div class="draw-block-content">
                        <h3 style="font-size: 1rem">Dodaj maskę produktu</h3>
                    </div>
                    <md-field>
                        <label>Kliknij aby dodać zdjęcie maski produktu</label>
                        <md-file :disabled="loading" @change="uploadImage('mask', $event)" accept="image/*" />
                    </md-field>
                    <div class="w-100" v-if="mask.src != ''">
                        <md-button class="md-raised md-primary" @click="showMask = !showMask">
                            <span v-if="showMask">Schowaj maskę</span>
                            <span v-if="!showMask">Pokaż maskę</span>

                        </md-button>
                    </div>
                </md-content>
                <md-content class="md-elevation-7">
                    <md-field>
                        <label>Nazwa</label>
                        <md-textarea v-model="name"></md-textarea>
                    </md-field>
                    <md-field>
                        <label>Cena produktu</label>
                        <md-input type="number" v-model="price"></md-input>
                    </md-field>
                    <md-button class="md-raised md-primary" @click="save()">
                        Zapisz
                    </md-button>
                </md-content>

            </div>
            <div id="konva-wrapper" class="col-md-6 p-0 konva-wrapper">
                <v-stage :config="configKonva" ref="stage" @mousedown="handleStageMouseDown" @mouseup="updateElements()">
                    <v-layer>
                        <v-image :config="photo" ></v-image>
                    </v-layer>
                    <v-layer ref="layer" :config="configLayer">
                        <v-rect v-if="rect.selected" :ref="'rect'" :config="rect" />
                        <v-circle v-if="circle.selected" :ref="'circle'" :config="circle" />

                        <v-transformer ref="transformer"></v-transformer>

                    </v-layer>
                    <v-layer>
                        <v-image :config="mask" v-if="showMask"></v-image>
                    </v-layer>


                </v-stage>
            </div>

        </div>
    </div>
</template>

<script>
    import VueKonva from 'vue-konva';

    export default {
        props: ['products', 'product'],
        data() {
            return {
                loading: false,
                upload_images:[],

                mask: {
                    x: 0,
                    y: 0,
                    width: 200,
                    height: 200,
                    src:'',
                    image:new Image(),
                    type:'background',
                    draggable: false
                },
                photo: {
                    x: 0,
                    y: 0,
                    width: 200,
                    height: 200,
                    src:'',
                    image:new Image(),
                    type:'background',
                    draggable: false
                },
                configKonva: {
                    container: 'konva-wrapper',
                    width: 0,
                    height: 200,
                    drawBorder:true,

                },
                rect:{
                    cornerRadius: 0,
                    draggable: true,
                    name: 'rect',
                    selected: true,
                    x: 20,
                    y: 50,
                    width: 100,
                    height: 100,
                    fill: 'rgba(15,131,205,0.5)',
                },
                circle:{
                    draggable: true,
                    selected: false,
                    name: 'circle',
                    x: 20,
                    y: 50,
                    radius: 40,
                    fill: 'rgba(15,131,205,0.5)',
                },
                configLayer:{
                    clipFunc:null
                },
                name: '',
                price: null,
                showMask: true,
                prods: null,
                selectedProduct: null,
                form_errors: [],
                errors: null,
                selectedShapeName: '',
                selectedObject: null,
            };
        },
        mounted() {
            this.configKonva.width = $('.konva-wrapper').width();
            this.configKonva.height = $('.konva-wrapper').width();
            this.circle.x = $('.konva-wrapper').width()/2;
            this.circle.y = $('.konva-wrapper').width()/2;
            this.rect.x = $('.konva-wrapper').width()/2;
            this.rect.y = $('.konva-wrapper').width()/2;
            var container =  this.$refs.stage.getNode().container();
            container.tabIndex = 1;
            this.initProduct(this.product);


        },
        watch: {
            product: function(val){
                this.initProduct(val);
            }
        },
        methods: {
            initProduct(item){
                this.mask.src = base_url+'/creator_items/'+item.mask_photo;
                this.addImage(this.photo.src = base_url+'/creator_items/'+item.project_photo, 'photo');
                this.addImage(this.mask.src = base_url+'/creator_items/'+item.project_mask, 'mask');
                if(item.data){
                    var data = JSON.parse(item.data);
                    if(data){
                        if(data.radius){
                            this.circle.radius = data.radius;
                            this.circle.x = data.x;
                            this.circle.scaleX = data.scaleX;
                            this.circle.scaleY = data.scaleY;
                            this.circle.y = data.y;
                            this.selectedObject = this.circle;
                            this.changeMask('circle');
                        }else{
                            this.rect.x = data.x;
                            this.rect.y = data.y;
                            this.rect.width = data.width;
                            this.rect.height = data.height;
                            this.rect.cornerRadius = data.cornerRadius;
                            this.selectedObject = this.rect;
                            this.changeMask('rect');
                        }
                    }else{
                        this.changeMask('rect');

                    }
                }
                this.name = item.name;
                this.price= item.price;
            },
            save(){
                let v = this;
                this.validate();
                if(this.form_errors.length > 0) return null;
                if(this.selectedObject){
                    var obj = {};
                    if(this.$parent.selectedItem.id){
                        obj.id=this.$parent.selectedItem.id;
                    }
                    if(this.selectedObject.name == 'rect'){
                        obj.width = this.selectedObject.width * this.selectedObject.scaleX;
                        obj.height = this.selectedObject.height * this.selectedObject.scaleY;
                        obj.x = this.selectedObject.x;
                        obj.type = 'rect';
                        obj.y = this.selectedObject.y;
                        obj.cornerRadius = this.selectedObject.cornerRadius;
                        obj.konvaWidth = this.configKonva.width;
                        obj.konvaHeight = this.configKonva.height;
                    }
                    if(this.selectedObject.name == 'circle'){
                        obj.width = (this.selectedObject.radius * this.selectedObject.scaleX) * 2;
                        obj.height = (this.selectedObject.radius * this.selectedObject.scaleY) * 2;
                        obj.radius = this.selectedObject.radius;
                        obj.scaleX = this.selectedObject.scaleX;
                        obj.scaleY = this.selectedObject.scaleY;
                        obj.type = 'circle';
                        obj.x = this.selectedObject.x;
                        obj.y = this.selectedObject.y;
                        obj.konvaWidth = this.configKonva.width;
                        obj.konvaHeight = this.configKonva.height;
                    }
                }

                var data = {
                    photo: this.photo.src,
                    mask: this.mask.src,
                    data: obj,
                    name: this.name,
                    price: this.price,
                };
                axios.post((obj.id)?base_url+'/admin/update_project/'+obj.id:base_url+'/admin/save', data)
                    .then(function (response) {
                        window.location.href= base_url+'/admin/projektuj';
                    });
            },
            validate(){
                if(this.photo.src == '') this.form_errors.push('Wymagane zdjęcie');
                if(this.mask.src == '') this.form_errors.push('Wymagane zdjęcie maski');
                if(this.name == '' || typeof this.name == 'undefined') this.form_errors.push('Musisz podac nazwę produktu');
                if(this.price == null) this.form_errors.push('Musisz podać cenę produktu');

                var v = this;
                setInterval(function () {
                    v.form_errors = [];
                }, 4000)
            },
            keyD(e){
                if(this.selectedShapeName != ''){
                    const transformerNode = this.$refs.transformer.getStage();
                    const stage = transformerNode.getStage();
                    const { selectedShapeName } = this;
                    const selectedNode = stage.findOne('.' + selectedShapeName);
                    if (e.keyCode === 37) {
                        event.preventDefault();
                        selectedNode.x(selectedNode.x() - 1);
                    } else if (e.keyCode === 38) {
                        event.preventDefault();
                        selectedNode.y(selectedNode.y() - 1);
                    } else if (e.keyCode === 39) {
                        event.preventDefault();
                        selectedNode.x(selectedNode.x() + 1);
                    } else if (e.keyCode === 40) {
                        event.preventDefault();
                        selectedNode.y(selectedNode.y() + 1);
                    } else {
                        return;
                    }


                }
            },
            addImage(src, type){
                var name = 'image'+Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                if(type == 'photo'){
                    this.photo = {
                        x: 0,
                        y: 0,
                        width: this.configKonva.width,
                        height: this.configKonva.height,
                        name: name,
                        src: src,
                        draggable: false,
                        image:new Image(),
                        type: 'image',
                    };
                }else{
                    this.mask = {
                        x: 0,
                        y: 0,
                        width: this.configKonva.width,
                        height: this.configKonva.height,
                        name: name,
                        src: src,
                        draggable: false,
                        image:new Image(),
                        type: 'image',
                    };
                }
                this.initImages();
            },
            changeMask(name){
              if(name == 'circle'){
                  this.circle.selected = true;
                  this.rect.selected = false;
                  this.selectedShapeName= '';
                  this.updateTransformer();
              }else{
                  this.rect.selected = true;
                  this.circle.selected = false;
                  this.selectedShapeName= '';
                  this.updateTransformer();
              }
            },
            handleStageMouseDown(e) {
                // clicked on stage - cler selection
                if (e.target === e.target.getStage()) {
                    this.selectedShapeName = '';
                    this.updateTransformer();
                    return;
                }
                // clicked on transformer - do nothing
                const clickedOnTransformer =
                    e.target.getParent().className === 'Transformer';
                if (clickedOnTransformer) {
                    return;
                }
                var name = e.target.name();
                if(name == 'rect'){
                    var element = this.rect;
                    this.selectedObject = this.rect;
                }
                if(name == 'circle'){
                    var element = this.circle;
                    this.selectedObject = this.circle;
                }
                if (element) {
                    this.selectedShapeName = name;
                } else {
                    this.selectedShapeName = '';
                }
                this.updateTransformer();

            },

            updateTransformer() {
                // here we need to manually attach or detach Transformer node
                const transformerNode = this.$refs.transformer.getStage();
                const stage = transformerNode.getStage();
                const { selectedShapeName } = this;
                const selectedNode = stage.findOne('.' + selectedShapeName);
                // do nothing if selected node is already attached
                if (selectedNode === transformerNode.node()) {
                    return;
                }
                if (selectedNode) {
                    // attach to another node
                    transformerNode.attachTo(selectedNode);
                } else {
                    // remove transformer
                    transformerNode.detach();
                }
                transformerNode.anchorCornerRadius(5);
                transformerNode.anchorFill('#836e5d');
                transformerNode.anchorStroke('white');

                transformerNode.getLayer().batchDraw();
            },
            initImages(){
                if(this.photo.src != null){
                    var img = new Image();
                    img.src = this.photo.src;
                    var t = this;
                    img.onload = function(){
                        t.photo.image = img;
                    };
                }
                if(this.mask.src != null){
                    var img2 = new Image();
                    img2.src = this.mask.src;
                    var t = this;
                    img2.onload = function(){
                        t.mask.image = img2;
                    };
                }
            },
            uploadImage(type, event){
                this.loading = true;
                let tmp_img = event.target.files[0];
                let formData = new FormData();
                formData.append('file',tmp_img);
                formData.append('creator_item', true);
                axios
                    .post('http://127.0.0.1:8000/upload',formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response=>{
                        if(response.data.errors){
                            this.errors = response.data;
                            this.loading = false;
                        }else{
                            if(type == 'photo'){
                                this.photo.src = response.data.image;
                                this.loading = false;
                                this.addImage(this.photo.src, type);
                            }
                            if(type == 'mask'){
                                this.mask.src = response.data.image;
                                this.loading = false;
                                this.addImage(this.mask.src, type);
                            }
                        }
                    })
            },
            updateElements(){
                const stage = this.$refs.stage.getStage();
                const { selectedShapeName } = this;
                const selectedNode = stage.findOne('.' + selectedShapeName);
                if(typeof selectedNode != 'undefined'){
                    if(selectedNode.attrs.name == 'rect'){
                        this.rect = selectedNode.attrs;
                    }else{
                        this.circle = selectedNode.attrs;
                    }
                }
            },
            deleteObject(item){
                if(item.type == 'image'){
                    const elem = this.images.find(r => r.name === this.selectedObject.name);

                    this.images.splice(this.images.indexOf(elem), 1);
                }else{
                    const elem = this.texts.find(r => r.name === this.selectedObject.name);
                    this.texts.splice(this.texts.indexOf(elem), 1);
                }
                this.selectedObject = null;
                this.selectedShapeName = '';
                const transformerNode = this.$refs.transformer.getStage();
                transformerNode.detach();
            },

        },
    }
</script>

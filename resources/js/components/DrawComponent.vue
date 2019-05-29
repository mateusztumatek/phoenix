<template>
    <div class="draw-component">
        <div class="row justify-content-between">
            <div class="col-sm-12 mb-2"><h1 class="header text-center">Zaprojektuj swoją biżuterię</h1></div>
            <md-steppers :md-active-step.sync="active" md-alternative class="w-100" md-linear>
                <md-step id="first" md-label="First Step" :md-done.sync="first">
                        <items :selectedItem="sItem" v-on:changeSItem="updateSItem($event)"></items>
                </md-step>

                <md-step id="second" md-label="Second Step" :md-done.sync="second" >
                    <div class="w-100 d-flex">
                        <div class="col-md-6 pl-0">

                            <transition name="fade">
                                <div v-if="loading" class="load">
                                    <md-progress-spinner class="md-accent" :md-diameter="30" md-mode="indeterminate"></md-progress-spinner>
                                </div>
                            </transition>

                            <md-content class="md-elevation-7">
                                <div class="draw-block-content">
                                    <h3>Dodaj plik z dysku</h3>
                                    <vue-dropzone       v-on:vdropzone-thumbnail="thumbnail" v-on:vdropzone-file-added="fileAdded" v-on:vdropzone-removed-file="rr" ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"></vue-dropzone>

                                   <!-- <md-field>
                                        <label>Kliknij aby dodać plik</label>
                                        <md-file :disabled="loading" @change="uploadImage" accept="image/*" />
                                    </md-field>-->
                                  <!--  <div class="uploaded_images" v-if="upload_images.length > 0">
                                        <div class="image" v-for="image in upload_images">
                                            <img :src="image">
                                            <div class="content" style="flex-wrap: wrap">
                                                <md-button @click="addImage(image)" class="md-raised md-accent w-100">Dodaj do projektu</md-button>
                                                <md-button @click="removeImage(image)" class="md-raised md-accent w-100" style="margin: 6px 8px !important;">Usuń obrazek</md-button>
                                            </div>
                                        </div>
                                    </div>-->
                                  <!--  <md-button :disabled="loading" @click="addText()" class="md-raised md-accent">Dodaj tekst</md-button>-->

                                    <md-button :disabled="loading" @click="clipLayer()" class="md-raised md-accent">{{(configLayer.clipFunc ==null)? 'Przytnij projekt' : 'Pokaż całość'}}</md-button>

                                </div>
                            </md-content>

                            <md-content class="md-elevation-7 mt-3" v-if="selectedShapeName != ''">

                                <h3 class="header">Edytuj element
                                </h3>
                                <md-button @click="deleteObject(selectedObject)" class="md-raised md-primary">Usuń element</md-button>


                                <!--
                                                                <md-button @click="moveUp(selectedObject)" class="md-raised md-primary">Na wierzch</md-button>
                                -->

                                <div v-if="selectedObject.type == 'text'">
                                    <md-field >
                                        <label>Textarea</label>
                                        <md-textarea v-model="texts[texts.indexOf(selectedObject)].text"></md-textarea>
                                    </md-field>
                                    <md-field>
                                        <label>Rozmiar czcionki</label>
                                        <md-input v-model="texts[texts.indexOf(selectedObject)].fontSize" type="number"></md-input>
                                    </md-field>
                                    <md-field>
                                        <label for="czcionka">Czcionka</label>
                                        <md-select v-model="texts[texts.indexOf(selectedObject)].fontFamily" name="czcionka" id="czcionka">
                                            <md-option v-for="font in fonts" v-bind:value="font"><span v-bind:style="{fontFamily: font}">{{font}}</span></md-option>
                                        </md-select>
                                    </md-field>
                                    <div class="creator-icons row justify-content-center">
                                        <i v-bind:class="{active: texts[texts.indexOf(selectedObject)].align == 'left'}" @click="texts[texts.indexOf(selectedObject)].align = 'left'" class="fa fa-align-left"></i>
                                        <i v-bind:class="{active: texts[texts.indexOf(selectedObject)].align == 'right'}" @click="texts[texts.indexOf(selectedObject)].align = 'right'" class="fa fa-align-right"></i>
                                        <i v-bind:class="{active: texts[texts.indexOf(selectedObject)].align == 'center'}" @click="texts[texts.indexOf(selectedObject)].align = 'center'" class="fa fa-align-center"></i>
                                        <i v-bind:class="{active: texts[texts.indexOf(selectedObject)].align == 'justify'}" @click="texts[texts.indexOf(selectedObject)].align = 'justify'" class="fa fa-align-justify"></i>

                                    </div>

                                    <md-field>
                                        <label for="styl">Styl czcionki</label>
                                        <md-select v-model="texts[texts.indexOf(selectedObject)].fontStyle" name="styl" id="styl">
                                            <md-option value="normal">Normal</md-option>
                                            <md-option value="bold">Bold</md-option>
                                            <md-option value="italic">Italic</md-option>
                                        </md-select>
                                    </md-field>
                                    <input type="color" v-model="texts[texts.indexOf(selectedObject)].fill">

                                </div>

                                <div class="draw-block-content" v-if="selectedObject.type == 'image'">
                                    <p>Jasność: </p>
                                    <input v-on:input="change()" type="range" min="-1" max="1" step="0.01" v-model="filters.brightness" />
                                    <p>Kontrast: </p>
                                    <input v-on:input="change()" type="range" min="-50" max="50" step="1" v-model="filters.kontrast" />
                                    <p>Rozmycie: </p>
                                    <input v-on:input="change()" type="range" min="0" max="40" step="0.05" v-model="filters.blur" />
                                    <p>Saturacja: </p>
                                    <input v-on:input="change()" type="range" min="-2" max="5" step="0.1" v-model="filters.saturation" />
                                </div>
                            </md-content>
                        </div>
                        <div class="col-md-6 p-0 konva-wrapper">
                            <v-stage :config="configKonva" ref="stage" @mousedown="handleStageMouseDown" @mouseup="updateElements()">
                                <v-layer>
                                    <v-image :config="image" ></v-image>
                                </v-layer>
                                <v-layer ref="layer" :config="configLayer">
                                    <v-image :ref="'design'" :config="design" />
                                    <v-transformer ref="transformer"></v-transformer>

                                </v-layer>
                                <v-layer>
                                    <v-image :config="mask" v-if="configLayer.clipFunc != null"></v-image>
                                </v-layer>


                            </v-stage>
                        </div>
                    </div>
                </md-step>

                <md-step id="third" md-label="Third Step" :md-done.sync="third">
                </md-step>
            </md-steppers>

        </div>
    </div>
</template>

<script>
    import VueKonva from 'vue-konva';
    import vue2Dropzone from '../../../node_modules/vue2-dropzone';
    import '../../../node_modules/vue2-dropzone/dist/vue2Dropzone.min.css';
    export default {
        props: ['project', 'files'],
        components:{
            vueDropzone: vue2Dropzone
        },
        data() {
            return {
                dropzoneOptions: {
                    url: base_url+'/upload',
                    thumbnailWidth: 300,
                    thumbnailHeight: 300,
                    addRemoveLinks: true,
                    maxFilesize: 2,
                    headers: { 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content }
                },
                active: 'first',
                first: false,
                second: false,
                third: false,
                fonts: ['Arial', 'Calibri', 'Times New Roman', 'Forte', 'Comic Sans MS', 'Impact', 'Arial Black'],
                loading: false,
                upload_images:[],
                filters: {
                    brightness: 0,
                    kontrast: 0,
                    blur: 0,
                    saturation: 0
                },
                mask: {
                    width: null,
                    height:null,
                    image:null,
                    type:'background',
                },
                image: {
                    width: null,
                    height:null,
                    image:null,
                    type:'background',
                },
                design:{},
                configKonva: {
                    width: 0,
                    height: 200,
                    drawBorder:true,

                },
                element: {},
                configLayer:{
                    clipFunc:null
                },
                sItem:null,
                group: null,
                selectedShapeName: '',
                selectedObject: null,
                clipData: null,
                token: '',
            };
        },
        mounted() {
            this.configKonva.width = $('.konva-wrapper').width();
            this.configKonva.height = $('.konva-wrapper').width();
            this.image.width = this.configKonva.width;
            this.image.height = this.configKonva.height;
            this.mask.width = this.configKonva.width;
            this.mask.height = this.configKonva.height;
            const obj = this;
            this.files.forEach(function (url) {
                var file = { size: 123, name: "Icon", type: "image/png",xhr:{
                        response:JSON.stringify({image: url})
                    } };
                obj.$refs.myVueDropzone.manuallyAddFile(file, url);
            })
        },

        methods: {
            thumbnail(file, dataUrl){
              th(file, dataUrl);
            },
            fileAdded(file){
                let v = this;
                file.previewElement.addEventListener("click", function() {
                    console.log(file);
                    v.addImage(JSON.parse(file.xhr.response).image);
                });
            },

            rr(file, error, xhr){
              this.removeImage(JSON.parse(file.xhr.response).image);
            },
            updateSItem(data){
                let v = this;
                this.sItem = data;
                this.first = true;
                this.active = 'second';
                var img = new Image();
                img.src = base_url+'/creator_items/'+this.sItem.project_photo;
                img.onload = () => {
                    v.image.image = img;
                };
                var img_mask = new Image();
                img_mask.src = base_url+'/creator_items/'+this.sItem.project_mask;
                img_mask.onload = () => {
                    v.mask.image = img_mask;
                };
                this.clipData = JSON.parse(data.data);
            },
            handleStageMouseDown(e) {

                // clicked on stage - cler selection
                const stage = this.$refs.stage.getNode();
                if (e.target.attrs.type == 'background') {
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

                var element = this.design;
                this.selectedObject = this.design;


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


            change(){
                const stage = this.$refs.stage.getStage();
                const selectedNode = stage.findOne('.design')
                selectedNode.cache();
                selectedNode.brightness(this.filters.brightness);
                selectedNode.contrast(this.filters.kontrast);
                selectedNode.blurRadius(this.filters.blur);
                selectedNode.saturation(this.filters.saturation);
                const transformerNode = this.$refs.transformer.getStage();
                transformerNode.getLayer().batchDraw();
            },

            updateElements(){
                const stage = this.$refs.stage.getStage();
                const { selectedShapeName } = this;
                const selectedNode = stage.findOne('.' + selectedShapeName);
                if(typeof selectedNode != 'undefined'){
                        this.design = selectedNode.attrs;
                        console.log(this.design);
                }

            },
            clipLayer(){
                    if(this.configLayer.clipFunc){
                        this.configLayer.clipFunc = null;
                        return;
                    }
                    var v = this;
                    this.configLayer.clipFunc =  function(ctx){
                        if(v.clipData.type == 'circle'){
                            ctx = drawEllipseByCenter(ctx, v.clipData.x, v.clipData.y, v.clipData.width, v.clipData.height);
                        }else{
                            ctx = roundRect(ctx,v.clipData.x, v.clipData.y, v.clipData.width, v.clipData.height, parseInt(v.clipData.cornerRadius));
                        }
                        ctx.strokeStyle = "#8edbff";
                        ctx.stroke();
                    }
            },

            load() {
                /*const data = localStorage.getItem('storage') || this.reset();
                this.elements = JSON.parse(data);
                this.initImages();*/
            },
            initImages(){
                let v =this;
                var img = new Image();
                img.src = this.design.src;
                img.onload = function () {
                    v.design.image = img;
                };

            },
            addImage(src){
                this.design = {
                    x: 0,
                    y: 0,
                    width: 200,
                    height: 200,
                    name: 'design',
                    src: src,
                    draggable: true,
                    filters: [Konva.Filters.Brighten, Konva.Filters.Blur, Konva.Filters.Contrast, Konva.Filters.HSL],
                    image:new Image(),
                    type: 'image',
                };
                this.initImages();
            },
            uploadImage($event){
                this.loading = true;
                let tmp_img = $event.target.files[0];
                let formData = new FormData();
                formData.append('file',tmp_img);
                axios
                    .post('http://127.0.0.1:8000/upload',formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response=>{
                        this.upload_images.push(response.data.image);
                        this.loading = false;
                    });
            },
            removeImage(image){
                this.loading = true;
                console.log(image);
                if(image == this.design.src) {
                    this.design = {
                        x: 0,
                        y: 0,
                        width: 200,
                        height: 200,
                        name: 'design',
                    };
                    this.selectedObject = null;
                    this.selectedShapeName = '';
                    this.updateTransformer();
                    this.$refs.layer.getNode().draw();

                }
                axios
                    .post('http://127.0.0.1:8000/remove',{'src': image}).then(response=>{
                        this.upload_images.splice(this.upload_images.indexOf(image), 1);
                        this.loading = false;
                    });
            },
            deleteObject(item){
                this.selectedObject = null;
                this.selectedShapeName = '';
                const transformerNode = this.$refs.transformer.getStage();
                transformerNode.detach();

                this.design = {
                    x: 0,
                    y: 0,
                    width: 200,
                    height: 200,
                    name: 'design',
                };

                this.$refs.layer.getNode().draw();
            },
            updateText(){
                this.$refs['text1'][0].getNode().attrs.text = 'fwafwa';
                this.$refs['text1'][0].getNode().batchDraw();
                this.elements[1].text = '';
                this.$refs.layer.getNode().batchDraw();
                this.$refs.transformer.getStage().detach();
            },
            moveUp(obj){
                const temp = obj;
                this.deleteObject(obj);
                if(temp.type == 'text') this.texts.push(temp);
                if(temp.type == 'image') {
                    this.addImage(temp.src);
                    this.initImages();
                }
                this.$refs.stage.getStage().batchDraw();
                this.$refs.layer.getNode().batchDraw();
            }
        },
    }
</script>

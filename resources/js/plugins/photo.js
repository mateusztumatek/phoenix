export default {
    data:() => {
        return{
            dialog: false,
            url:null,
        }
    },
    methods:{
        show(url){
            this.dialog = true;
            this.url = url;
        },
        close(){
            this.dialog = false;
            this.url = null;
        }
    }
}
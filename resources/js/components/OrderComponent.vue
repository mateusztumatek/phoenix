<template>
    <div class="col-md-12 d-flex justify-content-center give-me-space">
        <radial-progress-bar :diameter="600"
                             :completed-steps="step"
                             :total-steps="5"
                             :strokeWidth="20"
                             >
            <p class="white-color">Status: <strong>{{ Order.status }}</strong></p>
            <p class="white-color">Zapłacone: <strong>{{ (Order.is_paid)? 'Tak' : 'Nie' }}</strong></p>

        </radial-progress-bar>

    </div>
</template>

<script>
    import RadialProgressBar from 'vue-radial-progress';

    export default {
        props: ['order'],
        data: function(){
            return {
              Order: this.order,
              step: 1,
                totalSteps: 5,
            }
        },
        methods:{
            getStep(){
                if(this.Order.status == 'nowe') this.step = 1;
                if(this.Order.status == 'oczekiwanie na materiały') this.step = 2;
                if(this.Order.status == 'w realizacji') this.step = 3;
                if(this.Order.status == 'wysłane') this.step = 4;
                if(this.Order.status == 'odebrane') this.step = 5;

            },
            inc(){
                this.step = this.step + 1;
            },
            refresh(hash){
                axios.get(base_url+'/api/order/'+hash).then((response) => {
                    this.Order = response.data;
                });
                this.getStep();
            }
        },
        mounted() {
            this.getStep();
            this.$nextTick(function () {
                window.setInterval(() => {
                    this.refresh(this.Order.hash);
                },3000);
            })
        },
        components:{
            RadialProgressBar
        }
    }
</script>

<template>
    <!-- <div class="alert alert-success  alert-flash fade show" role="alert" v-show="show"> -->
    <div class="alert alert-flash fade show" 
        :class = "'alert-'+level" 
        role="alert" 
        v-show="show" 
        v-text="body">
        
        <!-- <strong>Success!</strong> {{ body }}. -->
    </div>
</template>

<script>
    export default {
       props: ['message'],
       
       data() {
           return {
            //    body: '',
               body: this.message,
               level:'success',
               show: false
           }
       },

       created() {
           /**
            * When we created the component for the first,
            * we flash() message.
            */
           if (this.message) {
            //    Then data is an optional
               this.flash();
            //    this.flash(this.message);

            //    this.body = this.message;
            //    this.show = true;
           }
           /**
            * We gonna listen for a 'flash' event: 
            * anywhere in the system, if we fire this flash event,
            * we wanna pick up from that, we wanna hear about it from this component,
            * and in response, we want to flash() that new message.
            * 
            * Usage: maybe a minute later, some javascript toggler changed, and it fires this 'flash' event,
            * So pick up this, and call flash which body with the new message, and view dynamicly rerenders. 
            */
           window.events.$on(
               'flash', 
            //    message=>this.flash(message));
            data => this.flash(data)) ;
           
       },

       methods: {
        //    flash(message) {
        // flash expect here a object.
        //  but in Ep 71, we passed a string at first, caust it was flashing from sever side.
           flash(data) {
            //    this.body = message;
               if(data) {
                    this.body = data.message;

                    this.level = data.level;
               }
               
               this.show = true;

               this.hide();
           },


           hide() {
               setTimeout(() => {
                   this.show = false;
               }, 3000);
           }
       }
    };
</script>

<style>
    .alert-flash{
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>

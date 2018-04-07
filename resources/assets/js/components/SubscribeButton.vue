<template>
    <!-- refactor, cause written in computed -->
    <!-- <button class="btn" :class="active ? 'btn-primary' : 'btn-default'" @click = "subscribe">Subscribe</button> -->
    <button :class="classes" @click = "subscribe">Subscribe</button>
</template>

<script>
    export default {
        props: ['active'],

        // components: {},

        // Git rid of this session entirely, cause we set props above
        /* data() {
            return {
                // Toogle this in Chrome console: $vm0.active = false;
                active: false
            }
        }, */

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-default'];
            }
        },

        methods: {
            subscribe() {
                /* let requestType = this.active ? 'delete' : 'post';

                axios[requestType](location.pathname + '/subscriptions'); */

                axios[
                    (this.active ? 'delete' : 'post')
                    ](location.pathname + '/subscriptions');
                

                this.active = !this.active;
                
            }
        }
    }
</script>
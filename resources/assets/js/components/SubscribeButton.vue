<template>
    <!-- refactor, cause written in computed -->
    <!-- <button class="btn" :class="active ? 'btn-primary' : 'btn-default'" @click = "subscribe">Subscribe</button> -->
    <button :class="classes" @click = "toggleSubscribe" v-text="toggle"></button>
</template>

<script>
    export default {
        props: ['active'],

        // components: {},

        // Git rid of this session entirely, cause we set props above
        data() {
            return {
                // Toogle this in Chrome console: $vm0.active = false;
                mutableActive: this.active,
                
            }
        },

        computed: {
            classes() {
                return ['btn', this.mutableActive ? 'btn-primary' : 'btn-default'];
            },

            toggle() {
                return this.mutableActive ? 'UnSubscribe' : 'Subscribe'
            }

        },

        methods: {
            toggleSubscribe() {
                /* let requestType = this.active ? 'delete' : 'post';

                axios[requestType](location.pathname + '/subscriptions'); */

                axios[
                    (this.mutableActive ? 'delete' : 'post')
                    ](location.pathname + '/subscriptions');
                
                this.mutableActive ? this.mutableActive = false : this.mutableActive = true
                // this.active = !this.active;              
            }


        }
    }
</script>
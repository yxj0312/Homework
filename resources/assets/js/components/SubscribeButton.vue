<template>
    <!-- refactor, cause written in computed -->
    <!-- <button class="btn" :class="active ? 'btn-primary' : 'btn-default'" @click = "subscribe">Subscribe</button> -->
    <!-- <button :class="classes" @click = "toggleSubscribe" v-text="toggle"></button> -->
    <a href="#" class="ml-2 pl-2 border-l" :class="isActive ? 'font-bold': ''" @click.prevent="subscribe" v-text="isActive ? 'Subscribed' : 'Subscribe'"></a>
</template>

<script>
export default {
  props: ['active'],

  // components: {},

  // Git rid of this session entirely, cause we set props above
  data() {
    return {
      // Toogle this in Chrome console: $vm0.active = false;
      isActive: this.active
    };
  },

  // computed: {
  //     classes() {
  //         return ['btn', this.isActive ? 'btn-primary' : 'btn-default'];
  //     },

  //     toggle() {
  //         return this.isActive ? 'UnSubscribe' : 'Subscribe'
  //     }
  // },

  methods: {
    //     toggleSubscribe() {
    //         /* let requestType = this.active ? 'delete' : 'post';

    //         axios[requestType](location.pathname + '/subscriptions'); */

    //         axios[
    //             (this.isActive ? 'delete' : 'post')
    //             ](location.pathname + '/subscriptions');

    //         this.isActive = !this.isActive;
    //         // this.mutableActive ? this.mutableActive = false : this.mutableActive = true
    //         // this.active = !this.active;
    //     }

    // }
    subscribe() {
      axios[this.isActive ? 'delete' : 'post'](
        location.pathname + '/subscriptions'
      );
      this.isActive = !this.isActive;
      if (this.isActive) {
        flash("Okay, we'll notify you when this thread is updated!");
      }
    }
  }
};
</script>

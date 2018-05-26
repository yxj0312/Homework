<template>
  <button type="submit" :class="classes" @click="toggle">
      <span class="fa fa-heart"></span>
      <span v-text="count" class=""></span>
  </button>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited
            }
        },

        // bind :class with a computed attribute
        computed: {
            classes() {
                return [
                    'btn',
                    this.active ? 'btn-primary': 'btn-default'
                ];
            },

            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggle() {
                this.active ? this.destroy(): this.create();
                /* if (this.isFavorited) {
                    // Unfavorited
                    this.destroy();
                } else {
                    this.create();
                } */
            },

            create() {
                axios.post(this.endpoint);
                this.active = true;
                this.count++;
            },

            destroy() {
                axios.delete(this.endpoint);

                this.active = false;
                this.count--;
            }
        },


    }
</script>

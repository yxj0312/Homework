<template>
<div>
  <div  v-for="(reply,index) in items">
      <!-- Listen to the $emit from child -->
      <reply :data="reply" @deleted="remove(index)"></reply>
      <br>
  </div>
</div>
</template>

<script>
    import Reply from './Reply.vue';
     
    export default {
        props: ['data'],

        components: { Reply },

        data() {
            return {
                items: this.data
            }
        },

        methods: {
            remove(index) {
                /* We gonna hook to the collection, and we gonna remove them entirely.Reply
                Grap one item from that point and remove it from the collection */
                this.items.splice(index, 1);

                this.$emit('removed');

                flash('Reply was deleted!');
            }
        }
    }
</script>
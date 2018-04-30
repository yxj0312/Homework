<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';
    

    export default {
        /* This props name can no longer be used in data() */
        props: ['dataRepliesCount', 'dataLocked'],

        components: { Replies, SubscribeButton },

        data() {
            return {
                repliesCount: this.dataRepliesCount,
                locked: this.dataLocked
            };
        } 
    }
</script>
<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';
    

    export default {
        /* This props name can no longer be used in data() */
        // props: ['dataRepliesCount', 'dataLocked'],
        props: ['thread'],        

        components: { Replies, SubscribeButton },

        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                editing: false
            };
        },

        methods: {
            toggleLock() {
                // ajax
                axios[this.locked ? 'delete' : 'post']('/locked-threads/'+ this.thread.slug);

                this.locked = ! this.locked;
            }
        }
    }
</script>
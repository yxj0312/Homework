<script>
    import Replies from '../components/Replies.vue';

    export default {
        /* This props name can no longer be used in data() */
        props: ['initialRepliesCount'],

        components: { Replies },

        data() {
            return {
                repliesCount: this.initialRepliesCount
            };
        } 
    }
</script>
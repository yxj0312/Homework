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
                editing: false,
                title: this.thread.title,
                body: this.thread.body,
                // use v-model to bind this.
                form: {
                    //  We can move it to created()
                    // title: this.thread.title,
                    // body: this.thread.body
                }
            };
        },

        created() {
            this.resetForm();
        },

        methods: {
            toggleLock() {
                let uri = `/locked-threads/${this.thread.slug}`;
                // ajax
                axios[this.locked ? 'delete' : 'post'](uri);

                this.locked = ! this.locked;
            },

            /* cancel() {
                // Another appoach.
                // this.form = {
                //     title: this.thread.title,
                //     body: this.thread.body
                // };
            }, */

            update() {
                let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

                axios.patch(uri, this.form).then(()=>{
                    this.editing = false;
                    this.title = this.form.title;
                    this.body = this.form.body;
                    flash('Your thread has been updated.');
                });
            },

            resetForm() {
                this.form = {
                    title: this.thread.title,
                    body: this.thread.body,
                };

                this.editing = false;
            }
        }
    }
</script>
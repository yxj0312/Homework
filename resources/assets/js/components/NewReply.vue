<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body" 
                            id="body" 
                            class="form-control" 
                            placeholder="Have something to say?" 
                            cols="30" 
                            rows="5"
                            required 
                            v-model="body"></textarea>
            </div>
            <br>
            <button type="submit" 
                    class="btn btn-default"
                    @click="addReply">Post</button>
        </div>
        <p class="text-center" v-else>
            Please <a href="/login">sign in</a> to participate in this discussion.
        </p>
    </div>
</template>

<script>
    export default {
        /* No longer need to accept that */
        // props: ['endpoint'],

        // components: {},

        data() {
            return {
                body: ''
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        methods: {
            addReply() {
                // axios.post(this.endpoint, { body: this.body })
                axios.post(location.pathname + '/replies', { body: this.body })
                    .catch(error=>{
                        flash(error.response.data, 'danger');
                    })
                    // .then(response => {
                    .then(({data}) => {
                        this.body = '';
                        
                        flash('Your Reply has been posted.');

                        // this.$emit('created', response.data);
                        this.$emit('created', data);
                    });
            }
        }
    }
</script>
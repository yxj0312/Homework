<template>
    <div :id="'reply-'+id" class="card">
    
        <div class="card-header" :class="isBest ? 'bg-success': '' ">
    
            <div class="level">
    
                <div class="flex">
    
                    <a :href="'/profiles/'+data.owner.name" 
                        v-text="data.owner.name">
    
                    </a> said <span v-text="ago"></span>
    
                </div>
    
                <!-- @if (Auth::check()) -->
    
                <div v-if="signedIn">
    
                    <favorite :reply="data"></favorite>
    
                </div>
    
                <!-- @endif -->
    
            </div>
    
        </div>
    
    
    
        <div class="card-body">
    
            <div v-if="editing">

                <!-- Form submit allow u to hit return key to submit the form -->
                <form @submit="update"  @keydown.enter.prevent @keyup.enter.prevent="update">

                    <div class="form-group">
        
                        <textarea class="form-control" v-model="body" required></textarea>
        
                    </div>
                    
                    <button class="btn btn-xs btn-primary">Update</button>
        
                    <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>

                </form>

            </div>
    
            <div v-else v-html="body"></div>
    
        </div>
    
    
    
        <!-- {{--  @can('update', Reply::class)  --}}
    
            @can('update', $reply)     -->
    
        <div class="card-footer level">
            
            <div v-if="authorize('updateReply', reply)">

                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
        
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>

            </div>

            <button class="btn btn-xs btn-default ml-a" @click="markBestReply" v-show="! isBest">Best Reply?</button>
    
        </div>
    
        <!-- @endcan -->
    
    </div>
    
    <!-- </br> -->
</template>

<script>
    import Favorite from './Favorite.vue';
    
    import moment from 'moment';
    
    
    
    export default {
    
        props: ['data'],
    
    
    
        components: {
    
            Favorite
    
        },
    
    
    
        data() {
    
            return {
    
                editing: false,
    
                id: this.data.id,
    
                body: this.data.body,

                isBest: this.data.isBest,

                reply: this.data,

                // Tell Vue to track this, or u can use Vuex
                // thread: window.thread
            };
    
        },
    
    
    
        computed: {
            // Ep 81
            /* isBest() {

                return this.thread.best_reply_id === this.id;
            
            }, */
    
            ago() {
    
                return moment(this.data.created_at).fromNow() + '...';
    
            },
    
    
            // Refactor to global : Vue.prototype.signedIn = window.App.signedIn in app.js;
            /* signedIn() {
    
                return window.App.signedIn;
    
            }, */
    
    
            /** Refactor to authorizations.js */
            // canUpdate() {
    
            //     /* put an authorize method in _bootstrap.js */
    
            //     return this.authorize(user => this.data.user_id === user.id);
    
            //     /* As admin situation, you have to update everything, no good */
    
            //     // return this.data.user_id == window.App.user.id;
    
            // }
    
        },

        created() {
            // Listen event here.
            window.events.$on('best-reply-selected', id => {
                // Update isBest property here.
                this.isBest = (id === this.id) 
            });
        },
    
    
    
        methods: {
    
            update() {
    
                axios.patch(
    
                        '/replies/' + this.data.id, {
    
                            body: this.body
    
                        })
    
                    .catch(error => {
    
                        flash(error.response.data, 'danger');
    
                    });
    
    
    
                this.editing = false;
    
    
    
                flash('Updated');
    
            },
    
    
    
            destroy() {
    
                axios.delete('/replies/' + this.data.id);
    
    
    
                /* Here is how child communicate with the parent component 
    
                  Hey, I've been deleted!
    
                */
    
                this.$emit('deleted', this.data.id);
    
    
    
                // $(this.$el).fadeOut(300, ()=>{
    
                //     flash('Your reply has been deleted.')
    
                // });
    
            },

            markBestReply() {
                // this.isBest = true;

                axios.post('/replies/' + this.id + '/best');

                //Fire a global event, pass through the id.
                window.events.$emit('best-reply-selected', this.id);

                // this.thread.best_reply_id = this.id;
            }
    
        }
    
    }
</script>
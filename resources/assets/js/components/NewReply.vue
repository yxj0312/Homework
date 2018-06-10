<template>
    <!-- <div class="new-reply"> -->
    <div class="py-6 ml-10">
        <div v-if="! signedIn">
            <p class="text-center text-sm text-grey-dark">
                Please <a href="/login" @click.prevent="$modal.show('login')" class="text-blue link">sign in</a> to participate in this
                discussion.
            </p>
        </div>

        <div v-else-if="! confirmed">
            To participate in this thread, please check your email and confirm your account.
        </div>

        <div v-else>
            <div class="mb-3">
                <wysiwyg name="body"
                    v-model="body"
                    placeholder="Have something to say?"
                ></wysiwyg>
                <!-- <textarea name="body"
                            id="body"
                            class="form-control"
                            placeholder="Have something to say?"
                            cols="30"
                            rows="5"
                            required
                            v-model="body"></textarea> -->
            </div>
            <br>
            <button type="submit"
                    class="btn is-green"
                    @click="addReply">Post</button>
        </div>
    </div>
</template>

<script>
// import 'jquery';
import 'jquery.caret';
import 'at.js';

export default {
  /* No longer need to accept that */
  // props: ['endpoint'],

  // components: {},

  data() {
    return {
      body: ''
      // completed: false
    };
  },

  // Refactored
  /* computed: {
            signedIn() {
                return window.App.signedIn;
            }
        }, */

  computed: {
    confirmed() {
      return window.App.user.confirmed;
    }
  },

  mounted() {
    $('#body').atwho({
      at: '@',
      delay: 750,
      callbacks: {
        remoteFilter: function(query, callback) {
          $.getJSON('/api/users', { name: query }, function(usernames) {
            callback(usernames);
          });
        }
      }
    });
  },

  methods: {
    addReply() {
      // axios.post(this.endpoint, { body: this.body })
      axios
        .post(location.pathname + '/replies', { body: this.body })
        .catch(error => {
          flash(error.response.data, 'danger');
        })
        // .then(response => {
        .then(({ data }) => {
          this.body = '';

          // this.completed = true;

          flash('Your Reply has been posted.');
          /** One Approach to clear the trix editor */
          // this.$refs.trix.$refs.trix.value='';

          // this.$emit('created', response.data);
          this.$emit('created', data);
        });
    }

    // resetState() {
    //     this.completed = false;
    // }
  }
};
</script>

<style scoped>
.new-reply {
  /* padding: 15px;
  background-color: #fff;
  border: 1px solid #e3e3e3; */
  background-color: #fff;
}
</style>

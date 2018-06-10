<template>
    <!-- <div>
        <div  v-for="(reply,index) in items" :key="reply.id"> -->
            <!-- Listen to the $emit from child -->
            <!-- <reply :reply="reply" @deleted="remove(index)"></reply>
            <br>
        </div> -->

        <!-- <paginator :dataSet="dataSet" @changed="fetch"></paginator> -->

        <!-- Move endpoint to newReply -->
        <!-- <new-reply :endpoint="endpoint" @created="add"></new-reply> -->
        <!-- <p v-if="$parent.locked">
            This thread has been locked. No more replies are allowed.
        </p> -->

        <!-- <new-reply @created="add" v-else></new-reply> -->
    <!-- </div> -->
    <div class="flex" style="margin-left: 56px">
        <div>
            <div v-for="(reply, index) in items" :key="reply.id">
                <reply :reply="reply" @deleted="remove(index)"></reply>
            </div>

            <paginator :dataSet="dataSet" @changed="fetch"></paginator>

            <p v-if="$parent.locked" class="mt-4 text-sm text-grey-dark text-center">
                This thread has been locked. No more replies are allowed.
            </p>

            <new-reply @created="add" v-else></new-reply>
        </div>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import collection from '../mixins/collection'

    export default {
        // props: ['data'],
        components: { Reply, NewReply },

        mixins: [collection],

        data() {
            return {
                dataSet: false,
                // items: this.data,
                /* items are now store in the mixins, and this two data() object will be merged by Vue. we can get rid of that. */
                // items: [],
                // endpoint: location.pathname + '/replies'
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page))
                    .then(this.refresh);
            },

            // Can not direct set default 1, because when u directly to url address like ?page=2. it will still go
            // to page 1
            // url(page = 1) {
            url(page) {
                if(!page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }
                // return location.pathname + '/replies?page=' + page;

                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data}) {
                // console.log(data);
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            },
        }
    }
</script>

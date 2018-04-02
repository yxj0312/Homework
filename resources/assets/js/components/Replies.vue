<template>
    <div>
        <div  v-for="(reply,index) in items" :key="reply.id">
            <!-- Listen to the $emit from child -->
            <reply :data="reply" @deleted="remove(index)"></reply>
            <br>
        </div>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
     
    export default {
        props: ['data'],

        components: { Reply, NewReply },

        data() {
            return {
                dataSet: false,
                // items: this.data,
                items: [],
                endpoint: location.pathname + '/replies'
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch() {
                axios.get(this.url())
                    .then(this.refresh);
            },

            url() {
                return location.pathname + '/replies';
            },

            refresh({data}) {
                // console.log(data);
                this.dataSet = data;
                this.items = data.data;
            },

            add(reply){
                this.items.push(reply);

                this.$emit('added');
            },

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
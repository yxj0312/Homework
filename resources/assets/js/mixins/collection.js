export default {
    data() {
        return {
            items: []
        };
    },
    
    methods: {
        add(item){
            this.items.push(item);

            this.$emit('added');
        },

        remove(index) {
            /* We gonna hook to the collection, and we gonna remove them entirely.Reply
            Grap one item from that point and remove it from the collection */
            this.items.splice(index, 1);

            this.$emit('removed');
        }        
    }
}


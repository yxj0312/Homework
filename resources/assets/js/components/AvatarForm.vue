<template>
    <div>
        <h1 v-text="user.name"></h1>

        
        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <input type="file" name="avatar" accept="image/*" @change="onChange">
             
            <button type="submit" class="btn btn-primary">Add Avatar</button>
        </form>
        

        <img :src="avatar" width="50"  height="50">
    </div>
</template>

<script>
    export default {
        props: ['user'],

        components: {},

        data() {
            return {
                avatar: ''
            }
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            onChange(e) {
                if (!e.target.files.length) return;

                let file = e.target.files[0];

                let reader = new FileReader;

                reader.readAsDataURL(file);

                reader.onload = e => {
                    this.avatar = e.target.result;
                };
            }
        }
    }
</script>
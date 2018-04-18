<template>
    <div>
        <div class="level">
            <img :src="avatar" width="50"  height="50" class="mr-1">
            
            <h1 v-text="user.name"></h1>
        </div>

        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <!-- name and class will auto merged to input -->
            <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
            <!-- <button type="submit" class="btn btn-primary">Add Avatar</button> -->
        </form>
        

    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],

        components: { ImageUpload },

        data() {
            return {
                avatar: this.user.avatar_path
            }
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            // Accept the payload of event: avatar = { src, file} in ImageUpload
            onLoad(avatar) {
                this.avatar = avatar.src;
                // Persist to the server
                // avatar is the file itself, not data url!
                // Jeff, why u name them in the same..
                this.persist(avatar.file);
            },

            persist(avatar) {
                // Simulate multipart/form-data (FormData: JS API), 
                // we should give the actual file, 
                // not as dataURL: this.avatar(string)
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'));
            }
        }
    }
</script> 
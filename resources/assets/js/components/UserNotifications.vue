<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <span class="fa fa-bell"></span>
            <!-- <span class="caret"></span> -->
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
             <a class="dropdown-item"
                v-for="notification in notifications" :key="notification.id"
                :href="notification.data.link"
                v-text="notification.data.message"
                @click.prevent="markAsRead(notification)"
             >
                Foobar
             </a>
        </div>
    </li>
</template>

<script>
    export default {
        props: [],

        components: {},

        data() {
            return {
               /* $vm0.notifications = ['one', 'two', 'three'] */
               notifications: false
            }
        },

        created() {
            /* axios.get('/profiles/' + window.App.user.name + '/notifications')
            // U have a response, we are going to save them
                .then(response => this.notifications = response.data); */
            this.fetchNotifications();
        },

        methods: {
            fetchNotifications() {
                axios.get('/profiles/' + window.App.user.name + '/notifications')
                 .then(response => this.notifications = response.data);
            },

            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
                .then(response => {
                    this.fetchNotifications();
                    document.location.replace(response.data.link);
                });
            }
        }
    }
</script>

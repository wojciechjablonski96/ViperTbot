<template>
    <widget wid-id="58974"
            color="purple"
            :fullscreen="true">
        <div slot="title">Twitch Chat</div>
        <div slot="icon">
            <i class="fa fa-comments txt-color-white"></i>
        </div>

        <div slot="toolbars">
            <!-- all your toolbars can go here -->
        </div>
        <!-- end toolbars -->

        <div slot="body">
            <!-- MAIN CONTAINER -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <iframe v-if="TwitchURL"
                            frameborder="0"
                            scrolling="no"
                            id="chat_embed"
                            :src="TwitchURL"
                            height="410"
                            width="100%"
                    ></iframe>
                </div>
            </div>
        </div>
        <!-- end body -->
    </widget>
</template>
<style>

</style>
<script>
    import Widget from 'Components/Widget/Widget.vue';

    export default{
        props: {

        },
        components:{
            Widget
        },
        methods: {

        },
        computed: {
            user() {
                return this.$store.getters.getUser;
            },

            /**
             * @return {string}
             */
            TwitchURL() {

                if(this.user.username !== null) {
                    return `http://www.twitch.tv/${this.user.username}/chat`
                }

                return null
            },
        }
    }
</script>
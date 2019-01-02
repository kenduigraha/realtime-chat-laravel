<template>
    <div>
        <div v-for="(data, index) in messageFront" v-bind:key="index">
            <h1 class="text-center">{{data.message}}</h1>
        </div>
        <div>
            <beautiful-chat
            :participants="participants"
            :titleImageUrl="titleImageUrl"
            :onMessageWasSent="onMessageWasSent"
            :messageList="messageList"
            :newMessagesCount="newMessagesCount"
            :isOpen="isChatOpen"
            :close="closeChat"
            :open="openChat"
            :showEmoji="true"
            :showFile="true"
            :showTypingIndicator="showTypingIndicator"
            :colors="colors"
            :alwaysScrollToBottom="alwaysScrollToBottom"
            :messageStyling="messageStyling" />
        </div>
    </div>
</template>

<script>
    export default {
        props: ['homeMessage'], 
        data() {
            return {
                messageFront: [],
                participants: [
                    {
                    id: 'user1',
                    name: 'Matteo',
                    imageUrl: 'https://avatars3.githubusercontent.com/u/1915989?s=230&v=4'
                    },
                    {
                    id: 'user2',
                    name: 'Support',
                    imageUrl: 'https://avatars3.githubusercontent.com/u/37018832?s=200&v=4'
                    }
                ], // the list of all the participant of the conversation. `name` is the user name, `id` is used to establish the author of a message, `imageUrl` is supposed to be the user avatar.
                titleImageUrl: 'https://a.slack-edge.com/66f9/img/avatars-teams/ava_0001-34.png',
                messageList: [
                    { type: 'text', author: `me`, data: { text: `Say yes!` } },
                    { type: 'text', author: `user1`, data: { text: `No.` } }
                ], // the list of the messages to show, can be paginated and adjusted dynamically
                newMessagesCount: 0,
                isChatOpen: false, // to determine whether the chat window should be open or closed
                showTypingIndicator: '', // when set to a value matching the participant.id it shows the typing indicator for the specific user
                colors: {
                    header: {
                    bg: '#4e8cff',
                    text: '#ffffff'
                    },
                    launcher: {
                    bg: '#4e8cff'
                    },
                    messageList: {
                    bg: '#ffffff'
                    },
                    sentMessage: {
                    bg: '#4e8cff',
                    text: '#ffffff'
                    },
                    receivedMessage: {
                    bg: '#eaeaea',
                    text: '#222222'
                    },
                    userInput: {
                    bg: '#f4f7f9',
                    text: '#565867'
                    }
                }, // specifies the color scheme for the component
                alwaysScrollToBottom: false, // when set to true always scrolls the chat to the bottom when new events are in (new message, user starts typing...)
                messageStyling: true 
            }
        },
        mounted() {
            axios.get(`/ajax/get-message`)
                     .catch(function (error) {
                            return error.response;
                      }).then((response) => {
                            console.log('response :')
                            console.log(response)
                            if (response.status == 200) {        
                                this.messageFront = response.data;
                            }
                        });
            axios.get(`/ajax/new-user-join-chat`)
                     .catch(function (error) {
                            return error.response;
                      }).then((response) => {
                            console.log('response :')
                            console.log(response)
                            if (response.status == 200) {        
                                // this.messageFront = response.data;
                            }
                        });
                        
        },
        created() { // called after the component is created
            window.Echo.channel('public-message')
                .listen('PublicMessageSent', payload => {
                    // console.log(payload)
                    this.placeMessage(payload) 
                });
            window.Echo.channel('public-new-user-join')
                .listen('NewUserJoinChat', payload => {
                    console.log(payload)
                    // this.placeMessage(payload) 
                });
        },
        methods: {
            placeMessage(payload) {
                console.log(this.messageFront)
                // this.messageFront = message;
                this.messageFront.push(payload)
            },
            sendMessage (text) {
            if (text.length > 0) {
                this.newMessagesCount = this.isChatOpen ? this.newMessagesCount : this.newMessagesCount + 1
                this.onMessageWasSent({ author: 'support', type: 'text', data: { text } })
            }
            },
            onMessageWasSent (message) {
            // called when the user sends a message
            this.messageList = [ ...this.messageList, message ]
            },
            openChat () {
                console.log('asdfsaf')
            // called when the user clicks on the fab button to open the chat
            this.isChatOpen = true
            this.newMessagesCount = 0
            },
            closeChat () {
            // called when the user clicks on the botton to close the chat
            this.isChatOpen = false
            }
        }        
    };
</script>

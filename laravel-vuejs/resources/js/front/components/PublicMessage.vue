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
            :messageStyling="messageStyling"
            v-model.lazy="search"/>
        </div>
        <notifications
            group="foo"
            position="top left"/>
    </div>
</template>


<script>
    export default {
        props: ['homeMessage'], 
        data() {
            return {
                messageFront: [],
                currentUser: {},
                participants: [
                    {
                    id: 'bot',
                    name: 'BOT',
                    imageUrl: 'https://avatars3.githubusercontent.com/u/1915989?s=230&v=4'
                    }
                ], // the list of all the participant of the conversation. `name` is the user name, `id` is used to establish the author of a message, `imageUrl` is supposed to be the user avatar.
                titleImageUrl: 'https://a.slack-edge.com/66f9/img/avatars-teams/ava_0001-34.png',
                messageList: [
                    { type: 'text', author: `BOT`, data: { text: `Welcome to chatbox. I'm your personal assistant.` } }
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
                alwaysScrollToBottom: true, // when set to true always scrolls the chat to the bottom when new events are in (new message, user starts typing...)
                messageStyling: true,
                search: ''
            }
        },
        mounted() {
            // axios.get(`/ajax/get-message`)
            //          .catch(function (error) {
            //                 return error.response;
            //           }).then((response) => {
            //                 console.log('response :')
            //                 console.log(response)
            //                 if (response.status == 200) {        
            //                     this.messageFront = response.data;
            //                 }
            //             });
                        
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
                    let { user } = payload;
                    // user = JSON.parse(user);
                    console.log(user)
                    this.participants.push({
                        id: String(user.id),
                        name: user.username,
                        imageUrl: 'https://avatars3.githubusercontent.com/u/37018832?s=200&v=4'
                    });

                    this.$notify({
                            group: 'foo',
                            title: `${user.username} is join to chatroom`,
                            text: `Hello! ${user.username} is join to chatroom`,
                            type: 'success',
                            duration: 6000
                        });
                });
            window.Echo.channel('public-new-user-leave')
                .listen('NewUserLeaveChat', payload => {
                    console.log(payload)
                    let { user } = payload;
                    // user = JSON.parse(user);
                    console.log(user)

                    this.$notify({
                            group: 'foo',
                            title: `${user} is exiting chatroom`,
                            text: `Hello! ${user} is exitng chatroom`,
                            type: 'info',
                            duration: 6000
                        });
                });
        },
        methods: {
            placeMessage(payload) {
                const { message, user } = payload;
                console.log(user)
                console.log(message)
                // this.messageFront = message;
                // this.messageFront.push(payload)
                this.messageList.push({ 
                    type: 'text', // TODO
                    author: user.id === this.currentUser.id ? "me" : this.currentUser.username, // TODO
                    data: { text: message }
                });
            },
            sendMessage (text) {
                if (text.length > 0) {
                    console.log('send message')
                    // TODO : refactor
                    this.newMessagesCount = this.isChatOpen ? this.newMessagesCount : this.newMessagesCount + 1
                    axios.post(`/ajax//send-message/`, { message: text })
                        .catch(function (error) {
                                return error.response;
                        }).then((response) => {
                                console.log('response :')
                                console.log(response)
                                if (response.status == 200) {
                                    // todo type text or files
                                    this.onMessageWasSent({ author: this.currentUser.username, type: 'text', data: { text } })
                                }
                        });

                }
            },
            onMessageWasSent (message) {
                console.log('senttt')
                // called when the user sends a message
                // TODO : if files
                axios.post(`/ajax/send-message/`, { message: message.data.text })
                        .catch(function (error) {
                                return error.response;
                        }).then((response) => {
                                console.log('response :')
                                console.log(response)
                                if (response.status == 200) {
                                    // this.messageList = [ ...this.messageList, message ]
                                }
                        });
            },
            openChat () {
                axios.get(`/ajax/new-user-join-chat`)
                     .catch(function (error) {
                            return error.response;
                      }).then((response) => {
                            console.log('response :')
                            console.log(response)
                            if (response.status == 200) {
                                const user = response.data;
                                this.currentUser = user;
                            }
                    });
                    axios.get(`/ajax/get-message`)
                        .catch(function (error) {
                                return error.response;
                        }).then((response) => {
                                console.log('response :')
                                console.log(response)
                                if (response.status == 200) {
                                    const messages = response.data;
                                    messages.map(msg => {
                                        this.messageList.push({ 
                                            type: 'text', // TODO
                                            author: msg.user_id === this.currentUser.id ? "me" : this.currentUser.username, // TODO
                                            data: { text: msg.message }
                                        });
                                    });
                                }
                        });
                // called when the user clicks on the fab button to open the chat
                this.isChatOpen = true
                this.newMessagesCount = 0
            },
            closeChat () {
                // TODO, variable participant handling when close chatbox
                // called when the user clicks on the botton to close the chat
                this.isChatOpen = false;
                axios.get(`/ajax/new-user-leave-chat`)
                     .catch(function (error) {
                            return error.response;
                      }).then((response) => {
                            console.log('response :')
                            console.log(response)
                            if (response.status == 200) {
                                const user = response.data;
                                // this.currentUser = user;
                            }
                    });
            }
        }   
    };
</script>

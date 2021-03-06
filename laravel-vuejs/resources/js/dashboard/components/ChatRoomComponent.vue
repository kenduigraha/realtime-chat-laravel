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
            :showEmoji="false"
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
        created() { // called after the component is created
            window.Echo.channel('public-message')
                .listen('PublicMessageSent', payload => {
                    this.placeMessage(payload) 
                });
            window.Echo.channel('public-new-user-join')
                .listen('NewUserJoinChat', payload => {
                    let { user } = payload;
                    this.participants.push({
                        id: String(user.id),
                        name: user.name,
                        imageUrl: 'https://avatars3.githubusercontent.com/u/37018832?s=200&v=4'
                    });

                    this.$notify({
                            group: 'foo',
                            title: `${user.name} is join to chatroom`,
                            text: `Hello! ${user.name} is join to chatroom`,
                            type: 'success',
                            duration: 6000
                        });
                });
            window.Echo.channel('public-new-user-leave')
                .listen('NewUserLeaveChat', payload => {
                    let { user } = payload;

                    this.participants = this.participants.filter(usr => String(usr.id) != String(user.id));
                    this.$notify({
                            group: 'foo',
                            title: `${user.name} is exiting chatroom`,
                            text: `Hello! ${user.name} is exitng chatroom`,
                            type: 'info',
                            duration: 6000
                        });
                });
        },
        methods: {
            placeMessage(payload) {
                const { message, user } = payload;
                this.messageList.push({ 
                    type: !message.files ? 'text' : 'file', 
                    author: user.id === this.currentUser.id ? "me" : this.currentUser.name, // TODO
                    data: {
                        text: message.text,
                        file: {
                            name: !message.files ? '' : message.files.title,
                            url: !message.files ? '' : message.files.file
                        }
                    }
                });
            },
            onMessageWasSent (message) {
                let formData = new FormData();
                if (message.type === 'file') {
                    formData.append('file', message.data.file);
                }
                if (message.data.text) {
                    formData.append('text', message.data.text);
                }

                axios.post(`/ajax/send-message/`,
                    formData,
                    {
                        headers: {
                            'content-type': 'multipart/form-data'
                        }
                    }
                )
                        .catch(function (error) {
                            console.error(error)
                                return error.response;
                        }).then((response) => {
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
                            if (response.status == 200) {
                                const user = response.data;
                                this.currentUser = user;
                            }
                    });
                    axios.get(`/ajax/get-message`)
                        .catch(function (error) {
                                return error.response;
                        }).then((response) => {
                                if (response.status == 200) {
                                    const messages = response.data;
                                    messages.map(msg => {
                                        this.messageList.push({
                                            type: !msg.files ? 'text' : 'file', 
                                            author: msg.user_id === this.currentUser.id ? "me" : this.currentUser.name, // TODO
                                            data: {
                                                text: msg.text,
                                                file: {
                                                    name: !msg.files ? '' : msg.files.title,
                                                    url: !msg.files ? '' : msg.files.file,
                                                }
                                            }
                                        });
                                    });
                                }
                        });
                // called when the user clicks on the fab button to open the chat
                this.isChatOpen = true
                this.newMessagesCount = 0
            },
            closeChat () {
                // called when the user clicks on the botton to close the chat
                this.isChatOpen = false;
                axios.get(`/ajax/new-user-leave-chat`)
                     .catch(function (error) {
                            return error.response;
                      }).then((response) => {
                            if (response.status == 200) {
                                const user = response.data;
                            }
                    });
            }
        }   
    };
</script>

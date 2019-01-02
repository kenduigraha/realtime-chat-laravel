<template>
    <div>
        <div class="form-row">
            <div class="col">
                <label for="transfer-username">Home Message</label>
                <input type="text" v-model="newMessage" @keydown.enter="save"
                class="form-control" v-bind:class="{'is-invalid': invalidRes}"
                placeholder="Write here your home message..." autofocus>
                <div :class="{'invalid-feedback': invalidRes}" v-html="feedbackBox"></div>
            </div>
        </div>
         <button type="button" class="btn btn-primary mt-4" @click="save">
             <strong>Send!</strong>
         </button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                newMessage: '',
                feedbackBox: '',
                invalidRes: false,
            }
        },
        methods: {
            save() {
                    axios.post(`/ajax/send-message`, {message: this.newMessage })
                     .catch(function (error) {
                            return error.response;
                      }).then(this.setResponse);

                    this.newMessage = '';
            },
            setResponse(response) {
                console.log('response :')
                console.log(response)
                if (response.status != 200) {
                    this.invalidRes = true;
                    this.feedbackBox = response.data.errors.message[0];                    
                } else {
                    this.invalidRes = false;
                    this.feedbackBox = "Your message was sent!";
                }
            }
        }
    };
</script>

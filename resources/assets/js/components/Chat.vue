<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <textarea rows="10" readonly class="form-control">
                    {{ messages.join('\n') }}
                </textarea>
                <hr>
                <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            window.Echo.channel('chat')
                .listen('ItemAdded', ({message}) => { 
                console.log(message);
                    this.messages.push(message);
                });
        },

        data() {
            return {
                messages: [],
                textMessage: ''
            }
        },

        methods: {
            sendMessage(){
                axios.post('/messages', {body: this.textMessage});
                this.messages.push(this.textMessage);
                this.textMessage = '';
            }  
        }
    }
</script>

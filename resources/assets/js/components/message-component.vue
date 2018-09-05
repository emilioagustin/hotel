<template>
    <!--Mensajes-->
    <div class="message__container" v-show="currentMessage.text != ''">
        <span class="message" v-bind:class="currentMessage.type">
            {{ currentMessage.text }}
        </span>
        <div class="close" @click="reset()">[X]</div>
    </div>
    <!--/Mensajes -->
</template>

<script>
    import router from '../router'

    export default {
        name: 'MessageComponent',
        props: ['message'], 
        data: function () {
            return {
                currentMessage: this.message,
            }
        }, 
        methods: {
            reset() 
            {
                this.currentMessage = { type: '', text: '' }
            }, 
            set(status, text, redirect = '', timeout = 1000)
            {
                this.currentMessage.type = status
                this.currentMessage.text = text
                setTimeout(() => {
                    this.currentMessage = { type: '', text: '' }
                    if (redirect != '')
                    {
                        router.push('/')
                    }
                }, timeout)
            }
        }
    }
</script>
<template>
    <div>
        <message-component ref="messageHandler" v-bind:message="message"></message-component>

        <!--titulo y subtitulo -->
        <section class="heading">
            <div class="titulo">
                <h1>Galería</h1>
                <br />      
            </div>
            <div class="action">
                <button @click="home()">VOLVER</button>
            </div>
        </section>
        <!--/titulo y subtitulo -->

        <!--Grid-->
        <div class="grid">
            <div class="thumbs">
                <ul>
                    <li :key="image.id" v-for="image in gallery">
                        <a :href="`/img/${id}/${image.image_path}`" target="_blank">
                            <img class="thumb" :src="`/img/${id}/${image.image_path}`" alt="Thumb" />
                            <a href="javascript:void(0);" @click="remove(image.id)">
                                <div class="trash"></div>
                            </a>
                        </a>
                    </li>
                </ul>
            </div>
            <br />
            <multiple-file-uploader headerMessage="" dropAreaPrimaryMessage="DROP FILES HERE" dropAreaSecondaryMessage="" 
                v-bind:postURL="`/content/images/${this.id}`" successMessagePath="success" errorMessagePath="error" fileNameMessage="" 
                fileSizeMessage="" totalFileMessage="" totalUploadSizeMessage="" removeFileMessage="" 
                uploadButtonMessage="Upload" cancelButtonMessage="Cancel" 
                v-bind:showHttpMessages=false @upload-success="onSuccess" @upload-error="onError"></multiple-file-uploader>
        </div>
        <!--/Grid -->
    </div>
</template>

<script>
    /* eslint-disable */
    import router from '../router'
    import axios from 'axios'
    import MultipleFileUploader from './multiple-file-uploader-component'
    import MessageComponent from './message-component'

    export default {
        components: {
            MultipleFileUploader, MessageComponent
        }, 
        name: 'GalleryComponent',
        props: ['id'],
        data() {
            return {
                gallery: [],
                message: { type: '', text: '' }
            }
        }, 
        mounted() {
            axios
            .get(`/content/images/${this.id}`)
            .then(response => this.gallery = response.data)
            .catch(error => {
                this.getMessageHandler().set('error', error.response.data.message, '/', 2000)   
            })
        },
        methods: {
            getMessageHandler()
            {
                return this.$refs.messageHandler
            }, 
            onSuccess: function(response)
            {
                this.gallery = response.data
                this.getMessageHandler().set('success', 'Imagen/es subida/s satisfactoriamente')
            },
            onError: function(response)
            {
                this.getMessageHandler().set('error', 'Ocurrió un error')
            }, 
            home()
            {
                router.push('/')    
            },
            remove(id)
            {
                if (confirm("¿Estás segur@ de que se deseas borrar el elemento?")) 
                {
                    const messageHandler = this.getMessageHandler()
                    axios
                    .delete(`/content/images/${id}`)
                    .then(response => {
                        this.gallery = response.data
                        messageHandler.set('success', 'Elemento borrado satisfactoriamente')
                    })
                    .catch((error) => {
                        messageHandler.set('error', error.response.data.message)
                    })    
                }
            }, 
        }
    }
</script>

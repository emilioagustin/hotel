<template>
    <div>
        <message-component ref="messageHandler" v-bind:message="message"></message-component>

        <!--titulo y subtitulo -->
        <section class="heading">
            <div class="titulo">
                <h1>Nuevo elemento</h1>      
            </div>
            <div class="action">
                <button @click="save()">GUARDAR</button>
            </div>
        </section>
        <!--/titulo y subtitulo -->

        <!--Grid-->
        <div class="grid">
            <div class="form">
                <label>
                    Estado
                </label>
                <span class="custom__dropdown">
                    <select v-model="content.status">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </span>
                <br /><br />
                <label>
                    Nombre
                </label>
                <input type="text" placeholder="Introduzca nombre" v-model="content.name" />
                <div class="languages">
                    <ul>
                        <li>
                            <a v-bind:class="this.current_language == 'ca' ? 'selected' : ''" href="javascript:void(0)" @click="selectLanguage('ca')">Catalá</a>
                        </li>
                        <li>
                            <a v-bind:class="this.current_language == 'es' ? 'selected' : ''" href="javascript:void(0)" @click="selectLanguage('es')">Castellano</a>
                        </li>
                        <li>
                            <a v-bind:class="this.current_language == 'en' ? 'selected' : ''" href="javascript:void(0)" @click="selectLanguage('en')">Inglés</a>
                        </li>
                        <li>
                            <a v-bind:class="this.current_language == 'fr' ? 'selected' : ''" href="javascript:void(0)" @click="selectLanguage('fr')">Francés</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-container" id="cat">
                    <label>
                        Título
                    </label>
                    <input type="text" placeholder="Introduzca título" v-model="content.languages[current_language].title" />
                    <br /><br />
                    <label>
                        Descripción
                    </label>
                    <editor v-model="content.languages[current_language].body"></editor>
                </div>
            </div>
        </div>
        <!--/Grid -->
    </div>
</template>

<script>
    /* eslint-disable */
    import axios from 'axios'
    import Editor from '@tinymce/tinymce-vue'
    import MessageComponent from './message-component'

    export default {
        components: {
            Editor, MessageComponent
        },
        name: 'ViewItemComponent',
        props: ['id'],
        data() {
            return {
                current_language: 'ca', 
                content: {
                    name: '',
                    status: '1', 
                    languages: {
                        'ca': 
                        {
                            id: 0, 
                            language: 'ca', 
                            title: '', 
                            body: ''
                        }, 
                        'es':
                        {
                            id: 0, 
                            language: 'es',
                            title: '', 
                            body: ''
                        }, 
                        'en': 
                        {
                            id: 0, 
                            language: 'en',
                            title: '', 
                            body: ''
                        }, 
                        'fr': 
                        {
                            id: 0, 
                            language: 'fr',
                            title: '', 
                            body: ''
                        }
                    }
                },
                message: { type: '', text: '' } 
            }
        }, 
        mounted() {
            if (this.id) 
            {
                axios
                .get(`/content/${this.id}`)
                .then(response => this.content = response.data)
                .catch(error => {
                    this.getMessageHandler().set('error', error.response.data.message, '/', 2000)   
                })
            }
        },
        methods: {
            getMessageHandler()
            {
                return this.$refs.messageHandler
            }, 
            save()
            {
                const messageHandler = this.getMessageHandler()
                if (this.id) 
                {
                    axios
                    .put(`/content/update/${this.id}`, this.content)
                    .then(response => {
                        messageHandler.set('success', 'Contenido modificado satisfactoriamente', '/')
                    })
                    .catch((error) => {
                        messageHandler.set('error', error.response.data.message)
                    })
                }
                else
                {
                    if (this.content.name == '')
                    {
                        messageHandler.set('error', 'Debes rellenar el campo nombre para continuar', '', 2000)
                        return;
                    }
                    axios
                    .post(`/content/`, this.content)
                    .then(response => {
                        messageHandler.set('success', 'Contenido creado satisfactoriamente', '/')
                    })
                    .catch((error) => {
                        messageHandler.set('error', error.response.data.message)
                    })
                }
                   
            }, 
            selectLanguage(lang)
            {
                this.current_language = lang
            }
        }
    }
</script>

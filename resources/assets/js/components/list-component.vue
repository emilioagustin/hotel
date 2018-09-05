<template>
    <div>
        <message-component ref="messageHandler" v-bind:message="message"></message-component>

        <!--titulo y subtitulo -->
        <section class="heading">
            <div class="titulo">
                <h1>Listado de elementos</h1>      
            </div>
            <div class="action">
                <button @click="newElement()">NUEVO</button>
            </div>
        </section>
        <!--/titulo y subtitulo -->

        <!--Grid-->
        <div class="grid">
            <div class="head">
                Nombre
            </div>
            <draggable class="drag-area" :list="rows" :options="{animation:200, group:'order'}"  @change="update">
                <div class="row" :class="{ 'odd': row.order%2 == 0 }" :key="row.id" v-for="row in rows">
                    <div class="column col-1">
                        <div class="draggable">
                            <span>&#8942;</span>
                        </div>
                    </div>
                    <div class="column col-6">
                        <router-link
                            :to="{ name: 'edit', params: { id: row.id }}"
                            exact
                        >
                            {{ row.name }}
                        </router-link>
                    </div>
                    <div class="column col-3 options">
                        <ul>
                            <li>
                                <label class="custom__checkbox">
                                    <input type="checkbox" v-model="row.status">
                                    <span class="checkmark" @click="changeVisibility(row)"></span>
                                </label>
                            </li>
                            <li>
                                <router-link
                                    :to="{ name: 'gallery', params: { id: row.id }}"
                                    exact
                                >
                                    <img src="img/photo-icon.svg" alt="Gestión fotográfica" width="20">
                                </router-link>
                            </li>
                            <li>
                                <a href="javascript:void(0)" @click="remove(row.id)">
                                    <img src="img/trash-icon.svg" alt="Gestión fotográfica" width="12">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </draggable>
        </div>
        <!--/Grid -->
    </div>
</template>

<script>
    /* eslint-disable */
    import Draggable from 'vuedraggable'
    import MessageComponent from './message-component'
    import router from './../router'
    import axios from 'axios'

    export default {
        components: {
            Draggable, MessageComponent
        },
        name: 'ListComponent',
        data() {
            return {
                rows: [], 
                message: { type: '', text: '' }
            }
        }, 
        mounted() {
            axios
            .get('/content')
            .then(response => this.rows = response.data)
            .catch((error) => {
                messageHandler.set('error', error.response.data.message)
            })
        },
        methods: {
            getMessageHandler()
            {
                return this.$refs.messageHandler
            }, 
            newElement()
            {
                router.push('new')
            },
            remove(id)
            {
                if (confirm("¿Estás segur@ de que se deseas borrar el elemento?")) 
                {
                    const messageHandler = this.getMessageHandler()
                    axios
                    .delete(`/content/${id}`)
                    .then(response => {
                        this.rows = response.data
                        messageHandler.set('success', 'Elemento borrado satisfactoriamente')
                    })
                    .catch((error) => {
                        messageHandler.set('error', error.response.data.message)
                    })    
                }
            }, 
            changeVisibility(row)
            {
                const messageHandler = this.getMessageHandler()
                row.status = !row.status ? 1 : 0
                axios
                .put(`/content/${row.id}`, row)
                .then(response => {
                    this.rows = response.data
                    messageHandler.set('success', 'Visibilidad modificada satisfactoriamente')
                })
                .catch((error) => {
                    messageHandler.set('error', error.response.data.message)
                })   
            }, 
            update() {
                const messageHandler = this.getMessageHandler()
                let key = 1
                for (let row of this.rows)
                {
                    row.order = key
                    key++
                }
                axios
                .put(`/content/`, {rows:this.rows})
                .then(response => {
                    this.rows = response.data
                    messageHandler.set('success', 'Orden modificado satisfactoriamente')
                })
                .catch((error) => {
                    messageHandler.set('error', error.response.data.message)
                })
            }
        }
    }
</script>

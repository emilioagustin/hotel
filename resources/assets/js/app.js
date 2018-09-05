
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import '../less/styles.less'
import Vue from 'vue'
import App from './components/app-component.vue'
import router from './router'

Vue.config.productionTip = false

new Vue({
    el: '#app',
    router,
    components: { App },
    template: '<App/>'
})

//console.log(Vue.version)

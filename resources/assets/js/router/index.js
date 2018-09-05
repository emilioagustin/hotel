/* eslint-disable */

import Vue from 'vue'
import Router from 'vue-router'
import ListComponent from '../components/list-component'
import ViewItemComponent from '../components/view-item-component'
import GalleryComponent from '../components/gallery-component'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'home',
            component: ListComponent
        },
        {
            path: '/new',
            name: 'new',
            component: ViewItemComponent
        },
        {
            path: '/edit/:id',
            name: 'edit',
            component: ViewItemComponent, 
            props: true
        },
        {
            path: '/gallery/:id',
            name: 'gallery',
            component: GalleryComponent,
            props: true
        },
    ],
    mode: 'history'
})

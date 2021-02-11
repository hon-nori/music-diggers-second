import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

import Comming from './components/Comming';
import Login from './components/Login';
import Mypage from './components/Mypage';

export default new Router({
    // モードの設定
    mode: 'history',
    routes: [
        {
            // routeのパス設定
            path: '/',
            // コンポーネントの指定
            component: Mypage
        },
        {
            path: '/comming',
            name: 'comming',
            component: Comming
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/mypage',
            name: 'mypage',
            component: Mypage
        }
    ]
});
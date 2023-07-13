import 'bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import Links from './components/Links.vue';
app.component('links', Links);
app.mount('#app');

import App from './views/App.vue'
import Vue from 'vue';
import VueRouter from 'vue-router'

import Welcome    from './views/Welcome'
import RequisitionList    from './views/RequisitionListView'
import Declaration    from './views/Declaration'
import Receive    from './views/Receive'
import DeclarationsAdmin    from './views/DeclarationsAdmin'
import ListAdminInd    from './views/ListAdminInd'
import ListAdminMun    from './views/ListAdminMun'
import ListAdminCA    from './views/ListAdminCA'
import ListAdminDF    from './views/ListAdminDF'
import CarrierAdmin    from './views/CarrierAdmin'
import VehicleAdmin    from './views/VehicleAdmin'
import LerChapterAdmin from './views/LerChapterAdmin'
import LerSubChapterAdmin from './views/LerSubChapterAdmin'
import LerWasteAdmin    from './views/LerWasteAdmin'
import PeriodsAdmin  from './views/PeriodsAdmin'


Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Welcome,
        },
        {
            path: '/app',
            name: 'app',
            component: App,
        },
        {
            path: '/welcome',
            name: 'welcome',
            component: Welcome,
        },
        {
            path: '/declarations',
            name: 'declarations',
            component: Declaration,
        },
        {
            path: '/receive',
            name: 'receive',
            component: Receive,
        },
        {
            path: '/requisition_list',
            name: 'requisition_list',
            component: RequisitionList,
        },
        {
            path: '/declarationsadmin',
            name: 'declarationsadmin',
            component: DeclarationsAdmin,
        },
        {
            path: '/listadminind',
            name: 'listadminind',
            component: ListAdminInd,
        },
        {
            path: '/listadminmun',
            name: 'listadminmun',
            component: ListAdminMun,
        },
        {
            path: '/listadminca',
            name: 'listadminca',
            component: ListAdminCA,
        },
        {
            path: '/listadmindf',
            name: 'listadmindf',
            component: ListAdminDF,
        },
        {
            path: '/vehicleadmin',
            name: 'vehicleadmin',
            component: VehicleAdmin,
        },
        {
            path: '/carrieradmin',
            name: 'carrieradmin',
            component: CarrierAdmin,
        },
        {
            path: '/lerchapteradmin',
            name: 'lerchapteradmin',
            component: LerChapterAdmin,
        },
        {
            path: '/lersubchapteradmin',
            name: 'lersubchapteradmin',
            component: LerSubChapterAdmin,
        },
        {
            path: '/lerwasteadmin',
            name: 'lerwasteadmin',
            component: LerWasteAdmin,
        },
        {
            path: '/periodsadmin',
            name: 'periodsadmin',
            component: PeriodsAdmin,
        },



    ],
});


export default router;
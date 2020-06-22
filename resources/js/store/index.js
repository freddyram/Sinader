import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
import createPersistedState from 'vuex-persistedstate'



export default new Vuex.Store(
{
	state: {
	    token:'',
	    type:'',
	    establishment: '',
	    user: '',
	    company: '',
	    receiver: '',
	    residue:'',
	    carrier:'',
	    indexedit:-1,
	    wastedetail:'',

	},
	mutations: {
		changeType(state, type) {
		    state.type = type;
		},
		changeToken(state, token) {
		    state.token = token;
		},
		changeEstablishment(state, establishment) {
		   	state.establishment = establishment;
		   //	state.currentCiiu = establishment.ciiu4.name;
		},
		changeUser(state, user) {
		   	state.user = user
		},
		changeReceiver(state, receiver) {
		   	state.receiver = receiver;
		},
		changeCompany(state, company) {
		   	state.company = company;
		},
		changeResidue(state, residue) {
		   	state.residue= residue;
		},
		changeCarrier(state, carrier) {
		   	state.carrier = carrier;
		},
		changeIndexedit(state, indexedit) {
		   	state.indexedit = indexedit;
		},
		changeWastedetail(state, wastedetail) {
		   	state.wastedetail = wastedetail;
		},
	},
	getters: {
		type: state => state.type,
		token: state => state.token,
		establishment: state => state.establishment, 
		user: state => state.user,
		receiver: state => state.receiver,
		company: state => state.company,
		residue: state => state.residue,
		carrier: state => state.carrier,
		indexedit: state => state.indexedit,
		wastedetail: state => state.wastedetail,
	},
    plugins: [
    	createPersistedState()
  	]
});
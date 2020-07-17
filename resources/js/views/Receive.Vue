<template>

<v-layout row>
<v-layout>
	<v-flex xs12 class="pr-1 px-2">
	  
    <v-card>
    <v-toolbar  color="main_green" dark>

      	<v-toolbar-title>Validar Recepción de Residuos no Peligrosos</v-toolbar-title>
    </v-toolbar>



    <v-data-table
      :headers="headers"
      :items="declarations"
      class="elevation-1"
      :rows-per-page-items="rowsPerPageItems"
      :pagination.sync="pagination"
      
    >
      <template v-slot:items="props">
        <td class="text-xs-right">{{ props.item.correlative }} - {{ props.item.correlative_dv }}</td>
        <td class="text-xs-right">{{ props.item.period }}</td>
        <td class="text-xs-right">{{ props.item.created_at }}</td>
        <td class="text-xs-right">{{ props.item.establishment }}</td>
        <td class="text-xs-right">{{ props.item.status }}</td>

        <td> 
            <v-btn  v-if="props.item.status=='ENVIADA'" small @click="toReceive(props.item)" color="main_green" dark>Recepcionar</v-btn>
        </td>
        <td v-if="props.item.status!='ENVIADA'" > 
            <v-btn  small @click="toNewDeclaration(props.item)" color="secondary_green" dark>Ver</v-btn>
        </td>   

      </template>
    </v-data-table>


    </v-card>
    	</v-flex>

</v-layout>
    <br>
    <br>
  

<v-layout>
	<v-flex xs12 class="px-2">

    <v-card>

    <v-toolbar  color="main_green" dark>

      	<v-toolbar-title>Recepción de Residuos no Peligrosos Generadores No Registrados</v-toolbar-title>

 	    <v-spacer></v-spacer>

        <v-btn @click='toNNReceive(false)' color="main_green">Nueva Recepción</v-btn>

    </v-toolbar>



    <v-data-table
      :headers="headers"
      :items="receivenn"
      class="elevation-1"
      :rows-per-page-items="rowsPerPageItems"
      :pagination.sync="pagination"
      
    >
      <template v-slot:items="props">
        <td class="text-xs-right">{{ props.item.correlative }} - {{ props.item.correlative_dv }}</td>
        <td class="text-xs-right">{{ props.item.period }}</td>
        <td class="text-xs-right">{{ props.item.created_at }}</td>
        <td class="text-xs-right">{{ props.item.establishment }}</td>
        <td class="text-xs-right">{{ props.item.status }}</td>


        <td> 

            <v-btn small @click="toNNReceive(props.item)" color="main_green" dark>Editar</v-btn>

        </td>   
      </template>
    </v-data-table>


    </v-card>

</v-flex>
</v-layout> 
</v-layout>    
</template>

<script>

  import { mapState } from 'vuex';  
  import Vue from 'vue';  
  import { EventBus } from './../eventbus.js';
  import { EventBus2 } from './../eventbus2.js';

 
  import ReceiveComponent  from './../components/ReceiveComponent';
  import DeclarationComponent  from './../components/DeclarationComponent';
  import NewReceiveComponent  from './../components/NewReceiveComponent';



  export default {
    props: {
        declaration: Object
    },
    data: () => ({
        headers: [

            { text: 'Folio Origen', value: '' },           
            { text: 'Período Origen', value: '' },
            { text: 'Fecha', value: '' },
            { text: 'Establecimiento', value:''},
            { text: 'Estado', value: '' },
            { text: 'Accion', value: '' },
        ],
        declarations: [],
        receivenn:[],

        rowsPerPageItems: [5, 10, 20, 40],
        pagination: {
            rowsPerPage: 40
        }, 


        }),
    

    created () {
        var app = this;
        EventBus.$on('changestatus', function(){ 
            app.getdecalrations();
        });
        EventBus2.$on('save', function(){ 
            app.getdecalrations();
        });

        this.initialize();
    },
    
    methods: {
        initialize(){
            this.getdecalrations();
        },  

        getdecalrations(){
            var app = this;

            axios.get('/api/declarations/forreceiver/' + this.$store.getters.establishment.id)
                .then(function (resp) {    
                    app.declarations = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });

            axios.get('/api/declarations/forreceivergennn/' + this.$store.getters.establishment.id)
                .then(function (resp) {    
                    app.receivenn = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });

        },

        toNewDeclaration (declaration){

            var Component = Vue.extend(DeclarationComponent)
            var instance = new Component({store: this.$store, propsData: {
            declaration_edit: declaration,
            }});
            instance.$mount();
            this.$refs.container.innerHTML = "" 
            this.$refs.container.replaceChild(instance.$el);
        },


        toReceive ($declaration){

            var Component = Vue.extend(ReceiveComponent)

            var instance = new Component({store: this.$store, propsData: {
            declaration_edit: $declaration,
             
          }});
            instance.$mount();
            this.$refs.container.replaceChild(instance.$el);
        },



        toNNReceive (declaration){


            var Component = Vue.extend(NewReceiveComponent)
            var instance = new Component({store: this.$store, propsData: {
              declaration_edit: declaration, 
            }});

            instance.$mount();
            this.$refs.container.replaceChild(instance.$el);
        },

    }
    }
</script>   
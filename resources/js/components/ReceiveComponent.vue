<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">

      <v-card>
        <v-toolbar dark color="main_green">
          <v-btn icon dark @click="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Recepción de residuos no peligrosos</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn flat class='white--text'  @click="rejection()">Rechazar</v-btn>
            
          </v-toolbar-items>
        </v-toolbar>

                <v-card class="px-5">
                    <br>

                        <v-layout>
                        <v-flex  xs3 class="pr-1">
                            <v-text-field v-model="folio"  readonly='true' label="Folio"></v-text-field>
                        </v-flex>

                        <v-flex  xs3 class="px-1">
                            <v-text-field  v-model="this.$store.getters.establishment.id" readonly='true' label="Id VU"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="this.company" readonly='true' label="Empresa"></v-text-field>
                        </v-flex>
                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="this.rut" readonly='true' label="Rut"></v-text-field>
                        </v-flex>
                    </v-layout>

                    <v-layout>
                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="this.establishment" readonly='true'  label="Establecimiento"></v-text-field>
                        </v-flex>
 
                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="this.address" readonly='true'  label="Dirección"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="this.commune"  label="Comuna"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="this.region" readonly='true' label="Región"></v-text-field>
                        </v-flex>
                    </v-layout>
 



                </v-card>
        



        <v-toolbar  color="secondary_green" dark>
            <v-toolbar-title >Residuos</v-toolbar-title>
        </v-toolbar>

        <v-data-table
          :headers="headers"
          :items="residues"
          class="elevation-1"
         
        >
          <template v-slot:items="props">
            <td class="text-xs-right">{{ props.item.waste }}</td>
            <td class="text-xs-right">{{ props.item.processing }}</td>
            <td class="text-xs-right">{{ props.item.gestion }}</td>
            <td class="text-xs-right">{{ props.item.quantity }}</td>
            <td v-if="props.item.discrep_quantity>0"  class="text-xs-right">{{ props.item.discrep_quantity }}</td>
            <td v-if="props.item.discrep_quantity<=0" class="text-xs-right">{{ props.item.quantity }}</td>

            <td class="justify-center layout px-0">
                <v-btn small @click="toNewResidue(props.item)" color="main_green" dark>Discrepancia</v-btn>
                <v-btn v-if="$store.getters.type!='DestinatarioFinal' " small @click="toNewTraceability(props.item)" color="main_green" dark>Trazabilidad</v-btn>
            </td>   


          </template>
        </v-data-table>

            <v-layout>
                <v-flex xs10 class="px-1">
                </v-flex>
                <v-flex xs2 class="px-1">
                    
                    <v-btn  class="ma-2 white--text" color="main_green"  @click="receive()">
                        Recepcionar
                        <v-icon right>save</v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>  

      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>


  import Vue from 'vue';  
  import Vuex from 'vuex'; 
  import { mapState } from 'vuex';  
  
  import { EventBus } from './../eventbus.js';

  import DiscrepancyComponent  from './../components/DiscrepancyComponent';
  import TraceabilityComponent  from './../components/TraceabilityComponent';


  export default {
    props: {
        declaration_edit: Object,
    },
    data () {
      return {
        dialog: true,
        notifications: false,

        headers: [
            { text: 'Descripción del Residuo', value: '' },
            { text: 'Tipo de Tratamiento', value: '' },
            { text: 'Tipo de Gestión', value: '' },
            { text: 'Cantidad', value: '' },
            { text: 'Recepcionado', value: '' },

        ],


        correlative:'',
        company:'',
        rut:'',
        establishment:'',
        address:'',
        commune:'',
        region:'',
        folio:'',
       
        residues: [],
        
      }
    },
    
    created () {
        this.initialize();
        var app = this;
        EventBus.$once('saveDiscrepancy', function(){   
            app.refreshList();
        });
    },

    methods: {
        initialize(){

            this.folio = this.declaration_edit.correlative + '-' + this.declaration_edit.correlative_dv;
            this.correlative    = this.declaration_edit.correlative; 
            this.company        = this.declaration_edit.company;
            this.rut            = this.declaration_edit.rut ;
            this.establishment  = this.declaration_edit.establishment;
            this.address        = this.declaration_edit.direccion;
            this.commune        = this.declaration_edit.comuna;
            this.region         = this.declaration_edit.region;

            this.refreshList();
            
        },  

        refreshList(){
            var app = this;
            var params = {
                declaration_id: this.declaration_edit.id,
                establishment_id: this.$store.getters.establishment.id,
            }

            //alert(JSON.stringify(params));    
            axios.post('/api/waste_details/forreceiver', params)
                    .then(function (resp) {    
                        //alert(JSON.stringify(resp.data));               
                        app.residues = resp.data;
                    })
                    .catch(function (resp) {
                        console.log(resp);
                    }); 
        },


        toNewResidue (residue){

                var ComponentReserv = Vue.extend(DiscrepancyComponent)
                var instance = new ComponentReserv({store: this.$store, propsData: {
                residue: residue, 
                }});
                instance.$mount();
                this.$refs.container.replaceChild(instance.$el);

        },


        toNewTraceability (item){

                var ComponentReserv = Vue.extend(TraceabilityComponent)
                var instance = new ComponentReserv({store: this.$store, propsData: {
                waste_detail: item, declaration_origin: this.declaration_edit 
                }});
                instance.$mount();
                this.$refs.container.replaceChild(instance.$el);

        },
        receive(){
            alert('Esta Declaración se dará por recibida en su totalidad')

            var params = {
                'declaration_id': this.declaration_edit.id,
                'status': 'ACEPTADA',
            }

            axios.post('/api/declaration/changestatus', params)
                    .then(function (resp) {    
                        //alert(JSON.stringify(resp.data));  
                        EventBus.$emit('changestatus', 'someValue');              
                    })
                    .catch(function (resp) {
                        console.log(resp);
                    }); 

            this.dialog = false;
        },

        rejection(){
            alert('Esta Declaración será rechazada en su totalidad')
            var params = {
                'declaration_id': this.declaration_edit.id,
                'status': 'RECHAZADA',
            }

            var declaration_id = this.declaration_edit.id;

            axios.post('/api/declaration/changestatus', params)
                    .then(function (resp) {    
                        //alert(JSON.stringify(resp.data)); 
                        EventBus.$emit('changestatus', 'someValue');               
                    })
                    .catch(function (resp) {
                        console.log(resp);
                    }); 

            alert("Declaracion  :" + declaration_id);

            alert('Envío de Correo ')

            axios.get('/api/mail/sendrejectdeclaration/'+declaration_id)
                    .then(function (resp) {    
                        alert(JSON.stringify(resp.data)); 
                        EventBus.$emit('RejectDeclaration', 'someValue');               
                    })
                    .catch(function (resp) {
                        console.log(resp);
                    }); 



            this.dialog = false;
        }



    }

  }
</script>
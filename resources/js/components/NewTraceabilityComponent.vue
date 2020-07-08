<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="1200"
    >
<!--       <template v-slot:activator="{ on }">
        <v-btn
          color="red lighten-2"
          dark
          v-on="on"
        >
          Click Me
        </v-btn>
      </template> -->

      <v-card>

        <v-toolbar dark color="main_green">
          <v-btn icon dark @click="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Agregar Destinatario de Trazabilidad</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
              <v-switch class='py-4' v-model="checkbox"  label="¿ Residuo exportado ?"> </v-switch>
          </v-toolbar-items>
          <v-spacer></v-spacer>
        </v-toolbar>

        <v-card-text>
            <v-form ref="form"  lazy-validation>


                    <v-layout>
                        <v-flex xs10 class="px-1">
                            <v-text-field v-model='residue' readonly  label="Residuo"></v-text-field>
                        </v-flex>
                    </v-layout>    
                    <v-layout>
                        
                        <v-flex xs2 class="px-1">
                            <v-text-field v-model='cantidad' type='number' label="Cantidad"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-select
                                :items="units"
                                v-model="unidad"
                                label="Unidad de Medida"
                                item-text="name" 
                                :rules = "generalRule"
                                v-on:change="changeUnit"
                                return-object
                            ></v-select> 
                        </v-flex>

                    </v-layout>



                    <v-layout v-if="checkbox">
                        <v-flex xs3 class="px-1">
                            <!-- <v-text-field v-model="pais" label="País"></v-text-field> -->

                            <v-select
                                :items="countries"
                                v-model="country"
                                label="Pais"
                                item-text="name" 
                                v-on:change="changeCountry"
                                return-object
                            ></v-select> 


                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="empresa" label="Empresa"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="contacto" label="Contacto"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="email" label="Email"></v-text-field>
                        </v-flex>

                    </v-layout>


                    <v-layout v-if="! checkbox">
                        <v-flex  xs3 class="px-1">

                            <v-text-field color="main_green" box placeholder="Seleccionar" @click='toSearch' v-model="receiver_name"  readonly label="Destinatario"></v-text-field>

                        </v-flex>

                        <v-flex  xs3 class="px-1">
                            <v-select
                                :items="establishments"
                                v-model="establishment"
                                item-text="name"  
                                label="Establecimiento"
                                v-on:change="changeEstablishment"
                                return-object
                            ></v-select> 

                        </v-flex>
                        <v-flex xs3 class="px-1">
                            
                            <v-select
                                :items="processings"
                                v-model="procesing"
                                label="Tipo de Tratamiento"
                                item-text="name"  
                                v-on:change="changeProcess"
                                return-object
                            ></v-select> 
                        </v-flex>
                        <v-flex xs3 class="px-1">
                            
                            <v-select
                                :items="gestions"
                                v-model="gestion"
                                label="Tipo de Gestión"
                                item-text="name" 
                                v-on:change="changeGestion"
                                return-object
                            ></v-select> 

                        </v-flex>

                    </v-layout>



           </v-form>         
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
            <v-btn class="ma-2 white--text"  color="main_green"  @click="saveResidue()">
                GUARDAR
                <v-icon right>save</v-icon>
            </v-btn>
        </v-card-actions>   


      </v-card>
    </v-dialog>
  </div>
</template>

<script>

  import Vue from 'vue';  
  import Vuex from 'vuex'; 
  import { mapState } from 'vuex';  
  
  import { EventBus } from './../eventbus.js';

  import TransportComponent  from './../components/TransportComponent';
  import SearchReceiverComponent  from './../components/SearchCompanyComponent';

  export default {
    props:{
       waste_detail: Object,
       declaration_origin: Object,
    },
    data () {
      return {
        checkbox:false,
        dialog: true,

        residue: '',
        cantidad: '',
        units:[],
        unidad: 'Toneladas',

        pais:' ',
        empresa:' ',
        contacto:' ',
        email:' ',


        receiver_name:'',
        establishments:'',
        procesing:'',
        gestion:'',

        establishment_selected:'',
        process_selected:'',
        gestion_selected:'',
        unit_selected:'',
        }
    },
    
    
    created(){
        var app = this;
        this.initialize();
        // EventBus.$on('saveCarrier', function(){  
        //     app.refreshCarrier();
        // });
        EventBus.$once('selectReceiver', function(){ 
            app.selectCompany();
        });

    },
    methods: {
        initialize(){


            this.residue  = this.waste_detail.waste;
            this.cantidad = this.waste_detail.quantity;


            var app = this;

            axios.get('/api/unit/forid/'+this.waste_detail.unit_id)
                .then(function (resp) {    
                    app.unit_selected = resp.data;
                    app.unidad   = app.unit_selected.name;
                })
                .catch(function (resp) {
                    console.log(resp);
                });

            axios.get('/api/unit')
                .then(function (resp) {   
                    // alert(JSON.stringify(resp.data)); 
                    app.units = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });

            axios.get('/api/managetype')
                .then(function (resp) {    
                    app.gestions = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });

            axios.get('/api/processtype')
                .then(function (resp) {    
                    app.processings = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });

            axios.get('/api/recolectiontype')
                .then(function (resp) {    
                    app.tipos_recoleccion = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });
            axios.get('/api/lerchapter')
                .then(function (resp) {    
                    app.capitulos = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });

        },

        selectCompany(){
            this.receiver_name= this.$store.getters.receiver.name;
            this.changeCompany(this.$store.getters.receiver);
            
        },

        changeCompany(company_selected){
            var app = this;
            this.company_selected = company_selected;

            axios.get('/api/establishments/'+this.company_selected.id)
                .then(function (resp) {    
                    app.establishments = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });
        },

        changeEstablishment(establishment_selected){
            this.establishment_selected = establishment_selected;
        },

        changeProcess(process_selected){
            this.process_selected = process_selected;
        },

        changeGestion(gestion_selected){
            this.gestion_selected = gestion_selected;
        },

        changeEstablishment(establishment_selected){
            this.establishment_selected = establishment_selected;
        },

        changeUnit(unit_selected){
            this.unit_selected = unit_selected;
        },

        toTransport (){
            var ComponentReserv = Vue.extend(TransportComponent)
            var instance = new ComponentReserv({store: this.$store, propsData: {
            source: '', 
            }});
            instance.$mount();
            this.$refs.container.replaceChild(instance.$el);
        },

        refreshCarrier(){
            this.carrier = this.$store.getters.carrier;
            this.carriername = this.carrier.carriername;
            this.vehicleplate = this.carrier.vehicleplate;

            //alert(JSON.stringify(this.carrier));
        },
        toSearch(){
                var ComponentReserv = Vue.extend(SearchReceiverComponent)
                var instance = new ComponentReserv({store: this.$store, propsData: {
                source: '', 
                }});
                instance.$mount();
                this.$refs.container.replaceChild(instance.$el);
       
        },
        saveResidue(){


            if (this.$refs.form.validate()){

                this.residue = {
                    waste: this.waste_detail.waste,
                    sum: this.cantidad + ' ' + this.unit_selected.name,
                    company: this.company_selected.rut + ' | ' + this.company_selected.name,
                    establishment: this.establishment_selected.id + ' | ' + this.establishment_selected.name,
                    processing: this.procesing.name,
                    gestion: this.gestion.name,
                    pais: this.pais,
                    empresa: this.empresa,
                    contacto: this.contacto,
                    email: this.email,


                    chapter: this.waste_detail.chapter,
                    subchapter: this.waste_detail.subchapter,

                    waste_id: this.waste_detail.waste_id,
                    chapter_id: this.waste_detail.subchapter_id,
                    subchapter_id: this.waste_detail.subchapter_id,

                    carrier_name:null,
                    carrier_id: null,
                    carrier_name: null,
                    trasnport_date: null,
                    plate_id: null,
                    plate: null,

                    company_id: this.company_selected.id,
                    establishment_id: this.establishment_selected.id,
                    process_id: this.process_selected.id,
                    manage_id: this.gestion_selected.id,
                    quantity: this.cantidad,
                    waste_id: this.waste_detail.id,
                    unit_id: this.unit_selected.id,
                    carrier_id: 0,
                    carrier:'',
                };

                this.$store.commit('changeResidue', this.residue);

                this.dialog = false;

                EventBus.$emit('saveResidues', 'someValue'); 
            }
            
        },

    }
}
  
</script>
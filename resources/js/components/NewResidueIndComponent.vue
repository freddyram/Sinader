<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="1200"
      persistent="true"
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
          <v-btn icon dark @click="dialog=false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Agregar Residuo</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>

              <v-switch class='py-4' v-model="checkbox"  label="¿ Residuo exportado ?"> </v-switch>

          </v-toolbar-items>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-card-text>
            <v-form ref="form_ler"  lazy-validation>

                    <v-layout>
                        <v-flex  xs4 class="px-1">

                            <v-select
                                :items="capitulos"
                                v-model="capitulo"
                                label="Capitulo"
                                :rules = "generalRule"
                                item-text="name"   
                                v-on:change="changeChapter"
                                return-object
                            ></v-select> 


                        </v-flex>
                        <v-flex  xs4 class="px-1">
                            <v-select
                                :items="subcapitulos"
                                v-model="subcapitulo"
                                label="Sub Capitulo"
                                :rules = "generalRule"
                                item-text="name" 
                                v-on:change="changeSupChapter"
                                return-object
                            ></v-select> 

                        </v-flex>
                        <v-flex xs4 class="px-1">
                            <v-select
                                :items="residues"
                                v-model="residue"
                                label="Residuo"
                                item-text="name" 
                                v-on:change="changeResidue"
                                return-object
                                :rules = "generalRule"
                            ></v-select> 
                        </v-flex>
                        
                    </v-layout>
            </v-form>    
            <v-form ref="form_exportado"  lazy-validation>    
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
                            <v-text-field v-model="email" :rules = 'emailRule' label="Email"></v-text-field>
                        </v-flex>

                    </v-layout>

            </v-form>
            <v-form ref="form_destinatario"  lazy-validation>        
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

                    <v-layout>
                        <v-flex xs3 class="px-1">
                            <v-text-field placeholder='999,99'  :rules = "numberRule" v-model='cantidad' type='number' label="Cantidad"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-select
                                :items="units"
                                v-model="unidad"
                                label="Unidad de Medida"
                                item-text="name" 
                                :rules = "generalRule"
                                v-on:change="changeUnit"
                                readonly
                                return-object
                            ></v-select> 
                        </v-flex>

                    </v-layout>

 


                    <v-layout v-if="this.$store.getters.type=='CentroAcopio' || this.$store.getters.type=='GeneradorIndustrial' || this.$store.getters.type=='DestinatarioFinal' ">

                        <v-flex xs3 class= "px-2">  
                            <v-text-field color="main_green" box placeholder="Seleccionar" @click='toTransport' v-model="carriername"  readonly label="Empresa Trasnporte"></v-text-field>
                        </v-flex>    
                        <v-flex xs3 class= "px-2">                  
                            <v-text-field readonly v-model="carrier_date" label="Fecha de Transporte"></v-text-field>
                        </v-flex>
                        <v-flex xs3>
                            <v-text-field readonly v-model="vehicleplate" label="Patente"></v-text-field>
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
    props: {
        residue_edit: Object,
    },
    data () {
      return {

        generalRule: [v => !!v || 'Campo requerido'],
        numberRule: [v => v && /^\d+(\.\d+)?$/.test(v) || 'Debe ser numérico', (v) => v<=9999 || 'Valor excede el máximo'],
        emailRule: [(v) => !!v || "Campo requerido", (v) => /.+@.+\..+/.test(v) || "El E-mail debe ser valido"],

        receiver_name:'',
        maxValue: 9999,
        minValue: 1,

        checkbox:false,
        dialog: true,

        cantidad:'',
        establishment:'',
        unidad:'Toneladas',
        destiny:'',
        residue:'',
        residue_name:'',
        capitulos: '',
        capitulo:'',
        subcapitulos: '',
        subcapitulo: '',
        residues: '',

        company_id:'',
        establishment_id:'',
        establishment_name:'',
        residue_id:'',
        process_id:'',
        gestion_id:'',
        unit_id:'',
        country_selected:'',

        chapter_id:'',
        chapter_name:'',
        subchapter_id:'',
        subchapter_name:'',

        pais:'Chile',
        empresa:'',
        contacto:'',
        email:'',
        
        carrier:'',
        carriername:'',
        vehicleplate:'',
        carrier_date:'',

        tipos_recoleccion: '',
        
        companies: [],
        countries:[],
        establishments: [],


        procesing:'',
        processings:'',
        gestion:'',
        gestions:'',
        units:'',
        }
    },
    created(){
        var app = this;

        

        this.initialize();
        EventBus.$once('saveCarrier', function(){  
            app.refreshCarrier();
        });
        EventBus.$once('selectReceiver', function(){ 
            app.selectCompany();
        });

    },
    methods: {
        initialize(){

           
            // alert('initialize');

            // alert(JSON.stringify(this.$store.getters.residue));
            

            var app = this;
            axios.get('/api/unit')
                .then(function (resp) {    
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

            axios.get('/api/countries')
                .then(function (resp) {    
                    app.countries = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });

            if(this.residue_edit){
                //alert(JSON.stringify(this.residue_edit));
                this.setEdit();
            } 
        },

        setEdit(){

                    //alert(JSON.stringify(this.residue_edit))

                    this.cantidad    = this.residue_edit.quantity;
                     
                    this.empresa     = this.residue_edit.empresa;
                    this.contacto    = this.residue_edit.contacto;
                    this.email       = this.residue_edit.email;
                    this.pais        = this.residue_edit.pais;
                    this.country     = this.residue_edit.pais;

                    this.capitulo    = this.residue_edit.chapter;
                    
                    this.chapter_id    = this.residue_edit.chapter_id;
                    this.chapter_name    = this.residue_edit.chapter;

                    this.subchapter_id = this.residue_edit.subchapter_id;
                    this.subchapter_name = this.residue_edit.subchapter;

                    this.company_id    = this.residue_edit.company_id;

                    this.receiver_name = this.residue_edit.company;

                    this.carrier_id   = this.residue_edit.carrier_id;
                    this.carriername  = this.residue_edit.carrier_name;
                    this.vehicleplate = this.residue_edit.plate;
                    this.carrier_date = this.residue_edit.trasnport_date;
                    
                    this.residue_id   = this.residue_edit.waste_id;
                    this.process_id   = this.residue_edit.process_id;
                    this.gestion_id   = this.residue_edit.manage_id;
                    this.unit_id      = 2;


                     // alert(JSON.stringify(this.residue_edit));

                    if(this.residue_edit.establishment=='Exportado'){
                        this.checkbox=true;
                    }

                    

                    var app = this;
                    app.subcapitulo = app.residue_edit.subchapter;
                    axios.get('/api/lersubchapter/' + this.chapter_id)
                        .then(function (resp) {   
                            app.subcapitulos = resp.data;
                            
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        });

                        

                    axios.get('/api/lerwaste/' + this.residue_edit.subchapter_id)
                    .then(function (resp) {   
                        app.residues = resp.data;
                        app.residue_name = app.residue_edit.waste; 
                        app.residue = app.residue_edit.waste; 
                    })
                    .catch(function (resp) {
                        console.log(resp);
                    });

                    
                    axios.get('/api/unit/forid/'+this.residue_edit.unit_id)
                        .then(function (resp) {    
                            app.unidad = resp.data;
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        });
  

                    if(this.residue_edit.establishment_id){

                    axios.get('/api/establishments/'+this.company_id)
                        .then(function (resp) {    
                            app.establishments = resp.data;
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        });


                    axios.get('/api/establishment/forid/'+this.residue_edit.establishment_id)
                        .then(function (resp) {    
                            app.establishment_selected=resp.data;
                            app.establishment = app.establishment_selected.name;
                            app.establishment_name = app.establishment_selected.name;
                            app.establishment_id = app.establishment_selected.id;

                        })
                        .catch(function (resp) {
                            console.log(resp);
                        }); 
                    }       

                    if(this.residue_edit.process_id){
                    axios.get('/api/processtype/forid/'+this.residue_edit.process_id)
                        .then(function (resp) {    
                            app.process_selected=resp.data;
                            app.procesing= app.process_selected.name;
                            app.processing_name= app.process_selected.name;
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        }); 
                    }

                    if(this.residue_edit.manage_id){
                    axios.get('/api/managetype/forid/'+this.residue_edit.manage_id)
                        .then(function (resp) {    
                            app.gestion_selected=resp.data;
                            app.gestion = app.gestion_selected.name;
                            app.gestion_name = app.gestion_selected.name;
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        });
                    }

                  


                    if(this.residue_edit.carrier_id){
                    axios.get('/api/carrier/forid/'+this.residue_edit.carrier_id)
                        .then(function (resp) {    
                            app.carrier=resp.data;
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        });
                    }                    
        

        },

        changeChapter(chapter_selected){
            var app = this;

            this.chapter_id = chapter_selected.id;
            this.chapter_name = chapter_selected.name;
            
            axios.get('/api/lersubchapter/' + chapter_selected.id)
                .then(function (resp) {   
                    app.subcapitulos = resp.data;
                    app.residues = [];
                })
                .catch(function (resp) {
                    console.log(resp);
                });
        },




        changeSupChapter(subchapter_selected){
            var app = this;

            this.subchapter_id = subchapter_selected.id;
            this.subchapter_name = subchapter_selected.name;
            
            axios.get('/api/lerwaste/' + subchapter_selected.id)
                .then(function (resp) {   
                    app.residues = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });
        },

        changeResidue(residue_selected){
            this.residue_name = residue_selected.name;
            this.residue_id = residue_selected.id;
            this.residue_selected = residue_selected;
        },

        selectCompany(){

            this.company_id = this.$store.getters.receiver.id
            this.receiver_name= this.$store.getters.receiver.rut +'-'+ this.$store.getters.receiver.digit +' | '+ this.$store.getters.receiver.name;
            this.changeCompany(this.$store.getters.receiver);
            
        },

        changeCompany(company_selected){

            var app = this;
            this.company_selected = company_selected;
            this.company_id = company_selected.id;
            axios.get('/api/establishments/'+this.company_selected.id)
                .then(function (resp) {    
                    app.establishments = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });
        },

        changeEstablishment(establishment_selected){
            this.establishment_name = establishment_selected.name;
            this.establishment_id = establishment_selected.id;
            this.establishment_selected = establishment_selected;
        },

        changeProcess(process_selected){
            this.processing_name = process_selected.name;
            this.process_id = process_selected.id;
            this.process_selected = process_selected;
        },

        changeGestion(gestion_selected){
            this.gestion_name = gestion_selected.name;
            this.gestion_id = gestion_selected.id;
            this.gestion_selected = gestion_selected;
        },

        changeCountry(country_selected){
            this.pais = country_selected.name;
            this.country_selected = country_selected;
        },

        changeUnit(unit_selected){
            this.unit_selected = unit_selected;
            this.unit_id = unit_selected.id;
        },

        selectExport(){
            this.company_selected        = '';
            this.establishment_selected  = '';
            this.process_selected        = '';
            this.gestion_selected        = '';  

            this.receiver_name = ''; 
            this.establishment = ''; 
            this.processings   = ''; 
            this.gestion       = ''; 

            // if(this.checkbox){
            //     this.checkbox=false;
            // }else {
                this.checkbox=true;
            // }
            
        },



        saveResidue(){

            var okSave = false;
            if(this.checkbox){

                if(this.$refs.form_ler.validate() && this.$refs.form_exportado.validate()){
                    okSave = true;
                }
             } else {
                if(this.$refs.form_ler.validate() && this.$refs.form_destinatario.validate()){
                    okSave = true;
                }                
             }

            if (okSave){

                var company_name = this.receiver_name;
                var processing_name = this.processing_name;
                var gestion_name = this.gestion_name;

                if(this.checkbox){
                    this.establishment_name = 'Exportado';
                    company_name = this.country_selected.name + ' | ' + this.empresa;
                    processing_name = 'Exportado';
                    gestion_name = 'Exportado';
                }        


                this.residue = {
                    waste: this.residue_name,
                    chapter:  this.chapter_name,  
                    subchapter: this.subchapter_name,
                    sum: this.cantidad + ' ' + this.unidad.name,
                    company: company_name,
                    establishment: this.establishment_name,

                    
                    processing: processing_name,
                    gestion: gestion_name,
                    pais: this.pais,
                    empresa: this.empresa,
                    contacto: this.contacto,
                    email: this.email,

                    company_id: this.company_id,
                    establishment_id: this.establishment_id,
                    process_id: this.process_id,
                    manage_id: this.gestion_id,
                    quantity: this.cantidad,

                    waste_id: this.residue_id,
                    chapter_id: this.chapter_id,
                    subchapter_id: this.subchapter_id,

                    unit_id: 2,
                    carrier_id: this.carrier_id,
                    carrier_name:this.carriername,
                    trasnport_date:this.carrier_date,
                    plate: this.vehicleplate,


                };



                this.$store.commit('changeResidue', this.residue);

                this.dialog = false;

                EventBus.$emit('saveResidues', 'someValue'); 
            }
            
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
            this.carrier_id = this.$store.getters.carrier.carrier_id;
            this.carrier = this.$store.getters.carrier;
            this.carriername = this.carrier.carriername;
            this.vehicleplate = this.carrier.vehicleplate;
            this.carrier_date = this.carrier.transport_date;
        },
        toSearch(){
                var ComponentReserv = Vue.extend(SearchReceiverComponent)
                var instance = new ComponentReserv({store: this.$store, propsData: {
                source: '', 
                }});
                instance.$mount();
                this.$refs.container.replaceChild(instance.$el);
       
        },


    }
}
  
</script>



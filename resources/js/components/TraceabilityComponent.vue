<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="1100"
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
          <v-btn icon dark @click="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Agregar Trazabilidad</v-toolbar-title>

        </v-toolbar>


        <v-card-text>
     
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
                    <v-layout>
                        <v-flex xs12 class="px-1">
                        <v-toolbar  color="secondary_green" dark>
                            <v-toolbar-title >Destinatarios</v-toolbar-title>

                        <v-spacer></v-spacer>
                        
                          <v-btn  class="ma-2 white--text" color='main_green'  @click="NewDestiny">
                            Agregar
                            <v-icon right>add</v-icon>
                          </v-btn>
                        
                        </v-toolbar>

                        </v-flex>
                    </v-layout>

                    <v-layout>

                        <v-flex xs12 class="px-1">
                        <v-data-table
                          :headers="headers"
                          :items="residues"
                          class="elevation-1"
                         
                        >
                          <template v-slot:items="props">
                            <td class="text-xs-right">{{ props.item.establishment }}</td>
                            <td class="text-xs-right">{{ props.item.processing }}</td>
                            <td class="text-xs-right">{{ props.item.gestion }}</td>
                            <td class="text-xs-right">{{ props.item.sum }}</td>

                          </template>
                        </v-data-table>
                      </v-flex>
                    </v-layout>

                    <v-layout>
                      <v-flex xs6 class="px-1 py-4">
                      </v-flex>  
                      <v-flex xs3 class="px-1 py-4">
                          <h2> Total:</h2>
                      </v-flex> 
                      <v-flex xs3 class="px-1 py-4">
                          <h2> {{total}} Ton</h2>
                      </v-flex> 
                    </v-layout>


        </v-card-text>

             <v-layout>
                <v-flex xs10 class="px-1">
                </v-flex>
                <v-flex xs2 class="px-1">
                    
                    <v-btn class="ma-2 white--text" color="main_green"  @click="saveAll()">
                        Guardar
                        <v-icon right>save</v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>  

      </v-card>
    </v-dialog>
  </div>
</template>

<script>


  import Vue from 'vue';  
  import Vuex from 'vuex'; 
  import { mapState } from 'vuex';  
  
  import { EventBus } from './../eventbus.js';

  import NewTraceabilityComponent  from './../components/NewTraceabilityComponent';

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
        unidad: '',

        total: 0,


        headers: [           
            { text: 'Destinatario', value: '' },
            { text: 'Tipo de Tratamiento', value: '' },
            { text: 'Tipo de Gestión', value: '' },
            { text: 'Cantidad', value: '' },
        ],

        residues: [],
        units: [],


        }
      },
    created(){
        var app = this;
        this.initialize();

        EventBus.$on('saveResidues', function(){  

            //alert('residues');
            app.refreshList();
        });
  

    },
      methods:{
        initialize(){
            var app = this;


            this.residue= this.waste_detail.waste;

            if(this.waste_detail.discrep_quantity>0){
              this.cantidad= this.waste_detail.discrep_quantity;
            }else {
              this.cantidad= this.waste_detail.quantity;
            }
            this.unidad= 'Toneladas';

            this.total=0;

            axios.get('/api/unit')
                .then(function (resp) {    
                    app.units = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });

          
        },
          NewDestiny (){

            if(this.total < this.waste_detail.quantity){ 

                  var ComponentReserv = Vue.extend(NewTraceabilityComponent)
                  var instance = new ComponentReserv({store: this.$store, propsData: {
                  waste_detail: this.waste_detail, remainder: this.waste_detail - this.total 
 
                  }});
                  instance.$mount();
                  this.$refs.container.replaceChild(instance.$el);
            } else {
                alert('Candidad completa, no es posible agregar un nuevo destinatario')
            }
          },

        refreshList(){
            this.residues.push(this.$store.getters.residue);
            this.total = this.total + parseFloat(this.$store.getters.residue.quantity);
        }, 

        saveAll(){
            alert("Se generarán declaraciones de salida a cada destinatario de la trazabilidad");

            var params ={
                declaration_origin: this.declaration_origin,
                waste_detail: this.residues,
            }

            axios.post('/api/declaration/savetraceability', params)
                .then(function (resp) {    
                    // EventBus.$emit('saveDeclaration', 'someValue');
                    alert('Se han creado declaraciones de trazabilidad')
                })
                .catch(function (resp) {
                    console.log(resp);
                });

            this.dialog = false;
        }  
      }
    }
  
</script>
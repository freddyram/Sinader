<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="1000"
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
          <v-toolbar-title>Registrar Discrepancia</v-toolbar-title>

        </v-toolbar>

        <v-card-text>

                    <v-layout>
                        <v-flex xs6 class="px-1">
                            <v-text-field v-model="this.residue_name" readonly=true label="Reciduo"></v-text-field>
                        </v-flex>
                        <v-flex xs3 class="px-1">
                            <v-text-field v-model="cantidad" :rules = "numberRule" type='number' label="Cantidad Recepcionada"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">

                            <v-select
                                :items="units"
                                v-model="this.unidad"
                                label="Unidad de Medida"
                                item-text="name" 
                                :rules = "generalRule"
                                v-on:change="changeUnit"
                                return-object
                            ></v-select> 

                        </v-flex>

                    </v-layout>

 

                    <v-layout>
                        <v-flex xs8 class="px-1">
                            <v-text-field v-model='comment'  label="Comentario"></v-text-field>
                        </v-flex>

                    </v-layout>


        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
            <v-btn class="ma-2 white--text"  color="main_green"  @click="saveDiscrepancy()">
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


  export default {
    props: {
        residue: Object,
    },
    data () {
      return {
        generalRule: [v => !!v || 'Campo requerido'],
        numberRule: [v => !!v || 'Campo requerido', v => v && /^[0-9]+$/.test(v) || 'Debe ser valor numérico',],

        checkbox:false,
        dialog: true,

        unidad:'',
        cantidad:0,
        residue_name:'',
        comment:'',
        units:[],
        }
      },
    created () {
        this.initialize();
    },

    methods: {
        initialize(){   

        this.residue_name = this.residue.waste;
        this.cantidad = this.residue.quantity;

        this.unidad = 'Toneladas';
          
        var app = this;

            axios.get('/api/unit')
                .then(function (resp) {    
                    app.units = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });
        },
        changeUnit(){
        }, 

        saveDiscrepancy(){
            var discrepancy = {
            waste_detail_id: this.residue.id,  
            disc_quantity : this.cantidad, 
            disc_unit     : this.unidad, 
            disc_comment  : this.comment,
            }

            axios.post('/api/waste_detail/updatediscrepancy', discrepancy)
                .then(function (resp) {    
                    EventBus.$emit('saveDiscrepancy', 'someValue');
                })
                .catch(function (resp) {
                    console.log(resp);
                });
            this.dialog = false;
        }
      },  
    }
  
</script>
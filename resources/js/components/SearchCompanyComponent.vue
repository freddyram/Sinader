<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="700"
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
          <v-toolbar-title>Buscar Empresa</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>

        <v-card-text>
     
            <v-layout>

                <v-flex xs12 class="px-2" style="overflow: auto">

                    <v-card class="py-4 px-2">
                        <v-card-title>
                            <v-text-field
                                v-model="rut"
                                label="Rut"
                                single-line
                                hide-details
                            ></v-text-field>
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="name"
                                label="Razon Social"
                                single-line
                                hide-details
                            ></v-text-field>  
                            <v-spacer></v-spacer>
                            <v-btn text icon color="grey lighten-2" @click='toSearch' >
                                <v-icon>search</v-icon>
                            </v-btn>

                        </v-card-title>
                      <v-data-table
                        :headers="headers"
                        :items="carriers"
                        v-model="selected"
                      >
                        <template v-slot:items="props">
                          <tr @click="selected_item(props.item)">
                            <td class="text-xs-right">{{ props.item.rut }}-{{props.item.digit}}</td>
                            <td class="text-xs-right">{{ props.item.name }}</td>
                        </tr>
                        </template>
                      </v-data-table>
                  

                    </v-card> 

                </v-flex>


            </v-layout>


        </v-card-text>
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

    data () {
      return {
        checkbox:false,
        dialog: true,

        rut:'',
        name:'',

        headers: [
            { text: 'Rut', value: '' },            
            { text: 'Razón Social', value: '' },
        ],

        carriers: [],
        }
      },

    created(){
        var app = this;
        this.initialize();
        // EventBus.$on('saveCarrier', function(){  
        //     alert("saveCarrier"); 
        //     app.refreshCarrier();
        // });
    },
    methods: {
        initialize(){
            var app = this;
            axios.get('/api/companies')
                .then(function (resp) {    
                    app.carriers = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });
        },


        toSearch(){
            var app = this;
  
            axios.get('/api/companies/search?rut='+this.rut+'&name='+this.name)
                .then(function (resp) {    
                    app.carriers = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                });            
        },

        selected_item(item){
            this.$store.commit('changeReceiver', item);

            EventBus.$emit('selectReceiver', 'someValue'); 
            this.dialog = false;
             
        }
    }




    }
  
</script>
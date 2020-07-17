<template>
  <div>
    <v-toolbar dark color="main_green">
      <v-toolbar-title>Periodos</v-toolbar-title>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px">
        <template v-slot:activator="{ on }">
          <v-btn color="secondary-green" dark class="mb-2" v-on="on">Nuevo Registro</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <!-- <span class="hidden-md-and-down">SINADER</span> -->
            <span  color="main_green" dark class="headline">{{ formTitle }}</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedItem.year" label="Año"></v-text-field>
                  <v-text-field v-model="editedItem.month" label="Mes"></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="ma-2 white--text"  color="main_green" @click="close">Cancelar</v-btn>
            <v-btn class="ma-2 white--text"  color="main_green" @click="toSave()">Grabar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="periods"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.year }}</td>
        <td class="text-xs-right">{{ props.item.month }}</td>
        <td  class="justify-center layout px-0">

            <v-btn icon  @click="edit_item(props.item)" >
                <v-icon>edit</v-icon>
            </v-btn>

            <v-btn  icon @click="delete_item(props.item)" >
                <v-icon>delete</v-icon>
            </v-btn>
        </td> 
      </template>
<!--       <template v-slot:no-data>
        <v-btn color="primary" @click="initialize">Eliminar</v-btn>
      </template> -->
    </v-data-table>
  </div>
</template>

<script>

  import Vue from 'vue';  
  import Vuex from 'vuex'; 
  import { mapState } from 'vuex'; 
  
  import { EventBus } from './../eventbus.js'

  export default {
    data: () => ({
      dialog: false,
      headers: [
        {
          text: 'Periodos',
          align: 'left',
          sortable: false,
          value: 'name'
        },
        { text: 'Año', value: 'year' },
        { text: 'Mes', value: 'month' },
        { text: 'Acciones', value: 'actions' },
      ],
      period: [],
      editedIndex: -1,
      editedItem: {
        year: 0,
        month: 0
      },
      defaultItem: {
        year: 0,
        month: 0
      }
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Nuevo Registro' : 'Editar Registro'
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      }
    },

    created () {
      this.initialize()
      this.getperiods();
    },

    methods: {
      initialize () {
        this.vehicles = []
      },

      getperiods(){
          var app = this;
          axios.get('/api/period')
              .then(function (resp) {
                  console.log(resp.data);    
                  app.period = resp.data;
              })
              .catch(function (resp) {
                  console.log(resp);
                  alert("Error period/index :" + resp);
              });
      },

      edit_item (item) {
        this.editedIndex = this.periods.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },


      delete_item(item){
        var app = this;
        const index = this.periods.indexOf(item)

        const periodid = this.periods[index]["id"];
        
        alert(this.periods[index]["id"]);


        if (confirm('¿Está seguro de eliminar el registro?')){
          
          axios.post('/api/period/delete/'+periodid)
              .then(function (resp) {  
                  app.periods.splice(index, 1)
              })
              .catch(function (resp) {
                  console.log(resp);
                  alert("Error vehicle/index :" + resp);
          });
          } else {
            alert("no hay nada que borrar")
          }
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      toSave() {

        var app = this;

        if (this.editedIndex > -1) {
          Object.assign(this.periods[this.editedIndex], this.editedItem)
        } 
        //else {
        //  this.vehicles.push(this.editedItem)
        //}
        
        var vehicle = this.editedItem;
         
        axios.post('/api/period/store', {vehicle: vehicle})
            .then(function (resp) {    
                alert('Registro Grabado Correctamente!!');
                //app.vehicles.push(resp.data);
                app.getperiods();
                EventBus.$emit('savePeriods', 'someValue');
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Error period/store :" + resp);
            });
        this.close()
      }
    }
  }
</script>
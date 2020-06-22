<template>
  <div>
    <v-toolbar dark color="main_green">
      <v-toolbar-title>Vehiculos</v-toolbar-title>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px" persistent="true">
        <template v-slot:activator="{ on }">
          <v-btn color="secondary-green" dark class="mb-2" v-on="on">Nuevo Registro</v-btn>
        </template>
        <v-card>
          <!--v-card-title-->
          <v-toolbar dark color="main_green">
            <v-btn icon dark @click="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
            <!-- <span class="hidden-md-and-down">SINADER</span> -->
            <v-toolbar-title>
              <span  color="main_green" dark class="headline">{{ formTitle }}</span>
            </v-toolbar-title>
          </v-toolbar>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 class="px-2">
                  <v-text-field v-model="editedItem.name" label="Nombre Vehículo"></v-text-field>
                  <v-text-field v-model="editedItem.plate" label="Patente"></v-text-field>
                  <v-text-field v-model="editedItem.carrier_id" label="Transportista"></v-text-field>
                  <!--v-text-field v-model="editedItem.vehicle_type_id" label="Tipo de Vehículo"-->
<!--                   <v-select
                      :items="vehicletypes"
                      v-model="id"
                      label="Tipo de Vehículo"
                      :rules = "generalRule"
                      item-text="name" 
                      v-on:change="changeVehicleType"
                      return-object
                  ></v-select> -->
                  <v-select
                    v-model="editedItem.vehicle_type_id"
                    :items="vehicletypes"
                    item-text="name"
                    item-value="id"
                    label="Tipo de Vehículo"
                    return-object
                    single-line
                  ></v-select>
                  </v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="ma-2 white--text"  color="main_green" @click="close">Cancelar
                <v-icon>close</v-icon>
            </v-btn>
            <v-btn class="ma-2 white--text"  color="main_green" @click="toSave()">Grabar
                <v-icon>save</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="vehicles"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td class="text-xs-right">{{ props.item.plate }}</td>
        <td class="text-xs-right">{{ props.item.carrier_id }}</td>
        <td class="text-xs-right">{{ props.item.vehicle_type_id }}</td>
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
      generalRule: [v => !!v || 'Campo requerido'],
      headers: [
        {
          text: 'Vehículo',
          align: 'left',
          sortable: false,
          value: 'name'
        },
        { text: 'Patente', value: 'plate' },
        { text: 'Transportista', value: 'carrier_id' },
        { text: 'Tipo de Vehículo', value: 'vehicle_type_id' },
        { text: 'Acciones', value: 'actions' },
      ],
      vehicles: [],
      vehicletypes: [],
      editedIndex: -1,
      editedItem: {
        name: '',
        plate: 0,
        carrier_id: 0,
        vehicle_type_id: 0
      },
      defaultItem: {
        name: '',
        plate: 0,
        carrier_id: 0,
        vehicle_type_id: 0
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
      this.getvehicles();
    },
    methods: {
      initialize () {
        this.vehicles = []
        var app = this;
        axios.get('/api/vehicletype')
          .then(function (resp) {    
            app.vehicletypes = resp.data;
//            alert(JSON.stringify(app.vehicletypes));
        })
          .catch(function (resp) {
            console.log(resp);
            alert("Error vehicletypes :" + resp);
        });
      },
      getvehicles(){
          var app = this;
          axios.get('/api/vehicle')
              .then(function (resp) {
                  console.log(resp.data);    
                  app.vehicles = resp.data;
              })
              .catch(function (resp) {
                  console.log(resp);
                  alert("Error vehicle/index :" + resp);
              });
      },
      changeVehicleType(vehicletype_selected){
          
          this.vehicle_type_id = vehicletype_selected.id;
          this.vehicle_type_name = vehicletype_selected.name;
      },
      edit_item (item) {
        this.editedIndex = this.vehicles.indexOf(item)
        this.editedItem = Object.assign({}, item)
  //      alert('Tipo de vehículo' + this.editedItem.vehicle_type_id)
        this.dialog = true
      },
      delete_item(item){
        var app = this;
        const index = this.vehicles.indexOf(item)
        const vehicleid = this.vehicles[index]["id"];
        
        alert(this.vehicles[index]["id"]);
        if (confirm('¿Está seguro de eliminar el registro?')){
          
          axios.post('/api/vehicle/delete/'+vehicleid)
              .then(function (resp) {  
                  app.vehicles.splice(index, 1)
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
          Object.assign(this.vehicles[this.editedIndex], this.editedItem)
        } 
        //else {
        //  this.vehicles.push(this.editedItem)
        //}
        
        var vehicle = this.editedItem;
         
        axios.post('/api/vehicle/store', {vehicle: vehicle})
            .then(function (resp) {    
                alert('Registro Grabado Correctamente!!');
                app.vehicles.push(resp.data);
                EventBus.$emit('saveVehicle', 'someValue');
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Error vehicle/store :" + resp);
            });
        this.close()
      }
    }
  }
</script>
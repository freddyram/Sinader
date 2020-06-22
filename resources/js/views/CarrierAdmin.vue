<template>
  <div>
    <v-toolbar dark color="main_green">
      <v-toolbar-title>Empresas Transportistas</v-toolbar-title>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px" persistent="true">
        <template v-slot:activator="{ on }">
          <v-btn color="secondary_green" dark class="mb-2" v-on="on">Nuevo Registro</v-btn>
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
                  <v-text-field v-model="editedItem.rut" label="rut"></v-text-field>
                  <v-text-field v-model="editedItem.dv" label="Digito Verificador"></v-text-field>
                  <v-text-field v-model="editedItem.name" label="Nombre"></v-text-field>
                  <v-text-field v-model="editedItem.address" label="Dirección">
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
            <v-btn class="ma-2 white--text"  color="main_green" @click="toSave()">Guardar
              <v-icon right>save</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="carriers"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.rut }}-{{ props.item.dv }}</td>
        <td class="text-xs-left">{{ props.item.name }}</td>
        <td class="text-xs-left">{{ props.item.address }}</td>
        <td class="justify-center layout px-0">

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
          text: 'Rut',
          align: 'left',
          sortable: false,
          value: 'rut'
        },
        { text: 'Nombre', value: 'name' },
        { text: 'Dirección', value: 'address' },
        { text: 'Acciones', value: 'actions' },
      ],
      carriers: [],
      editedIndex: -1,
      editedItem: {
        rut: 0,
        dv: '',
        name: '',
        address: ''
      },
      defaultItem: {
        rut: 0,
        dv: '',
        name: '',
        address: ''
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
      this.getcarriers();
    },
    methods: {
      initialize () {
        this.carriers = []
      },
      getcarriers(){
          var app = this;
          axios.get('/api/carrier')
              .then(function (resp) {
                  console.log(resp.data);    
                  app.carriers = resp.data;
              })
              .catch(function (resp) {
                  console.log(resp);
                  alert("Error carrier/index :" + resp);
              });
      },
      edit_item (item) {
        this.editedIndex = this.carriers.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },
      delete_item(item){
        var app = this;
        const index = this.carriers.indexOf(item)
        const carrierid = this.carriers[index]["id"];
        
        alert(this.carriers[index]["id"]);
        if (confirm('¿Está seguro de eliminar el registro?')){
          
          axios.post('/api/carrier/delete/'+carrierid)
              .then(function (resp) {  
                  app.carriers.splice(index, 1)
              })
              .catch(function (resp) {
                  console.log(resp);
                  alert("Error carrier/index :" + resp);
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
          Object.assign(this.carriers[this.editedIndex], this.editedItem)
        } 
        //else {
        //  this.carriers.push(this.editedItem)
        //}
        
        var carrier = this.editedItem;
         
        axios.post('/api/carrier/store', {carrier: carrier})
            .then(function (resp) {    
                alert('Registro Grabado Correctamente!!');
                app.carriers.push(resp.data);
                EventBus.$emit('savecarrier', 'someValue');
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Error carrier/store :" + resp);
            });
        this.close()
      }
    }
  }
</script>
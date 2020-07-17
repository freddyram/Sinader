<template>
  <div>
    <v-toolbar dark color="main_green">
      <v-toolbar-title>Capítulo LER</v-toolbar-title>
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
                  <v-text-field v-model="editedItem.chapter_number" label="Número de Capítulo"></v-text-field>
                  <v-textarea v-model="editedItem.name" label="Nombre"></v-textarea>
                  <v-text-field v-model="editedItem.active" label="Activo"></v-text-field>
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
      :items="lerchapters"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.chapter_number }}</td>
        <td class="text-xs-left">{{ props.item.name }}</td>
        <td class="text-xs-left">{{ props.item.active }}</td>
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
          text: 'Número de Capítulo',
          align: 'left',
          sortable: false,
          value: 'chapter_number'
        },
        { text: 'Nombre', value: 'name' },
        { text: 'Activo', value: 'active' },
        { text: 'Acciones', value: 'actions' },
      ],
      lerchapters: [],
      editedIndex: -1,
      editedItem: {
        chapter_number: '',
        name: '',
        active: 0
      },
      defaultItem: {
        chapter_number: '',
        name: '',
        active: 0
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
      this.getlerchapters();
    },
    methods: {
      initialize () {
        this.lerchapters = []
      },
      getlerchapters(){
          var app = this;
          axios.get('/api/lerchapter')
              .then(function (resp) {
                  console.log(resp.data);    
                  app.lerchapters = resp.data;
              })
              .catch(function (resp) {
                  console.log(resp);
                  alert("Error lerchapter/index :" + resp);
              });
      },
      edit_item (item) {
        this.editedIndex = this.lerchapters.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },
      delete_item(item){
        var app = this;
        const index = this.lerchapters.indexOf(item)
        const lerchapterid = this.lerchapters[index]["id"];
        
        alert(this.lerchapters[index]["id"]);
        if (confirm('¿Está seguro de eliminar el registro?')){
          
          axios.post('/api/lerchapter/delete/'+lerchapterid)
              .then(function (resp) {  
                  app.lerchapters.splice(index, 1)
              })
              .catch(function (resp) {
                  console.log(resp);
                  alert("Error lerchapter/index :" + resp);
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
          Object.assign(this.lerchapters[this.editedIndex], this.editedItem)
        } 
        //else {
        //  this.lerchapters.push(this.editedItem)
        //}
        
        var lerchapter = this.editedItem;
         
        axios.post('/api/lerchapter/store', {lerchapter: lerchapter})
            .then(function (resp) {    
                alert('Registro Grabado Correctamente!!');
                app.getlerchapters()
                //app.lerchapters.push(resp.data);
                EventBus.$emit('savelerchapter', 'someValue');
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Error lerchapter/store :" + resp);
            });
        this.close()
      }
    }
  }
</script>
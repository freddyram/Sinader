<template>

<v-layout row>
    <v-card>

    <v-layout row>
        <v-flex xs12 class='px-4'>

            <v-toolbar  color="main_green" dark>

              <v-toolbar-title>Generadores Industriales</v-toolbar-title>


            </v-toolbar>
        </v-flex>
    </v-layout>
    <v-layout row>
        <v-flex xs12 class='px-4'>
                  <v-card class="py-4 px-2">
                        <v-card-title>
                            <v-text-field
                                v-model="rut_search"
                                label="Rut"
                                single-line
                                hide-details
                            ></v-text-field>
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="establishment_search"
                                label="Establecimiento"
                                single-line
                                hide-details
                            ></v-text-field>  
                            <v-spacer></v-spacer>
                         
<!--                             <v-text-field
                                v-model="name"
                                label="Comuna"
                                single-line
                                hide-details
                            ></v-text-field> 
                            <v-spacer></v-spacer>  -->
                            <v-btn text icon color="grey lighten-2" @click='toSearch' >
                                <v-icon>search</v-icon>
                            </v-btn>

                            
                   </v-card-title>
                 </v-card>  


        </v-flex>
    </v-layout> 


    <v-layout row>
        <v-flex xs12 class='px-4'>
            <v-toolbar color="secondary_green" dark>
                <v-toolbar-title></v-toolbar-title>
               <v-spacer></v-spacer>
                <v-btn  class="ma-2 white--text" @click='' color="main_green">
                    Exportar Excel
                    <v-icon right>cloud_download</v-icon>
                </v-btn>
            </v-toolbar>
        </v-flex>
    </v-layout>


   <v-layout row>
        <v-flex xs12 class='px-4'>
            <v-data-table
              :headers="headers"
              :items="declarations"
              class="elevation-1"
              
            >
              <template v-slot:items="props">
                <td class="text-xs-right">{{ props.item.company.rut }} - {{ props.item.company.digit }}</td>
                <td class="text-xs-right">{{ props.item.company.name }}</td>
                <td class="text-xs-right">{{ props.item.id }}</td>
                <td class="text-xs-right">{{ props.item.name }}</td>
                <td class="text-xs-right">{{ props.item.commune.name }}</td>
                <td class="text-xs-right">{{ props.item.region.name }}</td>

                <td> 
                    <v-btn small @click="" color="secondary_green" dark>Desactivar
                        <v-icon>close</v-icon>
                    </v-btn>
<!--                     <v-btn small @click="" color="secondary_green" dark>Activar
                        <v-icon>check</v-icon>
                    </v-btn> -->

                </td>
              </template>
            </v-data-table>
        </v-flex>
    </v-layout>

    </v-card>
</v-layout>    
</template>

<script>

  import { mapState } from 'vuex';  
  import Vue from 'vue';  
  import { EventBus } from './../eventbus.js';

 
  import DeclarationComponent  from './../components/DeclarationComponent';


  export default {
    props: {
        declaration: Object
    },
    data: () => ({
        headers: [


            { text: 'rut', value: '' }, 
            { text: 'Empresa', value: '' }, 
            { text: 'Id', value: '' }, 
            { text: 'Establesimiento', value: '' },            
            { text: 'Comuna', value: '' },
            { text: 'Region', value: '' },
        
        ],
        declarations: [
        ],

        establishment_search:'',
        rut_search:'',

        }),
    

    created () {
        this.initialize();
        var app = this;

    },
    
    methods: {
        initialize(){
            this.getestablishment();
        },  

        getestablishment(){
            var app = this;
            axios.get('/api/establishment/all/' + 'GeneradorIndustrial')
                .then(function (resp) {    
                    app.declarations = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });
        },
        toSearch(){
            var app = this;
            var params = {type: 'GeneradorIndustrial',
                      establishment: this.establishment_search,
                      rut: this.rut_search,
                     }

                     alert(JSON.stringify(params));

            axios.post('/api/establishments/search', params)
                .then(function (resp) {    
                    app.declarations = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });
        },

        toNewDeclaration (declaration){

            var ComponentReserv = Vue.extend(DeclarationComponent)
            var instance = new ComponentReserv({store: this.$store, propsData: {
            declaration_edit: declaration,
            }});
            instance.$mount();
            this.$refs.container.innerHTML = "" 
            this.$refs.container.replaceChild(instance.$el);
        },

        toDelete(declaration){
            var app = this;
            axios.post('/api/declaration/delete/'+declaration.id)
                .then(function (resp) {  
                    app.getdecalrations();  
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });
        },
        
        enviar(declaration){
            var app = this;
            axios.post('/api/declaration/enviar/'+declaration.id)
                .then(function (resp) {  
                    app.getdecalrations();  
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });
        },

        toPdf(declaration){
            var app = this;
            axios.get('/api/declaration/pdf/'+declaration.id,
                {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/pdf'
                    },
                    responseType: "blob"
                }

                )
                .then(function (resp) {  
                    
                 var fileURL = window.URL.createObjectURL(new Blob([resp.data]));
                 var fileLink = document.createElement('a');
                 fileLink.href = fileURL;
                 fileLink.setAttribute('download', 'file.pdf');
                 document.body.appendChild(fileLink);

            
                 fileLink.click();

                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });




        }


    }
    }
</script>   
<template>

<v-layout row>
    <v-card>

    <v-layout row>
        <v-flex xs12 class='px-4'>

            <v-toolbar  color="main_green" dark>

              <v-toolbar-title v-if="this.$store.getters.type=='GeneradorIndustrial' || this.$store.getters.type=='GeneradorMunicipal'" >Reportes de Generación de Residuos no Peligrosos</v-toolbar-title>

              <v-toolbar-title v-if="this.$store.getters.type=='CentroAcopio' || this.$store.getters.type=='DestinatarioFinal'" >Reportes de Salida de Residuos no Peligrosos</v-toolbar-title>


            </v-toolbar>
        </v-flex>
    </v-layout>
    <v-layout row>
        <v-flex xs1>
            <p></p>
        </v-flex>
    </v-layout> 


    <v-layout row>
        <v-flex xs12 class='px-4'>
            <v-toolbar color="secondary_green" dark>
                <v-toolbar-title></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn  class="ma-2 white--text" @click='toNewDeclaration("")' color="main_green">
                    Nueva declaración
                    <v-icon right>add</v-icon>
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
                <td class="text-xs-right">{{ props.item.correlative }} - {{ props.item.correlative_dv }}</td>
                <td class="text-xs-right">{{ props.item.period }}</td>
                <td class="text-xs-right">{{ props.item.user }}</td>
                <td class="text-xs-right">{{ props.item.created_at }}</td>
                <td class="text-xs-right">{{ props.item.type }}</td>
                <td class="text-xs-right">{{ props.item.status }}</td>

                <v-btn  @click="toPdf(props.item)" >
                    Certificado        
                </v-btn>
                <!-- <td class="text-xs-right">{{ props.item.certificate }}</td> -->


                <td v-if="props.item.status=='CREADA'"> 
                    <v-btn icon  @click="toNewDeclaration(props.item)" color="white">
                        <v-icon>edit</v-icon>
                    </v-btn>
                </td>
                <td v-if="props.item.status=='CREADA' || props.item.status=='SINMOVIMIENTO' " >    
                     <v-btn icon  @click="toDelete(props.item)" color="white" >
                         <v-icon>delete</v-icon>
                     </v-btn>
                </td>  
                <td v-if="props.item.status=='CREADA'"> 
                    <v-btn   small @click="enviar(props.item)" color="secondary_green" dark>Enviar</v-btn>
                </td>   
                <td v-if="props.item.status!='CREADA'" > 
                    <v-btn  small @click="toNewDeclaration(props.item)" color="secondary_green" dark>Ver</v-btn>
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
  import { EventBus2 } from './../eventbus2.js';

 
  import DeclarationComponent  from './../components/DeclarationComponent';


  export default {
    props: {
        declaration: Object
    },
    data: () => ({
        headers: [


            { text: 'Folio', value: '' }, 
            { text: 'Ingresado Por', value: '' },            
            { text: 'Período', value: '' },
            { text: 'Fecha', value: '' },
            { text: 'Estado', value: '' },
            { text: 'Tipo', value: '' },
            { text: 'Certificado', value: '' },
        
        ],
        declarations: [
        ]

        }),
    

    created () {
        var app = this;
        EventBus2.$on('saveDeclaration', function(){   
            app.getdeclarations();
        });

        this.initialize();
    },
    
    methods: {
        initialize(){
            this.getdeclarations();
        },  

        getdeclarations(){
            var app = this;
            axios.get('/api/declarations')
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
                    app.getdeclarations();  
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
                    app.getdeclarations();  
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });

            alert("Grabar PDF");

            axios.get('/api/declaration/pdf/'+declaration.id,
                {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/pdf'
                    },
                    responseType: "blob"
                // })
                // then(function (resp) {  
                    
                // FileSaver.saveA(Blob([resp.data]), storage_path("certificado" + declaration.id + ".pdf"));


                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Error declarations/index :" + resp);
                });

                if (this.$store.getters.type=='GeneradorIndustrial')
                {
                    alert('Envío de Correo Generador Industrial')

                    axios.get('/api/mail/sendcertinddeclaration/'+declaration_id)
                        .then(function (resp) {    
                            alert(JSON.stringify(resp.data)); 
                            EventBus.$emit('CertIndDeclaration', 'someValue');               
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        });             
                }else{
                    alert('Envío de Correo Generador Municipal')

                    axios.get('/api/mail/sendcertmundeclaration/'+declaration_id)
                        .then(function (resp) {    
                            alert(JSON.stringify(resp.data)); 
                            EventBus.$emit('CertMunDeclaration', 'someValue');               
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        });  
                }
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

<template>
  <v-app id="inspire">

    <v-navigation-drawer
      :clipped="$vuetify.breakpoint.lgAndUp"
      v-model="drawer"
      fixed
      app
      class="side_bar_gray"
    >
      <v-list class="white--text"  dense>
        <template v-for="item in items">
          <v-layout
            v-if="item.heading"
            :key="item.heading"
            :to="item.link"
            row
            align-center
          >
            <v-flex xs4>
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-flex>

          </v-layout>
          <v-list-group
            v-else-if="item.children"
            v-model="item.model"
            :key="item.text"
          >
            <v-list-tile slot="activator" active-class="secondary_green--text">
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ item.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile
              v-for="(child, i) in item.children"

              :key="i"
              @click=""
            >
            </v-list-tile>
          </v-list-group>
          <v-list-tile  active-class="secondary_green--text" :to="item.link" v-else :key="item.text" @click="drawer = !drawer">

            <v-list-tile-content>
              <v-list-tile-title>
                {{ item.text }}
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-divider color="white"></v-divider>
        </template>
      </v-list>
    </v-navigation-drawer>



    <v-toolbar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      color="main_green"
      app
      fixed
      class="pl-0"
      style="padding-left: 0px"
    >
      <v-btn
        color="side_bar_gray"
        dark
        class="bold--text"
        style="position:relative; height: 100%"
        @click.stop="drawer = !drawer"
      >
        SINADER
      </v-btn>
      <v-toolbar-title class="ml-0 pl-3 white--text">
        <!-- <span class="hidden-md-and-down">SINADER</span> -->
        </v-toolbar-title>        
        <v-spacer></v-spacer>
      </v-toolbar-title>

        <p class='white--text'>Establecimiento: {{ this.$store.getters.establishment.name}}</p> 
        

      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon color="white">account_circle</v-icon>
      </v-btn>
    </v-toolbar>

    <v-content>
      <v-container fluid>
        <!-- COntenido -->
        <div class="container">
          <router-view></router-view>
        </div>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  import Vue from 'vue';
  import Vuex from 'vuex'; 
  import router  from './../routes';
  export default {
    data: () => ({
      dialog: false,
      drawer: true,
      step:"",
      establishment: null,
      naturalStates:[],
      items: [
      ]
    }),
    created () {
        this.initialize();
      
    },
    methods: {
        initialize() {

            var app = this;

            axios.get('/api/user')
                .then(function (resp) {
                   // alert(JSON.stringify(resp.data));  
                   app.$store.commit('changeUser', resp.data);
                })
                .catch(function (resp) {
                    console.log(resp);
                }); 

            axios.get('/api/company')
                .then(function (resp) {
                    // alert(JSON.stringify(resp.data));  
                   app.$store.commit('changeCompany', resp.data);
                })
                .catch(function (resp) {
                    console.log(resp);
                }); 

            axios.get('/api/establishment')
                .then(function (resp) {
                   // alert(JSON.stringify(resp.data));  
                   app.$store.commit('changeEstablishment', resp.data);
                   app.$store.commit('changeType', resp.data['type']);

                    if(app.$store.getters.type=='GeneradorIndustrial' || app.$store.getters.type=='GeneradorMunicipal'){
                        app.items= [
                            { icon: 'contacts', text: 'Bienvenida', link: '/welcome' },
                            { icon: 'declaration', text: 'Reporte Generación de Residuos', link: '/declarations' },
                               
                            { icon: 'wrap_text', text: 'Preguntas Frecuentes', link: '/'},
                            { icon: 'wrap_text', text: 'Tutoriales', link: '/'},
                        ]
                    }   

                    if(app.$store.getters.type=='CentroAcopio'){
                        app.items= [
                            { icon: 'contacts', text: 'Bienvenida', link: '/welcome' },
                            { icon: 'declaration', text: 'Reporte Recepción de Residuos', link: '/receive' },
                            { icon: 'declaration', text: 'Reporte Salida de Residuos', link: '/declarations' },
                                 
                            { icon: 'wrap_text', text: 'Preguntas Frecuentes', link: '/'},
                            { icon: 'wrap_text', text: 'Tutoriales', link: '/'},
                        ]
                    } 
                    if(app.$store.getters.type=='DestinatarioFinal'){
                        app.items= [
                            { icon: 'contacts', text: 'Bienvenida', link: '/welcome' },
                            { icon: 'declaration', text: 'Reporte Recepción de Residuos', link: '/receive' },
                            
                               
                            { icon: 'wrap_text', text: 'Preguntas Frecuentes', link: '/'},
                            { icon: 'wrap_text', text: 'Tutoriales', link: '/'},
                        ]
                    } 

                    if(app.$store.getters.type=='Administrador'){
                        app.items= [

                            { icon: 'contacts', text: 'Bienvenida', link: '/welcome' },
                            { icon: 'declaration', text: 'Solicitudes de Establecimiento', link: '/requisition_list' },
                            { icon: 'declaration', text: 'Declaraciones', link: '/declarationsadmin' },
                            { icon: 'declaration', text: 'Generadores Municipales', link: '/listadminmun' },
                            { icon: 'declaration', text: 'Generadores Industriales', link: '/listadminind' },
                            { icon: 'declaration', text: 'Destinatarios', link: '/listadmindf' },
                            { icon: 'declaration', text: 'I.R.A.R.', link: '/listadminca' },
                            { icon: 'declaration', text: 'Mantenedor de empresas transportistas', link: '/carrieradmin' },
                            { icon: 'declaration', text: 'Mantenedor de autorizaciones sanitarias transportes', link: '/' },
                            { icon: 'declaration', text: 'Mantenedor de transportes (patentes)', link: '/vehicleadmin' },
                            { icon: 'declaration', text: 'Mantenedor de códigos LER Capitulos', link: '/lerchapteradmin' },
                            { icon: 'declaration', text: 'Mantenedor de códigos LER Subcapitulos', link: '/lersubchapteradmin' },
                            { icon: 'declaration', text: 'Mantenedor de códigos LER Residuo', link: '/lerwasteadmin' },
                            { icon: 'declaration', text: 'Mantenedor de Periodos de declaración', link: '/declarationsperiodadmin' },
                            { icon: 'declaration', text: 'Reportes', link: '/' },

                        ]
                    } 




                })
                .catch(function (resp) {
                    console.log(resp);
                }); 


      }


    }
}
</script>
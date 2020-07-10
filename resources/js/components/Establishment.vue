<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="800"
    >

      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Datos del Establecimiento
        </v-card-title>

        <v-card-text>

                <v-layout>
                    <v-flex xs6 class="px-2">
                        <v-text-field readonly v-model="establishment.name" label="Establecimiento"></v-text-field> 
                    </v-flex>
                    <v-flex xs6 class="px-2">
                        <v-text-field readonly v-model="establishment.retc_code" label="RETC Id"></v-text-field> 
                    </v-flex>
                </v-layout>
                <v-layout>    
                    <v-flex xs3 class="px-2">
                        <v-text-field readonly v-model="establishment.street" label="Calle"></v-text-field> 
                    </v-flex>
                    <v-flex xs3 class="px-2">
                        <v-text-field readonly v-model="establishment.number" label="Numero"></v-text-field> 
                    </v-flex>
                    <v-flex xs3 class="px-2">
                        <v-text-field readonly v-model="establishment.commune.name" label="Comuna"></v-text-field> 
                    </v-flex>
                    <v-flex xs3 class="px-2">
                        <v-text-field readonly v-model="establishment.commune.region_id" label="Región"></v-text-field> 
                    </v-flex>
                </v-layout> 
                <v-layout>
                    <v-flex xs6 class="px-2">
                        <v-text-field readonly v-model="userEstablishment.user.name" label="Encargado"></v-text-field> 
                    </v-flex>
                    <v-flex xs6 class="px-2">
                        <v-text-field readonly v-model="userEstablishment.user.email" label="Email"></v-text-field> 
                    </v-flex>

                </v-layout> 

                <v-layout>
                    <v-flex xs8 class="px-2">
                        <v-text-field readonly v-model="establishment.company.name" label="Empresa"></v-text-field> 
                    </v-flex>
                    <v-flex xs2 class="px-2">
                        <v-text-field readonly v-model="establishment.company.rut" label="Rut"></v-text-field> 
                    </v-flex>
                    <v-flex xs1 >
                        <v-text-field readonly v-model="establishment.company.digit" label="dv"></v-text-field> 
                    </v-flex>
                </v-layout> 

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="main_green" 
            text
            @click="dialog = false"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  export default {
    props: {
        establishment: Object,
    },
    data () {
      return {
        dialog: true,
        userEstablishment:'',
      }
    },
    created () {
        this.initialize();
    },

    methods: {
        initialize () {
            var app = this;

            axios.get('/api/getuser/' + app.establishment.id)
                .then(function (resp) { 
                    app.userEstablishment  = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
   //                 alert("Error sources/refresh :" + resp);
                });

        },
    }
  }
</script>
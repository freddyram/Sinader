<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
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
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Crear Empresa de Transporte
        </v-card-title>

        <v-card-text>
            <v-layout>
                <v-flex  xs12 class="px-1">
                    <v-text-field  v-model="rut" label="Rut Empresa o Persona Natural"></v-text-field>
                </v-flex>
            </v-layout>    
                        
            <v-layout>
                <v-flex  xs12 class="px-1">
                    <v-text-field  v-model="name" label="Nombre Empresa o Persona Natural"></v-text-field>
                </v-flex>
            </v-layout>   

            <v-layout>
                <v-flex  xs12 class="px-1">
                    <v-text-field  v-model="plate" label="Patente"></v-text-field>
                </v-flex>
            </v-layout> 

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="main_green"
            class='white--text'
            @click="dialog = false"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>



  export default {
    data () {
      return {
        checkbox:false,
        dialog: true,
        rut:'',
        name:'',
        plate:'',
        }
      },

    created () {
        this.initialize();
    },

    methods: {
        initialize(){
            alert('Validación SII, pendiente')
        }
    }
  }

  
</script>
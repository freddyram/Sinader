<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="1000"
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
          Agregar Residuo
        </v-card-title>

        <v-card-text>
     
                    <v-layout>
                        <v-flex  xs3 class="px-1">

                            <v-select
                                :items="capitulos"
                                v-model="capitulo"
                                label="Capitulo"
                            ></v-select> 


                        </v-flex>
                        <v-flex  xs3 class="px-1">


                            <v-select
                                :items="subcapitulos"
                                v-model="subcapitulo"
                                label="Sub Capitulo"
                            ></v-select> 

                        </v-flex>
                        <v-flex xs3 class="px-1">
                            

                            <v-select
                                :items="residues"
                                v-model="residue"
                                label="Residuo"
                            ></v-select> 

                        </v-flex>
                        
                    </v-layout>
                    <v-layout>
                        <v-flex xs3 class="px-1">
                            <v-text-field :rules="numberRule" type='number' label="Cantidad"></v-text-field>
                        </v-flex>

                        <v-flex xs3 class="px-1">
                            <v-select
                                :items="units"
                                v-model="unidad"
                                label="Unidad de Medida"
                            ></v-select> 
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

        generalRule: [v => !!v || 'Campo requerido'],
        numberRule: [v => v && /^\d+(\.\d+)?$/.test(v) || 'Debe ser numérico',, (v) => v<=9999 || 'Valor excede el máximo'],

        checkbox:false,
        dialog: true,

        capitulos: ['capitulo prueba 1','capitulo prueba 2', 'capitulo prueba 3','capitulo prueba 4'],
        subcapitulos: ['subcapitulo prueba 1','subcapitulo prueba 2', 'subcapitulo prueba 3','subcapitulo prueba 4'],
        residues: ['Papel y cartón', 'residuo 2', 'residuo 3'],
        
        tipos_recolecciom: ['Punto Verde','Tipo Recolección 2','Tipo Recolección 3'],

        destinatarios: ['11111111-1 | SOREPA', '11111111-1 | SOREPA 2' ],
        establishments: ['4989 | Planta Colina','4990 | Planta 2'],

        processings:['Pretratamiento de papel y cartón', 'tipo 2', 'tipo3'],
        gestion:['Centro Acopio', 'Gestion 2', 'Gestion 3'],
        units:['Kg','Ton'],
        }
      }
    }
  
</script>
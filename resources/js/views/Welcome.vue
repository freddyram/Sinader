<style>
.redondeado {
  border-radius: 5px;
}

.welcome_disclaimer {
  text-align: justify;
  padding-left: 4%;
  padding-right: 4%;
}

.padded {
  padding-left: 4%;
  padding-right: 4%;
}

.welcome_icon {
  min-width: 50px;
  max-width: 150px;
}

.sitio_de_interes {
  min-width: 40px;
  max-width: 100px;
  margin: auto;
}
</style>

<template>
  <v-layout align-start justify-center row>
    <v-flex xs12 px-3>
      <v-card class="redondeado mb-4">
        <v-flex pt-4 pb-2 class="text-xs-center font-weight-medium">
          <h1>Bienvenido</h1>
          <h2>Sinader</h2>
        </v-flex>
        <v-card-text class="welcome_disclaimer">
          
        </v-card-text>
        <v-layout align-start justify-center row px-4>
          <v-flex xs6 pa-2>
            <h4>TUTORIALES</h4>
            <v-divider></v-divider>
            <v-layout justify-center row>
              <v-flex>
                <v-layout column>
                  <v-flex px-5 pt-3 pb-2>
                    <v-layout align-center justify-center>
                      <v-img src="https://i.ibb.co/0cjRgQG/tuts-icon.png" contain class="welcome_icon"></v-img>
                    </v-layout>
                  </v-flex>

                  <v-flex>
                    <v-card-text
                      style="text-align:justify"
                    >Videos Tutoriales. Enfocados en los pasos necesarios para obtener los informes operando los filtros correspondientes.</v-card-text>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-flex>

          <v-flex xs6 pa-2>
            <h4>F.A.Q.</h4>
            <v-divider></v-divider>
            <v-layout justify-center row>
              <v-flex>
                <v-layout column>
                  <v-flex px-5 pt-3 pb-2>
                    <v-layout align-center justify-center>
                      <v-img src="https://i.ibb.co/br1z2BF/faq-icon.png" contain class="welcome_icon"></v-img>
                    </v-layout>
                  </v-flex>

                  <v-flex>
                    <v-card-text
                      style="text-align:justify"
                    >Preguntas Frecuentes. Esta sección responde preguntas comunes además de explicar términos y conceptos.</v-card-text>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-flex>
        </v-layout>
      </v-card>

      <v-card class="pa-3 mt-3 redondeado">
        <h3 style="font-weight: 500">Sitios de Interés</h3>
        <v-divider class="pb-0"></v-divider>
        <v-layout align-center justify-center row wrap fill-height>
          <v-flex xs3>
            <v-card flat class="ma-3 pa-2">
              <v-img src="https://i.ibb.co/99gJtvV/Color-Min-Ambiente.png" class="sitio_de_interes"></v-img>
            </v-card>
          </v-flex>
          <v-flex xs3>
            <v-card flat class="ma-3 pa-2">
              <v-img src="https://i.ibb.co/JBcJ7XJ/sma-mma.png" class="sitio_de_interes"></v-img>
            </v-card>
          </v-flex>
          <v-flex xs3>
            <v-card flat class="ma-3 pa-2">
              <v-img src="https://i.ibb.co/D549TNn/min-energia.png" class="sitio_de_interes"></v-img>
            </v-card>
          </v-flex>
          <v-flex xs3>
            <v-card flat class="ma-3 pa-2">
              <v-img src="https://i.ibb.co/d71c9mm/descargaderiesgo.png" class="sitio_de_interes"></v-img>
            </v-card>
          </v-flex>
        </v-layout>
      </v-card>
    </v-flex>

    <v-flex xs5 px-3>
     
    </v-flex>
  </v-layout>
</template>

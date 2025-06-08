<script setup lang="ts">
//Pedir a API PHP que me mande los items guardados
//PHP Guarda cookie y luego crea / obtiene usuario y le manda items
import { ref } from 'vue'

//var loading = true
var data = ref(null)
//var error = nul
var lista = ref(null)
fetch('login.php', {
  method: "GET",
  headers: {
    'content-type': "application/json"
  }
}).then((htm) => htm.json()).then((json) => data.value = json).finally(() => {
  console.log(data.value);
});



fetch('bd_get.php', {
  method: "GET",
  headers: {
    'content-type': "application/json"
  }
}).then((htm) => htm.json()).then((json) => {

  lista.value = Array(json.length);
  for (const [i, j] of json.entries()) {
    lista.value[i] = {
      title: j.titulo,
      value: i,
      checked: j.terminado,
    };
  }
}).finally(() => {
  console.log(lista.value);
});

const sel = ref([]);

function update(element) {
  console.log(element.value);

}
</script>

<template>

  <header>
    <h1>TODO app</h1>

    <h1 v-if="data"> {{ data.nombre }} </h1>
    <form v-else action="login.php" method="POST">
      <label>Nombre</label>
      <input type="text" name="nombre" />
      <label>Contraseña</label>
      <input type="text" name="contra" />
      <v-btn type="submit" variant="outlined">Login</v-btn>
    </form>
  </header>

  <div class="cont">

    <div class="forma">
      <form action="post.php" method="POST">
        <label>Titulo</label>
        <input type="text" name="titulo" id="titulo" />
        <v-btn type="submit" variant="outlined">Enviar</v-btn>
      </form>
    </div>

    <div class="lista">
      <v-card class="mx-auto">

        <v-list v-if="lista" v-model:selected="sel" lines="three" select-strategy="leaf">
          <v-list-item v-for="item in lista" :title="item.title" :key="item.checked" :value="item.value">
            <template v-slot:prepend="{ isSelected, select }">
              <v-list-item-action start>
                <v-checkbox-btn :model-value="isSelected" @update:model-value="select"
                  @click="update(this)"></v-checkbox-btn>
              </v-list-item-action>
            </template>
          </v-list-item>

        </v-list>
      </v-card>
    </div>

  </div>
</template>

<style scoped>
header {
  line-height: 1.5;
}

.v-list-item--active {
  background-color: red;
}
</style>

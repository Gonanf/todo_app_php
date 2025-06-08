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

var sel = ref([]);


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
      value: j.id,
      checked: j.terminado,
    };
    if (j.terminado){
      sel.value = [...sel.value, j.id];
    }

  }
  console.log("SEL",sel.value);

}).finally(() => {
  console.log(lista.value);
});


function update(ref) {
  console.log(ref);
  //API -> Actualizar Item
  //Si Item ya esta en el valor provisto, dejar

  //ACTUALIZAR TODOS LOS ITEMS ME QUIERO MATAR

  //Deseleccionados
  for (const e of lista.value.filter(x => !ref.includes(x.value))){
    var fm = new FormData();
    fm.append('item',e.value);
    fetch('deselect_item.php', {
      method: "POST",
      headers: {"ContentType": 'application/json'},
      body: fm,
    });//.then((res) => res.json()).then(() => {sel.value = [...sel.value, e.value];});
  
  }

  //Seleccionados
  for (const e of ref){
    var fm = new FormData();
    fm.append('item',e);
    fetch('select_item.php', {
      method: "POST",
      headers: {"ContentType": 'application/json'},
      body: fm,
    });//.then((res) => res.json()).then(() => {sel.value = sel.value.filter(x => x != e)});
  }
  console.log(sel.value);
}

function del(ref){
  var fm = new FormData();
  fm.append('item',ref);
  fetch('del.php', {
      method: "POST",
      headers: {"ContentType": 'application/json'},
      body: fm,
    });
  console.log("REEE:",ref);
  lista.value = lista.value.filter(x => x.value != ref);
}
</script>

<template>
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">

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

        

        <v-list v-model:selected="sel" @update:selected="update"  lines="three" select-strategy="leaf">
          <v-list-item v-for="item in lista" :key="item.value" :title="item.title"
            :value="item.value">
            <template v-slot:prepend="{ isSelected, select }">
              <v-list-item-action start>
                <v-checkbox-btn :model-value="isSelected" @update:model-value="select"></v-checkbox-btn>
              </v-list-item-action>
            </template>

            <template v-slot:append>
              <v-list-item-action start>
                <v-btn icon="mdi-archive" @click="del(item.value)"></v-btn>
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

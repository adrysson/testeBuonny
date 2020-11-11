<template>
  <q-page class="q-ma-xl">
    <div class="row justify-center">
      <h1 class="text-h4">Pedidos</h1>
    </div>
    <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
      <div class="row justify-center">
        <q-select
          outlined
          v-model="model"
          :options="options"
          label="Clientes"
          class="q-ma-sm"
        />
        <q-input filled type="number" v-model="name" label="Valor" class="q-ma-sm" />
        <q-input filled type="number" v-model="name" label="Até" class="q-ma-sm" />
      </div>
      <div class="row justify-center">
        <q-btn label="Pesquisar" type="submit" color="primary" />
        <q-btn
          label="Limpar"
          type="reset"
          color="primary"
          flat
          class="q-ml-sm"
        />
      </div>
    </q-form>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from '@vue/composition-api'

export default defineComponent({
  name: 'PageIndex',
  data () {
    return {
      clientes: []
    }
  },
  mounted () {
  }
})
</script>

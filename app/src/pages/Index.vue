<template>
  <q-page class="q-ma-xl">
    <div class="row justify-center">
      <h1 class="text-h4">Pedidos</h1>
    </div>
    <q-form
      @submit="onSubmit"
      @reset="onReset"
      class="q-gutter-md"
    >
      <q-select
        outlined
        v-model="form.cliente_id"
        :options="options"
        label="Clientes"
        class="q-ma-sm"
      />
      <div class="flex flex-center">
        <q-input filled type="number" v-model="form.valor_min" label="Valor" class="q-ma-sm" />
        <q-input filled type="number" v-model="form.valor_max" label="Até" class="q-ma-sm" />
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

<script>
export default {
  name: 'PageIndex',
  data () {
    return {
      form: {
        cliente_id: '',
        valor_min: '',
        valor_max: ''
      },
      clientes: [],
      pedidos: [],
      formSubmit: false
    }
  },
  computed: {
    options () {
      return this.clientes.map((cliente) => {
        return {
          value: cliente.id,
          label: cliente.nome
        }
      })
    }
  },
  async mounted () {
    const response = await this.$axios.get('/clientes')
    this.clientes = response.data.clientes
  },
  methods: {
    async onSubmit () {
      const queryParams = this.getQueryParams()
      const response = await this.$axios.get(`/pedidos${queryParams}`)
      this.pedidos = response.data.pedidos
      this.formSubmit = true
    },
    onReset () {
      Object.keys(this.form).forEach((input) => {
        this.form[input] = ''
      })
    },
    getQueryParams () {
      let queryParams = ''
      Object.keys(this.form).forEach((input, key) => {
        const prefix = key !== 0 ? '&' : '?'
        const value = this.form[input].value || ''
        queryParams += `${prefix}${input}=${value}`
      })
      return queryParams
    }
  }
}
</script>

<template>
  <q-page class="q-ma-xl">
    <div class="row justify-center">
      <h1 class="text-h4">Adicionar pedido</h1>
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
        label="Cliente"
        class="q-ma-sm"
        :rules="[required]"
      />
      <div class="row justify-center">
        <q-btn label="Salvar" type="submit" color="primary" />
        <q-btn
          :to="{ name: 'index-pedido' }"
          label="Voltar"
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
        cliente_id: {
          value: '',
          label: ''
        }
      },
      clientes: []
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
    },
    formData () {
      return {
        cliente_id: this.form.cliente_id.value
      }
    }
  },
  async mounted () {
    const response = await this.$axios.get('/clientes')
    this.clientes = response.data.clientes
  },
  methods: {
    async onSubmit () {
      const response = await this.$axios.post('/pedidos', this.formData)
      this.$q.notify({
        position: 'top',
        message: response.data,
        color: 'positive'
      })
      this.$router.push({ name: 'index-pedido' })
    },
    onReset () {
      Object.keys(this.form).forEach((input) => {
        this.form[input] = ''
      })
    },
    required (option) {
      if (!option.value || option.value === '') {
        return 'Selecione uma opção'
      }
    }
  }
}
</script>

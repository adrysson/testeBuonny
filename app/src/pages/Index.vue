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
    <div class="row justify-center q-mt-lg">
      <div v-if="formSubmit" class="flex row column items-end">
        <q-btn label="Adicionar" :to="{name: 'add-pedidos'}" class="q-mb-md" color="primary" />
        <q-table
          :data="dataTable"
          :columns="columns"
          row-key="name"
        >
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props">
                {{ props.row.id }}
              </q-td>
              <q-td key="cliente" :props="props">
                {{ props.row.cliente }}
              </q-td>
              <q-td key="valor_total" :props="props">
                {{ props.row.valor_total }}
              </q-td>
              <q-td key="acoes" :props="props">
                <q-btn label="Editar" class="q-mx-xs" color="secondary" />
                <q-btn label="Excluir" class="q-mx-xs" color="negative" />
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
      <span v-else>
        Realize uma pesquisa para visualizar os pedidos
      </span>
    </div>
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
          label: 'Todos'
        },
        valor_min: '',
        valor_max: ''
      },
      clientes: [],
      pedidos: [],
      formSubmit: false,
      columns: [
        {
          name: 'id',
          required: true,
          label: 'ID',
          align: 'left',
          field: 'id',
          sortable: true
        },
        { name: 'cliente', align: 'center', label: 'Cliente', field: 'cliente', sortable: true },
        { name: 'valor_total', label: 'Valor total', field: 'valor_total', sortable: true },
        { name: 'acoes', label: 'Ações', field: 'acoes' }
      ]
    }
  },
  computed: {
    options () {
      const options = [{
        value: '',
        label: 'Todos'
      }]
      const clientes = this.clientes.map((cliente) => {
        return {
          value: cliente.id,
          label: cliente.nome
        }
      })
      return options.concat(clientes)
    },
    dataTable () {
      return this.pedidos.map((pedido) => {
        return {
          id: pedido.id,
          cliente: pedido.cliente.nome,
          valor_total: `R$ ${this.formatPrice(pedido.preco_total)}`
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
        const value = this.getValueInput(this.form[input])
        queryParams += `${prefix}${input}=${value}`
      })
      return queryParams
    },
    getValueInput (formInput) {
      if (typeof formInput.value === 'undefined') {
        return formInput || ''
      }
      return formInput.value
    },
    formatPrice (value) {
      const val = (value / 1).toFixed(2).replace('.', ',')
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
    }
  }
}
</script>

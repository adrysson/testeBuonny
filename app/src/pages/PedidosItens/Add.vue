<template>
  <q-page class="q-ma-xl">
    <div class="row justify-center">
      <h1 class="text-h4">Adicionar item ao pedido</h1>
    </div>
    <div class="row justify-center q-mt-lg">
      <div class="flex row column">
        <q-form
          @submit="onSubmit"
          @reset="onReset"
          class="q-gutter-md"
        >
          <q-select
            outlined
            v-model="form.produto_id"
            :options="options"
            label="Produto"
            class="q-ma-sm"
            :rules="[required]"
            lazy-rules
          />
          <q-input filled readonly type="text" v-model="valorFormatado" label="Valor" class="q-ma-sm" />
          <q-input filled type="number" v-model="form.quantidade" label="Quantidade" class="q-ma-sm" :rules="[val => !!val || 'Este campo é obrigatório']" lazy-rules />
          <q-input filled readonly type="text" v-model="totalFormatado" label="Total" class="q-ma-sm" />
          <div class="row justify-center">
            <q-btn label="Salvar" type="submit" color="primary" />
            <q-btn
              :to="{ name: 'edit-pedido', params: { id: $route.params.id } }"
              label="Voltar"
              type="reset"
              color="primary"
              flat
              class="q-ml-sm"
            />
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script>
export default {
  name: 'PedidosItensAdd',
  data () {
    return {
      form: {
        produto_id: {
          value: '',
          label: 'Selecione um produto'
        },
        quantidade: 0
      },
      pedido: null,
      produtos: []
    }
  },
  computed: {
    options () {
      return this.produtos.map((produto) => {
        return {
          value: produto.id,
          label: produto.descricao
        }
      })
    },
    produtoSelecionado () {
      return this.produtos.find((produto) => {
        return produto.id === this.form.produto_id.value
      })
    },
    valor () {
      return this.produtoSelecionado?.preco || 0
    },
    valorFormatado () {
      return `R$ ${this.formatPrice(this.valor)}`
    },
    total () {
      return this.valor * this.form.quantidade
    },
    totalFormatado () {
      return `R$ ${this.formatPrice(this.total)}`
    },
    formData () {
      return {
        pedido_id: this.$route.params.id,
        produto_id: this.form.produto_id.value,
        quantidade: this.form.quantidade
      }
    }
  },
  async mounted () {
    await this.getProdutos()
  },
  methods: {
    async getProdutos () {
      const response = await this.$axios.get('/produtos')
      this.produtos = response.data.produtos
    },
    async onSubmit () {
      const response = await this.$axios.post('/pedidos-itens', this.formData)
      this.$q.notify({
        position: 'top',
        message: response.data,
        color: 'positive'
      })
      this.$router.push({ name: 'edit-pedido', params: { id: this.$route.params.id } })
    },
    onReset () {
      Object.keys(this.form).forEach((input) => {
        this.form[input] = ''
      })
    },
    required (option) {
      if (!option.value || option.value === '') {
        return 'Este campo é obrigatório'
      }
    },
    formatPrice (value) {
      const val = (value / 1).toFixed(2).replace('.', ',')
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
    }
  }
}
</script>

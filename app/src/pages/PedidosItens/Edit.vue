<template>
  <q-page class="q-ma-xl">
    <div class="row justify-center">
      <h1 class="text-h4">Editar item do pedido</h1>
    </div>
    <div class="row justify-center q-mt-lg">
      <div class="flex row column">
        <q-form
          @submit="onSubmit"
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
  name: 'PedidosItensEdit',
  data () {
    return {
      form: {
        produto_id: {
          value: '',
          label: 'Selecione um produto'
        },
        quantidade: 0
      },
      pedidoItem: null,
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
        produto_id: this.form.produto_id.value,
        quantidade: parseInt(this.form.quantidade)
      }
    }
  },
  async mounted () {
    await this.getProdutos()
  },
  watch: {
    pedidoItem (value) {
      const produtoSelecionado = this.produtos.find((produto) => {
        return produto.id === value.produto_id
      })
      this.form.produto_id = {
        value: produtoSelecionado.id,
        label: produtoSelecionado.descricao
      }
      this.form.quantidade = value.quantidade
    }
  },
  methods: {
    async getProdutos () {
      const responseProdutos = await this.$axios.get('/produtos')
      this.produtos = responseProdutos.data.produtos

      const responsePedidoItem = await this.$axios.get(`/pedidos-itens/${this.$route.params.id}`)
      this.pedidoItem = responsePedidoItem.data.item
    },
    async onSubmit () {
      const response = await this.$axios.put(`/pedidos-itens/${this.$route.params.id}`, this.formData)
      this.$q.notify({
        position: 'top',
        message: response.data,
        color: 'positive'
      })
      this.$router.push({ name: 'edit-pedido', params: { id: this.pedidoItem.pedido_id } })
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

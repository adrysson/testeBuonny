<template>
  <q-page class="q-ma-xl" v-if="pedido">
    <div class="row justify-center">
      <h1 class="text-h4">Editar pedido</h1>
    </div>
    <div class="row justify-center q-mt-lg">
      <div class="flex row column">
        Cliente: {{ pedido.cliente.nome }}
        <div class="items-end">
          <q-btn label="Adicionar" :to="{name: 'add-item-pedido', params:{id: pedido.id}}" class="float-right q-mb-md" color="primary" />
        </div>
        <div v-if="pedido.produtos.length">
          <q-table
            title="Produtos"
            :data="dataTable"
            :columns="columns"
            row-key="name"
          >
            <template v-slot:body="props">
              <q-tr :props="props">
                <q-td key="id" :props="props">
                  {{ props.row.id }}
                </q-td>
                <q-td key="produto" :props="props">
                  {{ props.row.produto }}
                </q-td>
                <q-td key="preco" :props="props">
                  {{ props.row.preco }}
                </q-td>
                <q-td key="quantidade" :props="props">
                  {{ props.row.quantidade }}
                </q-td>
                <q-td key="valor_total" :props="props">
                  {{ props.row.valor_total }}
                </q-td>
                <q-td key="acoes" :props="props">
                  <q-btn label="Editar" class="q-mx-xs" color="secondary" :to="{name: 'edit-item-pedido', params:{ id: props.row.itemId }}" />
                  <q-btn label="Excluir" class="q-mx-xs" color="negative" @click="removeItem(props.row.itemId)" />
                </q-td>
              </q-tr>
            </template>
          </q-table>
          <div>
            Total do pedido: {{ formatPrice(pedido.preco_total) }}
          </div>
        </div>
        <span v-else>
          Este cliente não possui pedidos cadastrados
        </span>
      </div>
    </div>
    <div class="row justify-center">
      <q-btn
        :to="{ name: 'index-pedido' }"
        label="Voltar"
        type="reset"
        color="primary"
        flat
        class="q-ml-sm"
      />
    </div>
  </q-page>
</template>

<script>
export default {
  name: 'PedidosEdit',
  data () {
    return {
      form: {
        cliente_id: {
          value: '',
          label: ''
        }
      },
      columns: [
        {
          name: 'id',
          required: true,
          label: 'ID',
          align: 'left',
          field: 'id',
          sortable: true
        },
        { name: 'produto', align: 'center', label: 'Produto', field: 'produto', sortable: true },
        { name: 'preco', label: 'Valor unitário', field: 'preco', sortable: true },
        { name: 'quantidade', label: 'Qtde', field: 'quantidade', sortable: true },
        { name: 'valor_total', label: 'Valor Total', field: 'valor_total', sortable: true },
        { name: 'acoes', label: 'Ações', field: 'acoes' }
      ],
      pedido: null
    }
  },
  computed: {
    dataTable () {
      return this.pedido.produtos.map((produto) => {
        return {
          id: produto.id,
          itemId: produto._joinData.id,
          produto: produto.descricao,
          preco: `R$ ${this.formatPrice(produto.preco)}`,
          quantidade: produto._joinData.quantidade,
          valor_total: `R$ ${this.formatPrice(produto.preco * produto._joinData.quantidade)}`
        }
      })
    }
  },
  async mounted () {
    await this.getPedido()
  },
  methods: {
    async getPedido () {
      const response = await this.$axios.get(`/pedidos/${this.$route.params.id}`)
      this.pedido = response.data.pedido
    },
    formatPrice (value) {
      const val = (value / 1).toFixed(2).replace('.', ',')
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
    },
    async removeItem (id) {
      if (confirm('Você tem certeza que deseja excluir o item do pedido?')) {
        const response = await this.$axios.delete(`/pedidos-itens/${id}`)
        this.$q.notify({
          position: 'top',
          message: response.data || 'Item do pedido excluído com sucesso',
          color: 'warning'
        })
        this.getPedido()
      }
    }
  }
}
</script>

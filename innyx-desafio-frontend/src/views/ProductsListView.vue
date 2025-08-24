<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/api';
import Spinner from '@/components/Spinner.vue';

const router = useRouter();
const produtos = ref<any[]>([]);
const loading = ref(true);
const search = ref('');
const page = ref(1);
const totalItems = ref(0);
const itemsPerPage = ref(10);

async function fetchProducts() {
  loading.value = true;
  try {
    const response = await api.get('/produtos', {
      params: { page: page.value, busca: search.value }
    });
    produtos.value = response.data.data;
    totalItems.value = response.data.total;
    itemsPerPage.value = response.data.per_page;
  } catch (error) {
    console.error("Erro ao buscar produtos:", error);
  } finally {
    loading.value = false;
  }
}

function editProduct(id: number) {
  router.push({ name: 'product-edit', params: { id } });
}

async function deleteProduct(id: number) {
  if (confirm('Tem certeza que deseja excluir este produto?')) {
    try {
      await api.delete(`/produtos/${id}`);
      fetchProducts();
    } catch (error) {
      console.error("Erro ao excluir produto:", error);
    }
  }
}

onMounted(fetchProducts);
watch(page, fetchProducts);
watch(search, () => {
    page.value = 1;
    fetchProducts();
});
</script>

<template>
  <v-container>
    <v-card class="elevation-2">
      <v-card-title class="d-flex align-center flex-wrap">
        <span class="text-h5">Produtos</span>
        <v-spacer></v-spacer>
        <v-responsive max-width="300" class="mr-2 my-2">
          <v-text-field
            v-model="search"
            density="compact"
            variant="solo-filled"
            prepend-inner-icon="mdi-magnify"
            label="Buscar por nome ou descrição"
            single-line
            hide-details
            flat
          ></v-text-field>
        </v-responsive>
        <v-btn color="secondary" @click="router.push({ name: 'product-new' })" class="my-2" variant="elevated">
          <v-icon start>mdi-plus</v-icon>
          Adicionar Produto
        </v-btn>
      </v-card-title>
      <v-divider></v-divider>
      <div v-if="loading" class="text-center pa-12">
        <Spinner />
      </div>
      <v-card-text v-else-if="produtos.length === 0">
        <v-alert type="info" variant="tonal">
          Nenhum produto encontrado. Crie um novo produto para começar.
        </v-alert>
      </v-card-text>
      <v-table v-else>
        <thead>
          <tr>
            <th class="text-left font-weight-bold">Nome</th>
            <th class="text-left font-weight-bold">Preço</th>
            <th class="text-left font-weight-bold">Data de Validade</th>
            <th class="text-center font-weight-bold">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in produtos" :key="item.id" class="table-row">
            <td>{{ item.nome }}</td>
            <td>R$ {{ item.preco.toFixed(2).replace('.', ',') }}</td>
            <td>{{ new Date(item.data_validade + 'T00:00:00-03:00').toLocaleDateString('pt-BR') }}</td>
            <td class="text-center">
                <v-tooltip text="Editar Produto">
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" icon variant="text" size="small" @click="editProduct(item.id)">
                            <v-icon color="primary">mdi-pencil</v-icon>
                        </v-btn>
                    </template>
                </v-tooltip>
                <v-tooltip text="Excluir Produto">
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" icon variant="text" size="small" @click="deleteProduct(item.id)">
                            <v-icon color="error">mdi-delete</v-icon>
                        </v-btn>
                    </template>
                </v-tooltip>
            </td>
          </tr>
        </tbody>
      </v-table>
      <v-divider></v-divider>
      <v-pagination
        v-if="produtos.length > 0"
        v-model="page"
        :length="Math.ceil(totalItems / itemsPerPage)"
        :total-visible="5"
        class="my-4"
        active-color="primary"
      ></v-pagination>
    </v-card>
  </v-container>
</template>

<style scoped>
.table-row:hover {
  background-color: #f5f5f5;
}
</style>
<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/services/api';

const route = useRoute();
const router = useRouter();

const produto = ref({
  nome: '',
  descricao: '',
  preco: null,
  data_validade: '',
  categoria_id: null
});
const imagemFile = ref<File | null>(null);
const categorias = ref([]);
const loading = ref(false);
const errors = ref<any>({});

const productId = computed(() => route.params.id);
const isEditing = computed(() => !!productId.value);
const pageTitle = computed(() => isEditing.value ? 'Editar Produto' : 'Novo Produto');

async function fetchCategorias() {
    try {
        const response = await api.get('/categorias');
        categorias.value = response.data;
    } catch (error) {
        console.error("Erro ao buscar categorias", error);
    }
}

async function fetchProduct() {
  if (isEditing.value) {
    loading.value = true;
    try {
      const response = await api.get(`/produtos/${productId.value}`);
      response.data.data_validade = response.data.data_validade.split('T')[0];
      produto.value = response.data;
    } catch (error) {
      console.error("Erro ao buscar produto:", error);
    } finally {
      loading.value = false;
    }
  }
}

function handleFileUpload(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        imagemFile.value = target.files[0];
    }
}

async function handleSubmit() {
    loading.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('nome', produto.value.nome || '');
    formData.append('descricao', produto.value.descricao || '');
    formData.append('preco', produto.value.preco?.toString() || '');
    formData.append('data_validade', produto.value.data_validade || '');
    formData.append('categoria_id', produto.value.categoria_id?.toString() || '');
    if (imagemFile.value) {
        formData.append('imagem', imagemFile.value);
    }
    if (isEditing.value) {
        formData.append('_method', 'PUT');
    }

    try {
        const url = isEditing.value ? `/produtos/${productId.value}` : '/produtos';
        await api.post(url, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        router.push({ name: 'products' });
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error("Erro ao salvar produto:", error);
        }
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchCategorias();
    fetchProduct();
});
</script>

<template>
  <v-container>
    <v-card class="elevation-2">
      <v-card-title class="text-h5">{{ pageTitle }}</v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-form @submit.prevent="handleSubmit">
          <v-text-field label="Nome do Produto" v-model="produto.nome" :error-messages="errors.nome" variant="outlined" class="mb-2"></v-text-field>
          <v-textarea label="Descrição" v-model="produto.descricao" :error-messages="errors.descricao" variant="outlined" class="mb-2"></v-textarea>
          <v-text-field label="Preço" v-model="produto.preco" type="number" prefix="R$" :error-messages="errors.preco" variant="outlined" class="mb-2"></v-text-field>
          <v-text-field label="Data de Validade" v-model="produto.data_validade" type="date" :error-messages="errors.data_validade" variant="outlined" class="mb-2"></v-text-field>
          <v-select label="Categoria" :items="categorias" item-title="nome" item-value="id" v-model="produto.categoria_id" :error-messages="errors.categoria_id" variant="outlined" class="mb-2"></v-select>
          <v-file-input label="Imagem do Produto" @change="handleFileUpload" :error-messages="errors.imagem" variant="outlined" clearable></v-file-input>

          <v-divider class="my-4"></v-divider>
          <div class="d-flex justify-end">
            <v-btn variant="text" @click="router.back()" class="mr-4">
              Cancelar
            </v-btn>
            <v-btn type="submit" color="secondary" variant="elevated" :loading="loading" size="large">
              <v-icon start>mdi-content-save</v-icon>
              Salvar
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>
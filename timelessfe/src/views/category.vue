<script setup>
import { ref, onMounted } from "vue";
import api from "../api";

const products = ref([]);
const categories = ref([]);
const isLoading = ref(true);
const selectedCategory = ref("");
const filteredProducts = ref([]);

const fetchCategories = async () => {
  try {
    const response = await api.get("/api/categories");
    categories.value = response.data.data || response.data || [];
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
};

const fetchDataProducts = async () => {
  try {
    const response = await api.get("/api/products");
    products.value = response.data.data || response.data || [];
    filteredProducts.value = products.value;
  } catch (error) {
    console.error("Error fetching products:", error);
  } finally {
    isLoading.value = false;
  }
};

const filterProducts = () => {
  if (selectedCategory.value) {
    filteredProducts.value = products.value.filter((product) =>
      product.categories.some(
        (category) => category.id === parseInt(selectedCategory.value)
      )
    );
  } else {
    filteredProducts.value = products.value;
  }
};

onMounted(() => {
  fetchCategories();
  fetchDataProducts();
});
</script>

<template>
  <div class="container">
    <header class="header">
      <h1>Products by Category</h1>
    </header>

    <main>
      <!-- Category Dropdown -->
      <div class="filter-section">
        <label for="categories">Filter by Category:</label>
        <select
          id="categories"
          v-model="selectedCategory"
          @change="filterProducts"
        >
          <option value="">All Categories</option>
          <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
      </div>

      <!-- Product Grid -->
      <div v-if="isLoading" class="loading">Loading...</div>

      <div v-else-if="filteredProducts.length === 0" class="no-products">
        No products found.
      </div>

      <div v-else class="product-grid">
        <div
          v-for="product in filteredProducts"
          :key="product.id"
          class="product-card"
        >
          <img
            :src="product.image_url"
            alt="Product Image"
            class="product-image"
          />
          <div class="product-details">
            <h3>{{ product.name }}</h3>
            <p class="brand">{{ product.brand }}</p>
            <p class="price">${{ product.price }}</p>
            <p class="description">{{ product.description }}</p>
          </div>
          <button class="add-to-cart">Add to Cart</button>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
.container {
  font-family: Arial, sans-serif;
  padding: 20px;
  max-width: 1200px;
  margin-top: 80px;
}

.header {
  text-align: center;
  margin-bottom: 20px;
}

.filter-section {
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.filter-section select {
  padding: 5px 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.loading,
.no-products {
  text-align: center;
  color: gray;
  font-size: 1.2em;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

.product-card {
  border: 1px solid #ddd;
  border-radius: 5px;
  overflow: hidden;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.product-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.product-details {
  padding: 10px;
}

.product-details h3 {
  font-size: 1.1em;
  margin-bottom: 5px;
}

.product-details .brand {
  color: gray;
  font-size: 0.9em;
}

.product-details .price {
  color: green;
  font-weight: bold;
  margin-top: 10px;
}

.product-details .description {
  font-size: 0.9em;
  margin-top: 5px;
}

.add-to-cart {
  display: block;
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 0 0 5px 5px;
  text-align: center;
  font-size: 1em;
  cursor: pointer;
}

.add-to-cart:hover {
  background-color: #0056b3;
}
</style>

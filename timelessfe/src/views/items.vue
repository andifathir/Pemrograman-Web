<script setup>
  // Import necessary Vue composition API functions
  import { ref, onMounted } from 'vue';
  import api from '../api'; // Assuming 'api' is a custom axios instance
  
  // Import Navbar and Footer components
  import Navbar from '../components/Navbar.vue';
  import Footer from '../components/Footer.vue';

  // Define reactive variables to store product data and loading state
  const products = ref([]);
  const isLoading = ref(true);  // Track loading state

  // Method to fetch product data
  const fetchDataProducts = async () => {
    try {
      const response = await api.get('/api/products');
      
      // Log the entire response to see the structure
      console.log(response.data);  // Check if response.data contains the products

      // Update the products array
      products.value = response.data.data || response.data || [];
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      // Set loading to false once data is fetched or if there's an error
      isLoading.value = false;
    }
  };

  // Run the fetchDataProducts method when the component is mounted
  onMounted(() => {
    fetchDataProducts();
  });
</script>

<template>
  <!-- Items Section -->
  <section class="items" id="ITEMS">
    <div class="items-content">
      <h1>All Watch Products</h1>

      <!-- Loading state -->
      <div v-if="isLoading" class="loading-message">
        <p>Loading products...</p>
      </div>

      <!-- No products state -->
      <div v-if="!isLoading && products.length === 0" class="no-products-message">
        <p>No products available.</p>
      </div>

      <!-- Products list -->
      <div v-if="!isLoading && products.length > 0" class="items-container">
        <div 
          v-for="(product, index) in products" 
          :key="product.id || index" 
          class="product-card"
        >
          <div class="product-info">
            <!-- Display product image -->
            <div class="product-image">
              <img
                :src="product.image_url || 'https://via.placeholder.com/300x300?text=No+Image+Available'"
                :alt="product.name || 'Product Image'"
                class="product-img"
              />
            </div>

            <!-- Product Info -->
            <h2 class="product-name">{{ product.name }}</h2>
            
            <!-- Safely format the price, ensure it's a number and then format it -->
            <p class="product-price">
              ${{ (parseFloat(product.price) || 0).toFixed(2) }}
            </p>

            <p class="product-description">{{ product.description || 'No description available.' }}</p>
            <button class="btn-buy">Buy Now</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.items-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  padding: 20px;
}

.product-card {
  border: 1px solid #ddd;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  background-color: white;
  transition: transform 0.3s ease;
}

.product-card:hover {
  transform: scale(1.05);
}

.product-info {
  padding: 15px;
}

.product-image {
  width: 100%;
  height: 200px;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 15px;
}

.product-img {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
}

.product-name {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 10px 0;
}

.product-price {
  font-size: 1.1rem;
  color: #2a9d8f;
  margin: 5px 0;
}

.product-description {
  font-size: 0.9rem;
  color: #555;
  margin: 5px 0;
}

.btn-buy {
  display: inline-block;
  background-color: #2a9d8f;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  text-align: center;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-buy:hover {
  background-color: #1f7c6b;
}

.loading-message,
.no-products-message {
  text-align: center;
  font-size: 1.2rem;
  color: #888;
  margin-top: 20px;
}
</style>

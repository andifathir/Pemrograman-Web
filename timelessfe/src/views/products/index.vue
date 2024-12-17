<script setup>
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../../api";

// Initialize router
// const router = useRouter();

// Define state variables
const products = ref([]);
const categories = ref([]);
const isLoading = ref(true);
const errors = ref([]);

// Track the currently edited product ID
const editingProductId = ref(null);

// Form state
const newProduct = ref({
  name: "",
  brand: "",
  description: "",
  price: "",
  quantity_in_stock: "",
  image_url: null,
  category_ids: [],
});

// Fetch categories
const fetchCategories = async () => {
  try {
    const response = await api.get("/api/categories");
    categories.value = response.data.data || response.data || [];
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
};

// Fetch product data
const fetchDataProducts = async () => {
  try {
    const response = await api.get("/api/products");
    products.value = response.data.data || response.data || [];
  } catch (error) {
    console.error("Error fetching products:", error);
  } finally {
    isLoading.value = false;
  }
};

// Handle file input changes
const handleFileChange = (e) => {
  newProduct.value.image_url = e.target.files[0];
};

// Create or Update a product
const saveProduct = async () => {
  try {
    console.log("Form Submitted");

    // Initialize FormData
    const formData = new FormData();
    formData.append("name", newProduct.value.name);
    formData.append("brand", newProduct.value.brand);
    formData.append("description", newProduct.value.description);
    formData.append("price", newProduct.value.price);
    formData.append("quantity_in_stock", newProduct.value.quantity_in_stock);

    newProduct.value.category_ids.forEach((id, index) => {
      formData.append(`category_ids[${index}]`, id);
    });

    if (newProduct.value.image_url) {
      formData.append("image_url", newProduct.value.image_url);
    }

    if (editingProductId.value) {
      // Update product
      await api.post(`/api/products/${editingProductId.value}?_method=PUT`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      console.log("Product updated successfully!");
    } else {
      // Create product
      await api.post("/api/products", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      console.log("Product created successfully!");
    }

    // Refresh product list
    fetchDataProducts();

    // Reset form
    resetForm();
  } catch (error) {
    if (error.response && error.response.data) {
      console.error("Validation Errors:", error.response.data.errors);
      errors.value = error.response.data.errors;
    } else {
      console.error("Error saving product:", error.message);
    }
  }
};

// Edit product
const editProduct = (product) => {
  editingProductId.value = product.id;
  newProduct.value = {
    name: product.name,
    brand: product.brand,
    description: product.description,
    price: product.price,
    quantity_in_stock: product.quantity_in_stock,
    category_ids: product.categories.map((category) => category.id),
    image_url: null, // File input cannot preload, so keep it null
  };
};

// Reset form
const resetForm = () => {
  editingProductId.value = null;
  newProduct.value = {
    name: "",
    brand: "",
    description: "",
    price: "",
    quantity_in_stock: "",
    image_url: null,
    category_ids: [],
  };
};

// Delete product
const deleteProduct = async (id) => {
  await api.delete(`/api/products/${id}`);
  fetchDataProducts();
};

// Fetch data on mount
onMounted(() => {
  fetchDataProducts();
  fetchCategories();
});
</script>

<template>
  <div class="app-container">
    <!-- Navbar -->
    <Navbar />

    <!-- Main Container -->
    <div class="main-container">
      <!-- Create or Edit Product Section -->
      <div class="card">
        <h2 class="section-title">
          {{ editingProductId ? "Edit Product" : "Create New Product" }}
        </h2>
        <form @submit.prevent="saveProduct" class="form">
          <div class="form-row">
            <input
              v-model="newProduct.name"
              type="text"
              placeholder="Product Name"
              class="input"
              required
            />
            <input
              v-model="newProduct.brand"
              type="text"
              placeholder="Brand"
              class="input"
              required
            />
          </div>
          <div class="form-row">
            <input
              v-model="newProduct.price"
              type="number"
              placeholder="Price"
              class="input"
              required
            />
            <input
              v-model="newProduct.quantity_in_stock"
              type="number"
              placeholder="Quantity in Stock"
              class="input"
              required
            />
          </div>
          <textarea
            v-model="newProduct.description"
            placeholder="Description"
            rows="4"
            class="textarea"
          ></textarea>

          <div class="form-row">
            <div>
              <label class="label">Product Image</label>
              <input
                type="file"
                @change="handleFileChange($event)"
                class="file-input"
              />
            </div>
            <div>
              <label class="label">Categories</label>
              <select v-model="newProduct.category_ids" multiple class="select">
                <option
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.id"
                >
                  {{ category.name }}
                </option>
              </select>
            </div>
          </div>

          <button type="submit" class="submit-button">
            {{ editingProductId ? "Update Product" : "Create Product" }}
          </button>
          <button
            v-if="editingProductId"
            type="button"
            @click="resetForm"
            class="cancel-button"
          >
            Cancel
          </button>
        </form>
      </div>

      <!-- Product List -->
      <div>
        <h2 class="section-title">Product List</h2>
        <div v-if="products.length > 0" class="product-list">
          <div
            v-for="product in products"
            :key="product.id"
            class="product-card"
          >
            <img
              :src="product.image_url || 'https://via.placeholder.com/300'"
              alt="Product Image"
              class="product-image"
            />
            <h3 class="product-title">{{ product.name }}</h3>
            <p class="product-description">{{ product.description }}</p>
            <p class="product-price">${{ product.price }}</p>
            <div class="product-actions">
              <button @click="editProduct(product)" class="edit-button">
                Edit
              </button>
              <button @click="deleteProduct(product.id)" class="delete-button">
                Delete
              </button>
            </div>
          </div>
        </div>
        <p v-else class="no-products">No products available.</p>
      </div>
    </div>

    <!-- Footer -->
    <Footer />
  </div>
</template>


<style scoped>
.app-container {
  font-family: Arial, sans-serif;
  color: #333;
  background-color: #f9f9f9;
}

.navbar {
  background-color: #fff;
  padding: 10px 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
}

.main-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.card {
  background-color: #fff;
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.section-title {
  font-size: 1.25rem;
  margin-bottom: 10px;
}

.form-row {
  display: flex;
  gap: 20px;
  margin-bottom: 10px;
}

.input,
.textarea,
.select,
.file-input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.textarea {
  resize: none;
}

.submit-button {
  padding: 10px 20px;
  background-color: #4f46e5;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.submit-button:hover {
  background-color: #4338ca;
}

.product-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

.product-card {
  background-color: #fff;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  margin-bottom: 10px;
  border-radius: 5px;
}

.product-title {
  font-size: 1.1rem;
  margin-bottom: 5px;
}

.product-description {
  color: #555;
  margin-bottom: 10px;
}

.product-price {
  font-weight: bold;
  color: #4f46e5;
}

.product-actions button {
  background: none;
  border: none;
  cursor: pointer;
}

.edit-button {
  color: #4f46e5;
}

.delete-button {
  color: #e53e3e;
}

.cancel-button {
  margin-left: 10px;
  padding: 10px 20px;
  background-color: #e53e3e;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.cancel-button:hover {
  background-color: #c53030;
}

.footer {
  text-align: center;
  padding: 10px;
  background-color: #333;
  color: #fff;
}
</style>

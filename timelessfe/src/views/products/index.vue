<script setup>
import { ref, onMounted, computed } from "vue";
import api from "../../api";

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
      await api.post(
        `/api/products/${editingProductId.value}?_method=PUT`,
        formData,
        {
          headers: { "Content-Type": "multipart/form-data" },
        }
      );
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

// Add a helper to get the category name by ID
const getCategoryName = (categoryId) => {
  const category = categories.value.find((cat) => cat.id === categoryId);
  return category ? category.name : "";
};

// Add a helper to remove a selected category
const removeCategory = (categoryId) => {
  newProduct.value.category_ids = newProduct.value.category_ids.filter(
    (id) => id !== categoryId
  );
};

// Add a helper to add a category (if not already selected)
const addCategory = (categoryId) => {
  if (!newProduct.value.category_ids.includes(categoryId)) {
    newProduct.value.category_ids.push(categoryId);
  }
};

// Compute available categories
const availableCategories = computed(() =>
  categories.value.filter(
    (category) => !newProduct.value.category_ids.includes(category.id)
  )
);

// Fetch data on mount
onMounted(() => {
  fetchDataProducts();
  fetchCategories();
});
</script>

<template>
  <div class="app-container">
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
          <div>
            <label class="label">Categories</label>
            <div class="tag-picker">
              <!-- Selected Tags -->
              <div class="selected-tags">
                <span
                  v-for="categoryId in newProduct.category_ids"
                  :key="categoryId"
                  class="tag"
                >
                  {{ getCategoryName(categoryId) }}
                  <button
                    @click="removeCategory(categoryId)"
                    class="remove-btn"
                  >
                    &times;
                  </button>
                </span>
              </div>

              <!-- Available Categories -->
              <div class="tag-options">
                <button
                  v-for="category in availableCategories"
                  :key="category.id"
                  @click="addCategory(category.id)"
                  class="tag-option"
                >
                  {{ category.name }}
                </button>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div>
              <label class="label">Product Image</label>
              <input
                type="file"
                @change="handleFileChange($event)"
                class="file-input"
              />
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

            <!-- Categories -->
            <div class="product-categories">
              <strong>Categories:</strong>
              <ul>
                <li v-for="category in product.categories" :key="category.id">
                  {{ category.name }}
                </li>
              </ul>
            </div>

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
  </div>
</template>

<style scoped>
.app-container {
  font-family: Arial, sans-serif;
  color: #333;
  background-color: #f9f9f9;
  margin-top: 80px;
}

.main-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  /* border: #c53030 2px solid; */
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
  height: 250px;
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
  border: 2px solid #d1d5db;
  border-radius: 5px;
  cursor: pointer;
  padding: 5px 10px;
  font-size: 14px;
  font-weight: bold;
  transition: background-color 0.3s, border-color 0.3s;
}

.edit-button {
  color: #ffffff;
  background-color: #4f46e5;
  border-color: #4f46e5;
}

.edit-button:hover {
  background-color: #4338ca;
  border-color: #4338ca;
}

.delete-button {
  color: #ffffff;
  background-color: #e53e3e;
  border-color: #e53e3e;
  margin-left: 15px;
}

.delete-button:hover {
  background-color: #c53030;
  border-color: #c53030;
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

.tag-picker {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 10px;
}

.selected-tags {
  display: flex;
  gap: 5px;
  flex-wrap: wrap;
  margin-bottom: 10px;
}

.tag {
  background-color: #4f46e5;
  color: #fff;
  padding: 5px 10px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  font-size: 14px;
}

.tag .remove-btn {
  background: transparent;
  border: none;
  color: #fff;
  font-size: 14px;
  margin-left: 5px;
  cursor: pointer;
}

.tag .remove-btn:hover {
  color: #c53030;
}

.tag-options {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.tag-option {
  background-color: #f3f4f6;
  color: #333;
  padding: 5px 10px;
  border: 1px solid #ccc;
  border-radius: 20px;
  cursor: pointer;
}

.tag-option:hover {
  background-color: #e5e7eb;
}
</style>

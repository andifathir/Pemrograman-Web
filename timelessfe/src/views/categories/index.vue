<script setup>
import { ref, onMounted } from "vue";
import api from "../../api";

// State variables
const categories = ref([]);
const isLoading = ref(true);
const errors = ref([]);

// Track the currently edited category ID
const editingCategoryId = ref(null);

// Form state
const newCategory = ref({
  name: "",
  description: "",
});

// Fetch all categories
const fetchCategories = async () => {
  try {
    const response = await api.get("/api/categories");
    categories.value = response.data.data || response.data || [];
  } catch (error) {
    console.error("Error fetching categories:", error);
  } finally {
    isLoading.value = false;
  }
};

// Create or Update a category
const saveCategory = async () => {
  try {
    console.log("Form Submitted");

    const payload = {
      name: newCategory.value.name,
      description: newCategory.value.description,
    };

    if (editingCategoryId.value) {
      // Update category
      await api.put(`/api/categories/${editingCategoryId.value}`, payload);
      console.log("Category updated successfully!");
    } else {
      // Create category
      await api.post("/api/categories", payload);
      console.log("Category created successfully!");
    }

    // Refresh category list
    fetchCategories();

    // Reset form
    resetForm();
  } catch (error) {
    if (error.response && error.response.data) {
      console.error("Validation Errors:", error.response.data.errors);
      errors.value = error.response.data.errors;
    } else {
      console.error("Error saving category:", error.message);
    }
  }
};

// Edit category
const editCategory = (category) => {
  editingCategoryId.value = category.id;
  newCategory.value = {
    name: category.name,
    description: category.description,
  };
};

// Reset form
const resetForm = () => {
  editingCategoryId.value = null;
  newCategory.value = {
    name: "",
    description: "",
  };
};

// Delete category
const deleteCategory = async (id) => {
  try {
    await api.delete(`/api/categories/${id}`);
    console.log("Category deleted successfully!");
    fetchCategories();
  } catch (error) {
    console.error("Error deleting category:", error);
  }
};

// Fetch categories on mount
onMounted(() => {
  fetchCategories();
});
</script>

<template>
  <div class="app-container">
    <!-- Main Container -->
    <div class="main-container">
      <!-- Create or Edit Category Section -->
      <div class="card">
        <h2 class="section-title">
          {{ editingCategoryId ? "Edit Category" : "Create New Category" }}
        </h2>
        <form @submit.prevent="saveCategory" class="form">
          <div class="form-row">
            <input
              v-model="newCategory.name"
              type="text"
              placeholder="Category Name"
              class="input"
              required
            />
          </div>
          <textarea
            v-model="newCategory.description"
            placeholder="Description"
            rows="4"
            class="textarea"
          ></textarea>
          <button type="submit" class="submit-button">
            {{ editingCategoryId ? "Update Category" : "Create Category" }}
          </button>
          <button
            v-if="editingCategoryId"
            type="button"
            @click="resetForm"
            class="cancel-button"
          >
            Cancel
          </button>
        </form>
      </div>

      <!-- Category List -->
      <div>
        <h2 class="section-title">Category List</h2>
        <div v-if="categories.length > 0" class="category-list">
          <div
            v-for="category in categories"
            :key="category.id"
            class="category-card"
          >
            <h3 class="category-title">{{ category.name }}</h3>
            <p class="category-description">{{ category.description }}</p>
            <div class="category-actions">
              <button @click="editCategory(category)" class="edit-button">
                Edit
              </button>
              <button
                @click="deleteCategory(category.id)"
                class="delete-button"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
        <p v-else class="no-categories">No categories available.</p>
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
  max-width: 800px;
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
  margin-bottom: 10px;
}

.input,
.textarea {
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

.category-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.category-card {
  background-color: #fff;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.category-title {
  font-size: 1.1rem;
  margin-bottom: 5px;
}

.category-description {
  color: #555;
  margin-bottom: 10px;
}

.category-actions button {
  border: none;
  border-radius: 5px;
  cursor: pointer;
  padding: 5px 10px;
  font-size: 14px;
}

.edit-button {
  color: #ffffff;
  background-color: #4f46e5;
}

.edit-button:hover {
  background-color: #4338ca;
}

.delete-button {
  color: #ffffff;
  background-color: #e53e3e;
  margin-left: 15px;
}

.delete-button:hover {
  background-color: #c53030;
}
</style>

<script>
import Navbar from "../components/Navbar.vue";
import Footer from "../components/Footer.vue";

export default {
  name: "home",
  components: {
    Navbar,
    Footer,
  },

  methods: {
    // Navigate to the Products CRUD route
    goToProducts() {
      this.$router.push("/products");
    },
    // Navigate to the Categories CRUD route
    goToCategories() {
      this.$router.push("/categories");
    },
  },
};
</script>

<template>
  <div class="page-container">
    <!-- Navbar Component -->
    <Navbar />

    <!-- Main Content -->
    <div class="content">
      <h2>Choose A CRUD Operation</h2>
      <div class="button-container">
        <button @click="goToProducts" class="btn btn-primary">Products</button>
        <button @click="goToCategories" class="btn btn-secondary">
          Categories
        </button>
      </div>
    </div>

    <!-- Footer Component -->
    <Footer />
  </div>
</template>

<style scoped>
/* Ensure full height for html and body */
html,
body {
  margin: 0;
  height: 100%;
}

/* Page container with flexbox layout */
.page-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh; /* Full viewport height */
}

/* Content area - grows to push the footer down */
.content {
  flex: 1; /* Takes up remaining space */
  display: flex;
  flex-direction: column;
  justify-content: center; /* Vertically center */
  align-items: center; /* Horizontally center */
  text-align: center;
  padding: 1rem;
}

/* Button container styling */
.button-container {
  margin-top: 1rem;
  display: flex;
  gap: 1rem;
}

button {
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}

/* Footer - positioned naturally at the bottom */
.footer {
  text-align: center;
  padding: 1rem;
  background-color: #f8f9fa; /* Optional for visibility */
}
</style>

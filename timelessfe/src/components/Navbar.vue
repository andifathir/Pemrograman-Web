<template>
  <div class="navbar">
    <div class="logo">
      <i class="ri-hourglass-2-line"></i>
      <span>TIMELESS</span>
    </div>
    <!-- Make sure the 'h-class' is toggled via JavaScript and 
         the 'v-class' is toggled when the burger is clicked -->
    <nav class="navbar-items h-class">
      <ul class="nav v-class">
        <li><router-link to="/">HOME </router-link></li>
        <li><router-link to="/crud">CRUD </router-link></li>
        <li><router-link to="/category">CATEGORY</router-link></li>
        <li><router-link to="/items">ITEMS</router-link></li>
        <!-- Link to items page -->
      </ul>
    </nav>
    <!-- Burger icon -->
    <div class="burger"><i class="ri-menu-4-line"></i></div>
  </div>
</template>

<script>
export default {
  name: "Navbar",
  data() {
    return {
      isNavbarOpen: false, // Track whether the navbar is open
    };
  },
  mounted() {
    // Get references to the DOM elements after the component has been mounted
    const burger = this.$el.querySelector(".burger");
    const navbarItem = this.$el.querySelector(".navbar-items");

    if (burger && navbarItem) {
      burger.addEventListener("click", () => {
        this.isNavbarOpen = !this.isNavbarOpen; // Toggle state
        this.updateNavbarClass(navbarItem);
      });
    } else {
      console.warn("The .burger or .navbar-items elements were not found.");
    }

    // Handle clicking outside the navbar
    window.addEventListener("click", (event) => {
      if (!this.$el.contains(event.target) && this.isNavbarOpen) {
        this.isNavbarOpen = false;
        this.updateNavbarClass(navbarItem);
      }
    });

    // Close navbar on route change
    this.$router.beforeEach((to, from, next) => {
      if (this.isNavbarOpen) {
        this.isNavbarOpen = false;
        this.updateNavbarClass(navbarItem);
      }
      next(); // Continue navigation
    });
  },
  methods: {
    updateNavbarClass(navbarItem) {
      if (this.isNavbarOpen) {
        navbarItem.classList.remove("h-class");
      } else {
        navbarItem.classList.add("h-class");
      }
    },
  },
};

// Optional: Handle scrolling behavior for navbar
window.addEventListener("scroll", () => {
      let navbar = document.querySelector(".navbar");
      if (window.scrollY > 20) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });
</script>



<style scoped>
</style>

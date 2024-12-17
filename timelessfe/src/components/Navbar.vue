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
        <li><router-link to="/">HOME</router-link></li>
        <li><router-link to="#ABOUT">ABOUT</router-link></li>
        <li><router-link to="#CATEGORY">CATEGORY</router-link></li>
        <li><router-link to="/items">ITEMS</router-link></li> <!-- Link to items page -->
      </ul>
    </nav>
    <!-- Burger icon -->
    <div class="burger"><i class="ri-menu-4-line"></i></div>
  </div>
</template>

<script>
export default {
  name: "Navbar",
  mounted() {
    // Get references to the DOM elements after the component has been mounted
    const burger = this.$el.querySelector('.burger');
    const nav = this.$el.querySelector('.nav');
    const navbarItem = this.$el.querySelector('.navbar-items');

    // Ensure the elements exist before adding the event listener
    if (burger && nav && navbarItem) {
      burger.addEventListener("click", () => {
        navbarItem.classList.toggle("h-class");
        nav.classList.toggle("v-class");
      });
    } else {
      console.warn("The .burger, .nav, or .navbar-items elements were not found.");
    }

    // Optional: Handle scrolling behavior for navbar
    window.addEventListener("scroll", () => {
      const navbar = this.$el.querySelector(".navbar");
      if (navbar) {
        if (window.scrollY > 20) {
          navbar.classList.add("scrolled");
        } else {
          navbar.classList.remove("scrolled");
        }
      }
    });
  }
};
</script>

<style scoped>
/* Add styles here as needed */
</style>

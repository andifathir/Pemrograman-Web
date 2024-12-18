<template>

  <Navbar />

  <router-view></router-view>

  <Footer/>
   
</template>

<script>
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
// import 'swiper/swiper-bundle.css';


export default {
  name: 'home',
  components: {
    Navbar,
    Footer,
  },
};
</script>

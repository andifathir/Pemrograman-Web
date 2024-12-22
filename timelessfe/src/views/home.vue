<script setup>
import { onMounted } from 'vue';
import Swiper from 'swiper/bundle'; // Ensure you are importing Swiper properly

// Initialize Swiper after component is mounted
onMounted(() => {
  const swiper = new Swiper(".swiper", {
    direction: "horizontal",
    loop: true,
    slidesPerView: 3,
    spaceBetween: 30,
    pagination: {
      el: ".swiper-pagination",
      type: "bullets",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
});
</script>

<template> 
  <section class="homepg" id="HOME">
    <div class="content">
      <div class="left">
        <div class="left_box">
          <h2>watches collection 2024</h2>
          <h1>customer watches for any occasion</h1>
          <p>
            Celebrating the art of timekeeping, where every moment becomes a
            cherished memory and every watch reflects a legacy of elegance and
            precision.
          </p>
          <button class="btn">Explore more</button>
        </div>
      </div>
      <div class="right">
        <img src="../assets/images/pic2.png" alt="" />
        <div class="text">
          <div class="line"></div>
          <div class="line_text">Where every second tells a story.</div>
        </div>
      </div>
    </div>
  </section>
  <div class="featured">
    <h2>Featured</h2>
  </div>
  <div class="section">
      <!-- Slider main container -->
      <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
          <!-- Slides -->
          <div class="swiper-slide">
            <img
              src="../assets/images/professional-watches-submariner-green-bezel-roller-m126610lv-0002-2009jva-001-xl-frog.avif"
              alt="Rolex Submariner"
            />
            <p class="watch-name">Rolex</p>
            <p class="watch-name">Submariner</p>
          </div>
          <div class="swiper-slide">
            <img src="../assets/images/AP royal oak.webp" alt="Watch 2" />
            <p class="watch-name">Audemars Piguet</p>
            <p class="watch-name">Royal Oak</p>
          </div>
          <div class="swiper-slide">
            <img
              src="../assets/images/Daytona126519LNBlack_1_3354099a-05ec-46b1-8baa-11bd59e3a530.webp"
              alt="Watch 3"
            />
            <p class="watch-name">Rolex</p>
            <p class="watch-name">Daytona</p>
          </div>
          <div class="swiper-slide">
            <img
              src="../assets/images/carouselHP_novelties_1000x1321_ROmini_C.avif"
              alt="Watch 4"
            />
            <p class="watch-name">Audemars Piguet</p>
            <p class="watch-name">Royal Oak Mini Frosted Gold Quartz</p>
          </div>
          <div class="swiper-slide">
            <img src="../assets/images/SJE089J1.png" alt="Watch 5" />
            <p class="watch-name">Seiko</p>
            <p class="watch-name">King Seiko</p>
          </div>
        </div>

        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>
        <!-- If we need scrollbar -->
        <!-- <div class="swiper-scrollbar"></div> -->
      </div>
      <div class="pagination_">
        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev">
          <img src="../assets/icon/chevron-back-outline.svg" alt="" />
        </div>
        <div class="swiper-button-next">
          <img src="../assets/icon/chevron-forward-outline.svg" alt="" />
        </div>
      </div>
    </div>
</template>

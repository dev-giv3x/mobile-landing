<script setup lang="ts">
const scrollToForm = () => {
  const event = new CustomEvent('scroll-to-contact');
  window.dispatchEvent(event);
};
</script>

<template>
  <section class="px-4">
    <div class="max-w-[1120px] mx-auto pt-[60px] md:pt-[112px] flex justify-between items-start gap-4 md:gap-8">
      <div class="flex flex-col flex-1 min-w-0">
        <h1 class="text-xl sm:text-3xl md:text-5xl text-[#1A2B4B] ">Корпоративное<br class="hidden md:block">приложение <br class="hidden md:block">для вашего бизнеса</h1>
        <p class="text-[#5F738C] text-sm md:text-lg mt-4">Единый цифровой офис для вашей компании</p>
        <div class="flex flex-row items-center gap-2 md:gap-4 mt-3 overflow-visible">
          <div class="flex flex-row items-center gap-2 md:gap-4 mt-3">
            <button @click="scrollToForm" class="bg-[#1D65C1] hover:bg-[#154a8d] text-white text-[14px] max-[375px]:w-[150px] max-[375px]:h-[50px] xs:text-xs md:text-base px-3 md:px-8 py-3 active:scale-95 transition-all cursor-pointer rounded whitespace-nowrap">Оставить заявку</button>
            <button @click="scrollToForm" class="border border-[#D1D5DB] hover:bg-gray-50 text-[#1A2B4B] text-[14px] max-[375px]:w-[150px] max-[375px]:h-[50px] xs:text-xs md:text-base px-3 md:px-8 py-3 active:scale-95 transition-all cursor-pointer rounded whitespace-nowrap">Получить демо</button>
          </div>
        </div>
      </div>
      <div class="w-[25%] sm:w-[30%] md:w-[300px] flex-shrink-0">
        <img src="@/assets/image/hero-block-image.png" class="w-full h-auto object-contain" alt="hero"/>
      </div>
    </div>
  </section>
</template>



<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
  name: "BusinesTrouble"
})
</script>

<template>
  <section class="mb-12 px-4">
    <div class="flex items-center max-[450px]:mt-12 mt-[120px] gap-4">
      <div class="flex items-center justify-center w-full gap-4">
        <div class="hidden md:block flex-1 mr-[46px] h-px max-w-[364px] bg-gradient-to-r from-[#EBF1F9] to-[#EDEDED]"></div>
        <h2 class="text-xl md:text-3xl max-[376px]:text-[18px] text-[#1A2B4B] text-center shrink-0 whitespace-nowrap">Проблемы бизнеса?</h2>
        <div class="hidden md:block flex-1 ml-[46px] h-px max-w-[364px] bg-gradient-to-l from-[#FFFFFF] to-[#EDEDED]"></div>
      </div>
    </div>
    <div class="flex flex-wrap justify-center mt-10 gap-6">
      <div class="flex items-center bg-white h-[112px] max-[376px]:max-w-[300px] max-[376px]:h-[95px] rounded-xl shadow-md p-6 flex-1  min-w-[300px] max-w-[400px]">
        <div class="flex-shrink-0">
          <img src="@/assets/image/busines-troubles-first.png" class=" w-[83px] h-[62px] object-contain" alt="image">
        </div>
        <div class="ml-4">
          <p class="text-lg text-[#1A2B4B] ">Отсутствие единого инфополя</p>
        </div>
      </div>
      <div class="flex items-center bg-white h-[112px] max-[376px]:max-w-[300px] max-[376px]:h-[95px] rounded-xl shadow-md p-6 flex-1 min-w-[300px] max-w-[400px]">
        <div class="flex-shrink-0">
          <img src="@/assets/image/busines-troubles-second.png" class=" w-[81px] h-[69px] object-contain" alt="image">
        </div>
        <div class="ml-4">
          <p class="text-lg text-[#1A2B4B] leading-6 font-normal">Потеря данных</p>
        </div>
      </div>
      <div class="flex items-center bg-white h-[112px] max-[376px]:max-w-[300px] max-[376px]:h-[95px] rounded-xl shadow-md p-6 flex-1 min-w-[300px] max-w-[400px]">
        <div class="flex-shrink-0">
          <img src="@/assets/image/busines-troubles-third.png" class=" w-[90px] h-[90px] object-contain" alt="image">
        </div>
        <div class="">
          <p class="text-lg text-[#1A2B4B] leading-6 font-normal">Сложная коммуникация</p>
        </div>
      </div>
    </div>
  </section>
</template>

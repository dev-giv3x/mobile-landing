<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const icons = import.meta.glob('../assets/icons/*.svg', { eager: true })

const getIcon = (name: string) => {
  return icons[`../assets/icons/${name}.svg`]?.default || ''
}

const modules = ref([])

onMounted(async () => {
    const response = await axios.get('http://localhost:80/api/modules')
    modules.value = response.data
})
</script>

<template>
  <section class="px-4">
    <div class="flex justify-center mt-[112px] items-start">
      <div class="max-[780px]:hidden w-[174px] h-px mr-[46.5px] bg-[linear-gradient(90deg,#F1F5FB_0%,#EDEDED_100%)] mt-[18px]"></div>
      <div class="text-center">
        <h2 class="text-[#1A2B4B] text-2xl md:text-3xl max-[376px]:text-[18px] ">Решение: <span class="text-[#1D65C1] block md:inline-block"> Единое корпоративное приложение</span></h2>
        <p class="text-[#5F738C] max-[376px]:text-[16px] md:text-lg mt-2">
          Все инструменты для сотрудников в одном месте</p>
      </div>
      <div class="max-[780px]:hidden w-[174px] h-px ml-[46.5px] bg-[linear-gradient(90deg,#EDEDED_0%,#FCFDFE_100%)] mt-[18px]"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12 max-w-[1120px] mx-auto justify-items-center">
      <div class="w-full max-w-[357px] h-[192px] bg-white rounded-xl shadow-sm border border-gray-50 flex flex-col" v-for="m in modules" :key="m.id">
        <div class="flex items-center gap-3 px-[25px] pt-[25px]">
          <img :src="getIcon(m.primary_icon)" class="w-8 h-8 object-contain" alt="" />
          <h3 class="text-lg text-[#1A2B4B]">{{ m.title }}</h3>
        </div>
        <div class="w-[307.33px] h-px bg-[#F3F4F6] ml-[25px] mt-4 mb-3"></div>
        <p class=" text-base text-[#5F738C] pl-[25px]"> {{ m.content }} </p>
        <div class="w-[307.33px] h-px bg-[#F3F4F6] ml-[25px] mt-4 mb-3"></div>
        <div class="flex items-center gap-3 px-[25px] pb-[20px]">
          <img :src="getIcon(m.secondary_icon)" class="w-5 h-5 object-contain" alt="" />
          <p class="text-sm text-[#5F738C]">{{ m.secondary_content }}</p>
        </div>
      </div>
    </div>
  </section>
</template>
<template>
  <div>
    <image-ocr :image-src="imagePath" />
  </div>
</template>

<script>
import ImageOcr from './cameraOcrStatic.vue';

export default {
  name: 'OCRPage',
  components: {
    ImageOcr
  },
  data() {
    return {
      imagePath: '/img/aprende.png'
    }
  }
}
</script>

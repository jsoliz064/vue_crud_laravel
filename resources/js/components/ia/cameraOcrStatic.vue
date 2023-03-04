<template>
  <div>
    <img :src="imageSrc" ref="image" />
    <button @click="recognizeText">Extract Text</button>
    <div v-if="text">
      <h3>Extracted Text:</h3>
      <p>{{ text }}</p>
    </div>
  </div>
</template>

<script>
import Tesseract from 'tesseract.js';

export default {
  name: 'ImageOCR',
  props: {
    imageSrc: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      text: ''
    }
  },
  methods: {
    recognizeText() {
      Tesseract.recognize(this.$refs.image.src,'spa')
        .then(result => {
          this.text = result.data.text;
          console.log(result);
        })
    }
  }
}
</script>

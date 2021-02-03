<template>
  <div>
    <h3>Компонент Vue.js</h3>
    <span>{{ number }}</span>
    <button @click="add">+</button>
    <button @click="subtract">-</button>
    <label for="input-number">Введите число</label>
    <input id="input-number" type="text" v-model="number">
    <div v-for="item in sidebar">
        {{ item.caption }}
    </div>
    <router-link to="/site/clinics">Клиники</router-link>
    <router-link to="/site/financing">Финансирование</router-link>
    <router-view></router-view>
  </div>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: "register",
  data() {
    return {
      number: 0
    }
  },
  computed: { ...mapState(['sidebar']), },
  methods: {
    add() { this.number++ },
    subtract() { this.number-- },
  }
}
</script>

<style scoped>
</style>
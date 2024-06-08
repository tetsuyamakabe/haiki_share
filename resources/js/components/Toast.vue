<!-- Toast.vue -->
<template>
    <div v-if="flashMessage.message" :class="`toast toast-${flashMessage.type}`">
      {{ flashMessage.message }}
    </div>
  </template>
  
  <script>
  import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters('flash', ['flashMessage']) // モジュール名を指定してgetterを取得
  }
}

  </script>
  
  <style>
.toast {
  position: absolute;
  top: 0;
  left: 0; /* 左端に表示するためにleftを0に設定 */
  width: 100%; /* 画面幅いっぱいに表示 */
  padding: 16px;
  color: #fff;
  font-weight: bold;
  opacity: 0.9;
  z-index: 2; /* ヘッダーよりも大きな値に設定 */
  display: flex;
    justify-content: center;
}

/* 以下はそのまま */
.toast-success {
  background-color: #28a745;
}
.toast-error {
  background-color: #dc3545;
}
.toast-info {
  background-color: #17a2b8;
}
.toast-warning {
  background-color: #ffc107;
}
  </style>
  
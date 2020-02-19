<template>
  <div class="container">

    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link active" href="#">Active</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
    </ul>

    <div>
      <h1>users</h1>
      <input type="text" v-model="id">
      <button @click="loadUser">Load user</button>
    </div>
  </div>
</template>

<script>
    export default {
      data() {
         return {
           id: ''
         }
      },
      methods: {
        loadUser() {
          this.$router.push(`/users/${this.id}`)
        }
      }
    }
</script>

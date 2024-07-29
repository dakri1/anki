<template>
<h1>Hello, {{ firstName }}</h1>
</template>

<script>
export default {
  name: 'Cards',
  data() {
    return {
      firstName: 'George',
    };
  },
};
</script>
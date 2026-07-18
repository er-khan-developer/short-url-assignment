<script setup lang="ts">
import DataTable from 'datatables.net-vue3';

const columns = [
  { data: 'id', title: 'ID' },
  { data: 'name', title: 'Name' },
  { data: 'email', title: 'Email' },
];

const data = [
  { id: 1, name: 'John', email: 'john@example.com' },
  { id: 2, name: 'Jane', email: 'jane@example.com' },
];
</script>

<template>
  <DataTable
    :data="data"
    :columns="columns"
    class="display"
  />
</template>
<script setup>
import { ref } from "vue";
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const breadcrumbs = [
  {
    title: 'Departments',
    href: '/departments',
  },
];


const departments = ref([
  { id: 1, name: "Institute of Computing", head: "Dr. Santos" },
  { id: 2, name: "Human Resources", head: "Ms. Dela Cruz" },
  { id: 3, name: "Finance Department", head: "Mr. Reyes" },
]);


const showModal = ref(false);
const editMode = ref(false);
const current = ref({ id: null, name: "", head: "" });


const heads = ref([
  "Dr. Santos",
  "Ms. Dela Cruz",
  "Mr. Reyes",
  "Engr. Lopez",
]);

function openAddModal() {
  editMode.value = false;
  current.value = { id: null, name: "", head: "" };
  showModal.value = true;
}

function openEditModal(dept) {
  editMode.value = true;
  current.value = { ...dept };
  showModal.value = true;
}

function save() {
  if (editMode.value) {
    const index = departments.value.findIndex((d) => d.id === current.value.id);
    departments.value[index] = { ...current.value };
  } else {
    departments.value.push({
      ...current.value,
      id: Date.now(),
    });
  }
  showModal.value = false;
}
</script>

<template>
    <Head title="Departments"/>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
     <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Departments</h1>

      <button
        @click="openAddModal"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
      >
        + Add Department
      </button>
    </div>

     <table class="w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 border">ID</th>
          <th class="p-3 border">Department Name</th>
          <th class="p-3 border">Department Head</th>
          <th class="p-3 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="dept in departments"
          :key="dept.id"
          class="hover:bg-gray-50"
        >
          <td class="p-3 border">{{ dept.id }}</td>
          <td class="p-3 border">{{ dept.name }}</td>
          <td class="p-3 border">{{ dept.head || "—" }}</td>
          <td class="p-3 border">
            <button
              @click="openEditModal(dept)"
              class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
            >
              Edit
            </button>
          </td>
        </tr>
      </tbody>
    </table>

     <div
      v-if="showModal"
      class="fixed inset-0 bg-white bg-opacity-40 flex justify-center items-center"
    >
      <div class="bg-white rounded-lg p-6 w-96 shadow-xl">
        <h2 class="text-xl font-bold mb-4">
          {{ editMode ? "Edit Department" : "Add Department" }}
        </h2>

         <label class="block mb-2 font-medium">Department Name</label>
        <input
          v-model="current.name"
          type="text"
          class="w-full px-3 py-2 border rounded mb-4"
        />

         <label class="block mb-2 font-medium">Assign Department Head</label>
        <select
          v-model="current.head"
          class="w-full px-3 py-2 border rounded mb-4"
        >
          <option disabled value="">Select Head</option>
          <option v-for="h in heads" :key="h" :value="h">
            {{ h }}
          </option>
        </select>

         <div class="flex justify-end gap-3 mt-4">
          <button
            @click="showModal = false"
            class="px-3 py-2 bg-gray-300 rounded hover:bg-gray-400"
          >
            Cancel
          </button>
          <button
            @click="save"
            class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
const breadcrumbs = [
  {
    title: 'Attendance',
    href: '/attendance',
  },
];

// Sample attendance data
const records = ref([
  { id: 1, user: "Juan Dela Cruz", date: "2025-11-27", time_in: "08:05", time_out: "17:00", status: "Present" },
  { id: 2, user: "Maria Santos", date: "2025-11-27", time_in: "08:10", time_out: "17:05", status: "Present" },
  { id: 3, user: "Pedro Reyes", date: "2025-11-27", time_in: "—", time_out: "—", status: "Absent" },
]);

// For form + modal
const showModal = ref(false);
const editMode = ref(false);
const current = ref({
  id: null,
  user: "",
  date: "",
  time_in: "",
  time_out: "",
  status: "",
});

// Sample employees
const users = ref(["Juan Dela Cruz", "Maria Santos", "Pedro Reyes", "Ana Lopez"]);

function openAdd() {
  editMode.value = false;
  current.value = { id: null, user: "", date: "", time_in: "", time_out: "", status: "" };
  showModal.value = true;
}

function openEdit(item) {
  editMode.value = true;
  current.value = { ...item };
  showModal.value = true;
}

function save() {
  if (editMode.value) {
    const index = records.value.findIndex(r => r.id === current.value.id);
    records.value[index] = { ...current.value };
  } else {
    records.value.push({
      ...current.value,
      id: Date.now(),
    });
  }
  showModal.value = false;
}
</script>

<template>
  <Head title="Attendance Management"/>

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold">Attendance Management</h1>

        <button
          @click="openAdd"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
          + Add Attendance
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-3 border">Employee</th>
              <th class="p-3 border">Date</th>
              <th class="p-3 border">Time In</th>
              <th class="p-3 border">Time Out</th>
              <th class="p-3 border">Status</th>
              <th class="p-3 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="r in records" :key="r.id" class="hover:bg-gray-50">
              <td class="p-3 border">{{ r.user }}</td>
              <td class="p-3 border">{{ r.date }}</td>
              <td class="p-3 border">{{ r.time_in }}</td>
              <td class="p-3 border">{{ r.time_out }}</td>
              <td class="p-3 border">
                <span
                  :class="{
                    'px-2 py-1 rounded text-white': true,
                    'bg-green-600': r.status === 'Present',
                    'bg-yellow-600': r.status === 'Late',
                    'bg-red-600': r.status === 'Absent',
                  }"
                >
                  {{ r.status }}
                </span>
              </td>
              <td class="p-3 border">
                <button
                  @click="openEdit(r)"
                  class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                >
                  Edit
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50"
      >
        <div class="bg-white w-96 p-6 rounded-xl shadow-xl">
          <h2 class="text-xl font-bold mb-4">
            {{ editMode ? "Edit Attendance" : "Add Attendance" }}
          </h2>

          <!-- Employee -->
          <label class="block mb-1 font-medium">Employee</label>
          <select
            v-model="current.user"
            class="w-full border rounded px-3 py-2 mb-4"
          >
            <option disabled value="">Select Employee</option>
            <option v-for="u in users" :key="u">{{ u }}</option>
          </select>

          <!-- Date -->
          <label class="block mb-1 font-medium">Date</label>
          <input
            v-model="current.date"
            type="date"
            class="w-full border rounded px-3 py-2 mb-4"
          />

          <!-- Time In -->
          <label class="block mb-1 font-medium">Time In</label>
          <input
            v-model="current.time_in"
            type="time"
            class="w-full border rounded px-3 py-2 mb-4"
          />

          <!-- Time Out -->
          <label class="block mb-1 font-medium">Time Out</label>
          <input
            v-model="current.time_out"
            type="time"
            class="w-full border rounded px-3 py-2 mb-4"
          />

          <!-- Status -->
          <label class="block mb-1 font-medium">Status</label>
          <select
            v-model="current.status"
            class="w-full border rounded px-3 py-2 mb-4"
          >
            <option disabled value="">Select Status</option>
            <option>Present</option>
            <option>Late</option>
            <option>Absent</option>
          </select>

          <!-- Buttons -->
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

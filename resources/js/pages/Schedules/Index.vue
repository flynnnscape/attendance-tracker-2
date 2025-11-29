<script setup>
import { ref } from "vue";
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const breadcrumbs = [
  {
    title: 'Departments',
    href: '/departments',
  },
];

const shifts = ref([
  { id: 1, name: "Morning Shift", time: "08:00 AM - 12:00 PM" },
  { id: 2, name: "Afternoon Shift", time: "01:00 PM - 05:00 PM" },
  { id: 3, name: "Night Shift", time: "06:00 PM - 10:00 PM" },
]);

// Sample Employees + Assigned Schedules
const employees = ref([
  { id: 1, name: "Juan Dela Cruz", shift: "Morning Shift" },
  { id: 2, name: "Maria Clara", shift: "Afternoon Shift" },
  { id: 3, name: "Pedro Penduko", shift: "—" },
]);

// Modal states
const showShiftModal = ref(false);
const showAssignModal = ref(false);

const editMode = ref(false);

const currentShift = ref({ id: null, name: "", time: "" });
const currentEmployee = ref({ id: null, name: "", shift: "" });

function openNewShiftModal() {
  editMode.value = false;
  currentShift.value = { id: null, name: "", time: "" };
  showShiftModal.value = true;
}

function openEditShiftModal(shift) {
  editMode.value = true;
  currentShift.value = { ...shift };
  showShiftModal.value = true;
}

function saveShift() {
  if (editMode.value) {
    const i = shifts.value.findIndex(s => s.id === currentShift.value.id);
    shifts.value[i] = { ...currentShift.value };
  } else {
    shifts.value.push({ ...currentShift.value, id: Date.now() });
  }
  showShiftModal.value = false;
}

function openAssignModal(emp) {
  currentEmployee.value = { ...emp };
  showAssignModal.value = true;
}

function saveAssignment() {
  const i = employees.value.findIndex(e => e.id === currentEmployee.value.id);
  employees.value[i].shift = currentEmployee.value.shift;
  showAssignModal.value = false;
}
</script>

<template>
  <Head title="Schedules" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Schedules Management</h1>

      <!-- ============================
           SHIFTS SECTION
      ============================= -->
      <div class="mb-10">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Shifts</h2>
          <button
            @click="openNewShiftModal"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            + Create Shift
          </button>
        </div>

        <table class="w-full border">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-3 border">ID</th>
              <th class="p-3 border">Shift Name</th>
              <th class="p-3 border">Time Range</th>
              <th class="p-3 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="shift in shifts" :key="shift.id" class="hover:bg-gray-50">
              <td class="p-3 border">{{ shift.id }}</td>
              <td class="p-3 border">{{ shift.name }}</td>
              <td class="p-3 border">{{ shift.time }}</td>
              <td class="p-3 border">
                <button
                  @click="openEditShiftModal(shift)"
                  class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                >
                  Edit
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ============================
           ASSIGN SCHEDULE SECTION
      ============================= -->
      <div>
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Assign Employee Schedules</h2>
        </div>

        <table class="w-full border">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-3 border">Employee</th>
              <th class="p-3 border">Assigned Shift</th>
              <th class="p-3 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="emp in employees"
              :key="emp.id"
              class="hover:bg-gray-50"
            >
              <td class="p-3 border">{{ emp.name }}</td>
              <td class="p-3 border">{{ emp.shift }}</td>
              <td class="p-3 border">
                <button
                  @click="openAssignModal(emp)"
                  class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700"
                >
                  Assign
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ============================
           SHIFT MODAL
      ============================= -->
      <div
        v-if="showShiftModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center"
      >
        <div class="bg-white rounded-lg p-6 w-96 shadow-xl">
          <h2 class="text-xl font-bold mb-4">
            {{ editMode ? "Edit Shift" : "Create New Shift" }}
          </h2>

          <label class="block mb-2 font-medium">Shift Name</label>
          <input
            v-model="currentShift.name"
            type="text"
            class="w-full px-3 py-2 border rounded mb-4"
          />

          <label class="block mb-2 font-medium">Time Range</label>
          <input
            v-model="currentShift.time"
            type="text"
            placeholder="e.g., 08:00 AM - 12:00 PM"
            class="w-full px-3 py-2 border rounded mb-4"
          />

          <div class="flex justify-end gap-3">
            <button
              @click="showShiftModal = false"
              class="px-3 py-2 bg-gray-300 rounded hover:bg-gray-400"
            >
              Cancel
            </button>
            <button
              @click="saveShift"
              class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              Save
            </button>
          </div>
        </div>
      </div>

      <!-- ============================
           ASSIGN MODAL
      ============================= -->
      <div
        v-if="showAssignModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center"
      >
        <div class="bg-white rounded-lg p-6 w-96 shadow-xl">
          <h2 class="text-xl font-bold mb-4">Assign Shift</h2>

          <label class="block mb-2 font-medium">Employee</label>
          <input
            type="text"
            v-model="currentEmployee.name"
            disabled
            class="w-full px-3 py-2 border rounded mb-4 bg-gray-100"
          />

          <label class="block mb-2 font-medium">Select Shift</label>
          <select
            v-model="currentEmployee.shift"
            class="w-full px-3 py-2 border rounded mb-4"
          >
            <option value="—">No Shift</option>
            <option
              v-for="shift in shifts"
              :key="shift.id"
              :value="shift.name"
            >
              {{ shift.name }}
            </option>
          </select>

          <div class="flex justify-end gap-3">
            <button
              @click="showAssignModal = false"
              class="px-3 py-2 bg-gray-300 rounded hover:bg-gray-400"
            >
              Cancel
            </button>
            <button
              @click="saveAssignment"
              class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700"
            >
              Assign
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

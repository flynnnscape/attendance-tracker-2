<script setup>
import { ref } from "vue";
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

// Tabs
const tabs = [
  { id: 'attendance', label: 'Monthly Attendance' },
  { id: 'late', label: 'Late / Absent' },
  { id: 'payroll', label: 'Payroll Reports' },
];
const activeTab = ref('attendance');

const activeClass = 'bg-indigo-600 text-white';
const inactiveClass = 'bg-gray-100 text-gray-700';

// Sample Data
const attendanceReport = ref([
  { id: 1, employee: 'Juan Dela Cruz', days_present: 22, days_absent: 2, late_days: 1 },
  { id: 2, employee: 'Maria Santos', days_present: 20, days_absent: 4, late_days: 0 },
]);

const lateReport = ref([
  { id: 1, employee: 'Pedro Penduko', date: '2025-11-05', status: 'Late' },
  { id: 2, employee: 'Maria Santos', date: '2025-11-12', status: 'Absent' },
]);

const payrollReport = ref([
  { id: 1, cutoff: 'Nov 1-15', total_employees: 35, total_gross: 875000, total_net: 700000 },
  { id: 2, cutoff: 'Nov 16-30', total_employees: 35, total_gross: 880000, total_net: 704000 },
]);

function exportReport(type) {
  alert(`Export ${type} report (UI-only).`);
}
</script>

<template>
  <Head title="Reports" />
  <AppLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Reports</h1>

      <!-- Tabs -->
      <nav class="mb-6">
        <ul class="flex gap-2 flex-wrap">
          <li v-for="tab in tabs" :key="tab.id">
            <button
              @click="activeTab = tab.id"
              :class="activeTab === tab.id ? activeClass : inactiveClass"
              class="px-4 py-2 rounded"
            >
              {{ tab.label }}
            </button>
          </li>
        </ul>
      </nav>

      <!-- Report Content -->
      <div class="bg-white shadow rounded-lg p-6">

        <!-- Monthly Attendance Report -->
        <section v-if="activeTab === 'attendance'">
          <div class="flex justify-between mb-4">
            <h2 class="font-semibold text-lg">Monthly Attendance Report</h2>
            <button @click="exportReport('Monthly Attendance')" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Export CSV</button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full table-auto border">
              <thead class="bg-gray-50">
                <tr>
                  <th class="p-2 border">Employee</th>
                  <th class="p-2 border">Days Present</th>
                  <th class="p-2 border">Days Absent</th>
                  <th class="p-2 border">Late Days</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="r in attendanceReport" :key="r.id" class="hover:bg-gray-50">
                  <td class="p-2 border">{{ r.employee }}</td>
                  <td class="p-2 border">{{ r.days_present }}</td>
                  <td class="p-2 border">{{ r.days_absent }}</td>
                  <td class="p-2 border">{{ r.late_days }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Late / Absent Report -->
        <section v-if="activeTab === 'late'">
          <div class="flex justify-between mb-4">
            <h2 class="font-semibold text-lg">Late / Absent Report</h2>
            <button @click="exportReport('Late / Absent')" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Export CSV</button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full table-auto border">
              <thead class="bg-gray-50">
                <tr>
                  <th class="p-2 border">Employee</th>
                  <th class="p-2 border">Date</th>
                  <th class="p-2 border">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="r in lateReport" :key="r.id" class="hover:bg-gray-50">
                  <td class="p-2 border">{{ r.employee }}</td>
                  <td class="p-2 border">{{ r.date }}</td>
                  <td class="p-2 border">{{ r.status }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Payroll Reports -->
        <section v-if="activeTab === 'payroll'">
          <div class="flex justify-between mb-4">
            <h2 class="font-semibold text-lg">Payroll Reports</h2>
            <button @click="exportReport('Payroll')" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Export CSV</button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full table-auto border">
              <thead class="bg-gray-50">
                <tr>
                  <th class="p-2 border">Cutoff</th>
                  <th class="p-2 border">Total Employees</th>
                  <th class="p-2 border">Total Gross</th>
                  <th class="p-2 border">Total Net</th>
                  <th class="p-2 border">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="r in payrollReport" :key="r.id" class="hover:bg-gray-50">
                  <td class="p-2 border">{{ r.cutoff }}</td>
                  <td class="p-2 border">{{ r.total_employees }}</td>
                  <td class="p-2 border">{{ r.total_gross.toLocaleString() }}</td>
                  <td class="p-2 border">{{ r.total_net.toLocaleString() }}</td>
                  <td class="p-2 border">
                    <button class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">View</button>
                    <button class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">Payslips</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

      </div>
    </div>
  </AppLayout>
</template>

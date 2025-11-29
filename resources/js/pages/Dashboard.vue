<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const page = usePage();
const roles = page.props.auth.roles;

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: dashboard().url },
];

// Sample stats
const stats = ref({
  employees: 35,
  attendance: { present: 28, absent: 5, late: 3 },
  payroll: { total_gross: 1250000, total_net: 1000000 },
});

// Attendance percentages for pie chart
const totalAttendance = computed(() =>
  stats.value.attendance.present +
  stats.value.attendance.absent +
  stats.value.attendance.late
);

const attendancePercent = computed(() => ({
  present: (stats.value.attendance.present / totalAttendance.value) * 100,
  absent: (stats.value.attendance.absent / totalAttendance.value) * 100,
  late: (stats.value.attendance.late / totalAttendance.value) * 100,
}));

// Payroll percentages for bar chart
const totalPayroll = stats.value.payroll.total_gross + stats.value.payroll.total_net;
const grossPercent = (stats.value.payroll.total_gross / totalPayroll) * 100;
const netPercent = (stats.value.payroll.total_net / totalPayroll) * 100;
</script>

<template>
  <Head title="Dashboard" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <!-- Admin Dashboard -->
    <div v-if="roles.includes('Admin')" class="p-6 space-y-6">
      <h1 class="text-2xl font-bold">Admin Dashboard</h1>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Employees Card -->
        <div class="bg-white shadow rounded-lg p-6 text-center">
          <h2 class="text-lg font-semibold mb-2">Employees</h2>
          <span class="text-4xl font-bold text-indigo-600">{{ stats.employees }}</span>
          <p class="text-gray-500 mt-1">Total Employees</p>
        </div>

        <!-- Attendance Card -->
        <div class="bg-white shadow rounded-lg p-6 flex flex-col items-center">
          <h2 class="text-lg font-semibold mb-4">Attendance</h2>
          <div
            class="w-40 h-40 rounded-full"
            :style="{
              background: `conic-gradient(#10B981 0% ${attendancePercent.present}%, #EF4444 ${attendancePercent.present}% ${attendancePercent.present + attendancePercent.absent}%, #F59E0B ${attendancePercent.present + attendancePercent.absent}% 100%)`
            }"
          ></div>
          <div class="mt-4 w-full space-y-1">
            <div class="flex justify-between text-sm font-medium text-gray-700">
              <span class="text-green-600">Present</span> <span>{{ stats.attendance.present }}</span>
            </div>
            <div class="flex justify-between text-sm font-medium text-gray-700">
              <span class="text-red-600">Absent</span> <span>{{ stats.attendance.absent }}</span>
            </div>
            <div class="flex justify-between text-sm font-medium text-gray-700">
              <span class="text-yellow-600">Late</span> <span>{{ stats.attendance.late }}</span>
            </div>
          </div>
        </div>

        <!-- Payroll Card -->
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
          <h2 class="text-lg font-semibold">Payroll Summary</h2>

          <div>
            <p class="text-gray-700 font-medium text-sm mb-1">Total Gross</p>
            <div class="w-full h-6 bg-gray-200 rounded-full">
              <div
                class="h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm"
                :style="{ width: Math.min(100, grossPercent) + '%' }"
              >
                ₱{{ stats.payroll.total_gross.toLocaleString() }}
              </div>
            </div>
          </div>

          <div>
            <p class="text-gray-700 font-medium text-sm mb-1">Total Net</p>
            <div class="w-full h-6 bg-gray-200 rounded-full">
              <div
                class="h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-sm"
                :style="{ width: Math.min(100, netPercent) + '%' }"
              >
                ₱{{ stats.payroll.total_net.toLocaleString() }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Detailed Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6 space-y-2">
          <h3 class="text-lg font-semibold">Attendance Details</h3>
          <ul class="space-y-1 text-sm text-gray-700 font-medium">
            <li class="flex justify-between"><span>Present</span> <span class="text-green-600">{{ stats.attendance.present }}</span></li>
            <li class="flex justify-between"><span>Absent</span> <span class="text-red-600">{{ stats.attendance.absent }}</span></li>
            <li class="flex justify-between"><span>Late</span> <span class="text-yellow-600">{{ stats.attendance.late }}</span></li>
          </ul>
        </div>

        <div class="bg-white shadow rounded-lg p-6 space-y-1">
          <h3 class="text-lg font-semibold">Payroll Details</h3>
          <ul class="space-y-1 text-sm text-gray-700 font-medium">
            <li class="flex justify-between"><span>Total Gross</span> <span class="text-blue-600">₱{{ stats.payroll.total_gross.toLocaleString() }}</span></li>
            <li class="flex justify-between"><span>Total Net</span> <span class="text-green-600">₱{{ stats.payroll.total_net.toLocaleString() }}</span></li>
          </ul>
        </div>

        <div class="bg-white shadow rounded-lg p-6 flex items-center justify-center">
          <div class="text-center">
            <span class="text-4xl font-bold text-indigo-600">{{ stats.employees }}</span>
            <p class="text-gray-500 mt-2">Employees</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Users Dashboard -->
    <div v-else-if="roles.includes('Users')" class="p-6 space-y-6">
      <h1 class="text-2xl font-bold">User Dashboard</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Attendance Summary -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-semibold mb-4">Attendance Summary</h2>
          <ul class="space-y-2 text-sm font-medium text-gray-700">
            <li class="flex justify-between"><span>Present</span> <span class="text-green-600">{{ stats.attendance.present }}</span></li>
            <li class="flex justify-between"><span>Absent</span> <span class="text-red-600">{{ stats.attendance.absent }}</span></li>
            <li class="flex justify-between"><span>Late</span> <span class="text-yellow-600">{{ stats.attendance.late }}</span></li>
          </ul>
        </div>

        <!-- Payroll Summary -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-semibold mb-4">Payroll Summary (Latest Cutoff)</h2>
          <ul class="space-y-2 text-sm font-medium text-gray-700">
            <li class="flex justify-between"><span>Total Gross</span> <span class="text-blue-600">₱{{ stats.payroll.total_gross.toLocaleString() }}</span></li>
            <li class="flex justify-between"><span>Total Net</span> <span class="text-green-600">₱{{ stats.payroll.total_net.toLocaleString() }}</span></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- HR Dashboard -->
    <div v-else-if="roles.includes('HR')" class="p-6 space-y-6">
      <h1 class="text-2xl font-bold">HR Dashboard</h1>

      <!-- Alerts -->
      <div class="space-y-2">
        <div class="p-3 rounded-lg bg-red-500 text-white font-medium">5 employees are absent today.</div>
        <div class="p-3 rounded-lg bg-yellow-500 text-white font-medium">3 employees are late today.</div>
        <div class="p-3 rounded-lg bg-blue-500 text-white font-medium">Next payroll cutoff is on Nov 30, 2025.</div>
      </div>

      <!-- Placeholder Content -->
      <div class="grid gap-4 md:grid-cols-2">
        <div class="relative aspect-video rounded-xl border"><PlaceholderPattern /></div>
        <div class="relative aspect-video rounded-xl border"><PlaceholderPattern /></div>
      </div>
    </div>

    <!-- Head Dashboard -->
    <div v-else-if="roles.includes('Head')" class="p-6 space-y-6">
      <h1 class="text-2xl font-bold">Department Head Dashboard</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Team Attendance -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-semibold mb-4">Team Attendance</h2>
          <ul class="space-y-2 text-sm font-medium text-gray-700">
            <li class="flex justify-between"><span>Present</span> <span class="text-green-600">{{ stats.attendance.present }}</span></li>
            <li class="flex justify-between"><span>Absent</span> <span class="text-red-600">{{ stats.attendance.absent }}</span></li>
            <li class="flex justify-between"><span>Late</span> <span class="text-yellow-600">{{ stats.attendance.late }}</span></li>
          </ul>
        </div>

        <!-- Team Payroll Summary -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-semibold mb-4">Team Payroll Summary</h2>
          <ul class="space-y-2 text-sm font-medium text-gray-700">
            <li class="flex justify-between"><span>Total Gross</span> <span class="text-blue-600">₱{{ stats.payroll.total_gross.toLocaleString() }}</span></li>
            <li class="flex justify-between"><span>Total Net</span> <span class="text-green-600">₱{{ stats.payroll.total_net.toLocaleString() }}</span></li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

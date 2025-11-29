<template>
  <Head title="Payroll Management" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <header class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Payroll Management</h1>
        <div class="flex gap-3">
          <button @click="openGenerate" class="px-4 py-2 bg-indigo-600 text-white rounded">Generate Payroll</button>
          <button @click="downloadSample" class="px-4 py-2 bg-gray-100 rounded">Download Sample Log</button>
        </div>
      </header>

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

       <div class="bg-white shadow rounded-lg p-6">
         <section v-if="activeTab === 'generate'">
          <div class="flex gap-4 items-center mb-4">
            <label class="font-medium">Cutoff Start</label>
            <input v-model="generate.cutoff_start" type="date" class="border rounded px-3 py-2" />
            <label class="font-medium">Cutoff End</label>
            <input v-model="generate.cutoff_end" type="date" class="border rounded px-3 py-2" />
            <label class="font-medium">Run For</label>
            <select v-model="generate.run_for" class="border rounded px-3 py-2">
              <option value="all">All employees</option>
              <option value="department">By department</option>
            </select>
            <button @click="previewPayroll" class="px-3 py-2 bg-green-600 text-white rounded">Preview</button>
            <button @click="runPayroll" class="px-3 py-2 bg-indigo-600 text-white rounded">Run</button>
          </div>

          <div v-if="payrollPreview.length === 0" class="text-gray-500 p-6 border rounded">
            No preview yet — click <strong>Preview</strong> to compute payroll sample for the selected cutoff.
          </div>

          <div v-else>
            <h3 class="font-semibold mb-2">Payroll Preview (first 10)</h3>
            <div class="overflow-x-auto">
              <table class="w-full table-auto border">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="p-2 border">Employee</th>
                    <th class="p-2 border">Gross</th>
                    <th class="p-2 border">Deductions</th>
                    <th class="p-2 border">Net</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="r in payrollPreview.slice(0,10)" :key="r.id" class="hover:bg-gray-50">
                    <td class="p-2 border">{{ r.user }}</td>
                    <td class="p-2 border">{{ r.gross | currency }}</td>
                    <td class="p-2 border">{{ r.deductions | currency }}</td>
                    <td class="p-2 border font-semibold">{{ r.net | currency }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>

         <section v-if="activeTab === 'settings'">
          <h3 class="text-lg font-semibold mb-3">Payroll Settings</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 border rounded">
              <h4 class="font-medium mb-2">Rates</h4>
              <label class="block mb-1 text-sm">Hourly Rate (default)</label>
              <input v-model.number="settings.hourly_rate" type="number" class="w-full border rounded px-3 py-2" />
              <label class="block mt-3 mb-1 text-sm">Overtime Multiplier</label>
              <input v-model.number="settings.overtime_multiplier" type="number" step="0.1" class="w-full border rounded px-3 py-2" />
            </div>

            <div class="p-4 border rounded">
              <h4 class="font-medium mb-2">Cutoffs</h4>
              <label class="block mb-1 text-sm">Default Cutoff Days</label>
              <select v-model="settings.cutoff" class="w-full border rounded px-3 py-2">
                <option value="15">15th & end of month</option>
                <option value="30">Monthly</option>
              </select>

              <label class="block mt-3 mb-1 text-sm">Payroll Currency</label>
              <input v-model="settings.currency" type="text" class="w-full border rounded px-3 py-2" />
            </div>
          </div>

          <div class="mt-4 flex justify-end gap-2">
            <button @click="resetSettings" class="px-3 py-2 bg-gray-200 rounded">Reset</button>
            <button @click="saveSettings" class="px-3 py-2 bg-indigo-600 text-white rounded">Save Settings</button>
          </div>
        </section>

         <section v-if="activeTab === 'structures'">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">Salary Structures</h3>
            <button @click="openAddStructure" class="px-3 py-2 bg-green-600 text-white rounded">+ Add Structure</button>
          </div>

          <div class="space-y-3">
            <div v-for="s in structures" :key="s.id" class="p-3 border rounded flex justify-between items-center">
              <div>
                <div class="font-medium">{{ s.title }}</div>
                <div class="text-sm text-gray-600">Base: {{ s.base | currency }} • Type: {{ s.type }}</div>
              </div>
              <div class="flex gap-2">
                <button @click="editStructure(s)" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                <button @click="deleteStructure(s.id)" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
              </div>
            </div>
          </div>

           <div v-if="showStructureModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-40">
            <div class="bg-white w-96 p-6 rounded shadow-xl">
              <h4 class="font-semibold mb-3">{{ structureEdit.id ? 'Edit' : 'Add' }} Structure</h4>
              <label class="block mb-1">Title</label>
              <input v-model="structureEdit.title" class="w-full border rounded px-3 py-2 mb-3" />
              <label class="block mb-1">Base Salary</label>
              <input v-model.number="structureEdit.base" type="number" class="w-full border rounded px-3 py-2 mb-3" />
              <label class="block mb-1">Type</label>
              <select v-model="structureEdit.type" class="w-full border rounded px-3 py-2 mb-4">
                <option>Monthly</option>
                <option>Daily</option>
                <option>Hourly</option>
              </select>

              <div class="flex justify-end gap-2">
                <button @click="showStructureModal=false" class="px-3 py-2 bg-gray-200 rounded">Cancel</button>
                <button @click="saveStructure" class="px-3 py-2 bg-indigo-600 text-white rounded">Save</button>
              </div>
            </div>
          </div>
        </section>

         <section v-if="activeTab === 'allowances'">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">Allowances & Benefits</h3>
            <button @click="openAllowanceModal" class="px-3 py-2 bg-green-600 text-white rounded">+ Add Allowance</button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div v-for="a in allowances" :key="a.id" class="p-3 border rounded">
              <div class="flex justify-between">
                <div>
                  <div class="font-medium">{{ a.title }}</div>
                  <div class="text-sm text-gray-600">{{ a.type }} • {{ a.amount | currency }}</div>
                </div>
                <div class="flex gap-2">
                  <button @click="editAllowance(a)" class="px-2 py-1 bg-yellow-500 text-white rounded">Edit</button>
                  <button @click="deleteAllowance(a.id)" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
                </div>
              </div>
            </div>
          </div>

           <div v-if="showAllowance" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-40">
            <div class="bg-white w-96 p-6 rounded shadow-xl">
              <h4 class="font-semibold mb-3">{{ allowanceEdit.id ? 'Edit' : 'Add' }} Allowance</h4>
              <label class="block mb-1">Title</label>
              <input v-model="allowanceEdit.title" class="w-full border rounded px-3 py-2 mb-2" />
              <label class="block mb-1">Amount</label>
              <input v-model.number="allowanceEdit.amount" type="number" class="w-full border rounded px-3 py-2 mb-2" />
              <label class="block mb-1">Type</label>
              <select v-model="allowanceEdit.type" class="w-full border rounded px-3 py-2 mb-4">
                <option>Fixed</option>
                <option>Percentage</option>
              </select>

              <div class="flex justify-end gap-2">
                <button @click="showAllowance=false" class="px-3 py-2 bg-gray-200 rounded">Cancel</button>
                <button @click="saveAllowance" class="px-3 py-2 bg-indigo-600 text-white rounded">Save</button>
              </div>
            </div>
          </div>
        </section>

         <section v-if="activeTab === 'deductions'">
          <h3 class="font-semibold mb-3">Automatic Deductions</h3>
          <div class="space-y-4">
            <div v-for="(d, key) in deductions" :key="key" class="p-4 border rounded">
              <div class="flex justify-between items-center">
                <div>
                  <div class="font-medium">{{ d.label }}</div>
                  <div class="text-sm text-gray-600">{{ d.description }}</div>
                </div>

                <div class="w-1/3">
                  <label class="text-sm block mb-1">Rate / Bracket</label>
                  <input v-model="d.rate" class="w-full border rounded px-3 py-2" />
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4 flex justify-end gap-2">
            <button @click="resetDeductions" class="px-3 py-2 bg-gray-200 rounded">Reset</button>
            <button @click="saveDeductions" class="px-3 py-2 bg-indigo-600 text-white rounded">Save Deductions</button>
          </div>
        </section>

         <section v-if="activeTab === 'upload'">
          <h3 class="font-semibold mb-3">Upload Biometric Logs</h3>
          <div class="p-4 border rounded">
            <label class="block mb-2">Select CSV / Excel file</label>
            <input ref="logFile" type="file" accept=".csv, .xlsx, .xls" @change="onFileChange" />
            <div class="mt-3">
              <label class="block mb-1">Map Columns</label>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <select v-model="logMapping.user_col" class="border rounded px-3 py-2">
                  <option disabled value="">Employee Column</option>
                  <option>employee_id</option>
                  <option>name</option>
                </select>
                <select v-model="logMapping.date_col" class="border rounded px-3 py-2">
                  <option disabled value="">Date Column</option>
                  <option>date</option>
                </select>
                <select v-model="logMapping.time_col" class="border rounded px-3 py-2">
                  <option disabled value="">Time Column</option>
                  <option>time</option>
                </select>
              </div>
            </div>

            <div class="mt-4 flex gap-2">
              <button @click="previewLogs" class="px-3 py-2 bg-gray-100 rounded">Preview</button>
              <button @click="importLogs" class="px-3 py-2 bg-indigo-600 text-white rounded">Import</button>
            </div>

            <div v-if="logPreview.length" class="mt-4">
              <h4 class="font-medium mb-2">Preview</h4>
              <div class="overflow-x-auto">
                <table class="w-full table-auto border">
                  <thead class="bg-gray-50">
                    <tr><th class="p-2 border">Employee</th><th class="p-2 border">Date</th><th class="p-2 border">Time</th></tr>
                  </thead>
                  <tbody>
                    <tr v-for="(l,i) in logPreview.slice(0,8)" :key="i" class="hover:bg-gray-50">
                      <td class="p-2 border">{{ l.user }}</td>
                      <td class="p-2 border">{{ l.date }}</td>
                      <td class="p-2 border">{{ l.time }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

         <section v-if="activeTab === 'reports'">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">Payroll Reports</h3>
            <div class="flex gap-2">
              <select v-model="report.filter" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="this_month">This month</option>
                <option value="last_month">Last month</option>
              </select>
              <button @click="exportReport" class="px-3 py-2 bg-indigo-600 text-white rounded">Export CSV</button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full table-auto border">
              <thead class="bg-gray-50">
                <tr>
                  <th class="p-2 border">Cutoff</th>
                  <th class="p-2 border">Employees</th>
                  <th class="p-2 border">Total Gross</th>
                  <th class="p-2 border">Total Net</th>
                  <th class="p-2 border">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="r in reports" :key="r.id" class="hover:bg-gray-50">
                  <td class="p-2 border">{{ r.cutoff }}</td>
                  <td class="p-2 border">{{ r.employees }}</td>
                  <td class="p-2 border">{{ r.total_gross | currency }}</td>
                  <td class="p-2 border">{{ r.total_net | currency }}</td>
                  <td class="p-2 border">
                    <button class="px-2 py-1 bg-gray-100 rounded">View</button>
                    <button class="px-2 py-1 bg-gray-100 rounded">Payslips</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

         <section v-if="activeTab === 'payslips'">
          <h3 class="font-semibold mb-3">Payslip Templates</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1">Select Template</label>
              <select v-model="selectedTemplate" class="w-full border rounded px-3 py-2 mb-3">
                <option v-for="t in payslipTemplates" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>

              <label class="block mb-1">Template HTML</label>
              <textarea v-model="templateEditor" rows="10" class="w-full border rounded px-3 py-2"></textarea>

              <div class="mt-3 flex gap-2 justify-end">
                <button @click="resetTemplate" class="px-3 py-2 bg-gray-200 rounded">Reset</button>
                <button @click="saveTemplate" class="px-3 py-2 bg-indigo-600 text-white rounded">Save</button>
              </div>
            </div>

            <div>
              <label class="block mb-1">Live Preview</label>
              <div class="p-4 border rounded min-h-[200px]">
                <div v-html="templateEditorPreview"></div>
              </div>
            </div>
          </div>
        </section>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const breadcrumbs = [
  {
    title: 'Payroll',
    href: '/payroll',
  },
];

 const tabs = [
  { id: 'generate', label: 'Generate Payroll' },
  { id: 'settings', label: 'Payroll Settings' },
  { id: 'structures', label: 'Salary Structures' },
  { id: 'allowances', label: 'Allowances & Benefits' },
  { id: 'deductions', label: 'Deductions' },
  { id: 'upload', label: 'Upload Logs' },
  { id: 'reports', label: 'Payroll Reports' },
  { id: 'payslips', label: 'Payslip Templates' },
];

const activeTab = ref('generate');

const activeClass = 'bg-indigo-600 text-white';
const inactiveClass = 'bg-gray-100 text-gray-700';

 const generate = reactive({
  cutoff_start: '',
  cutoff_end: '',
  run_for: 'all',
});

const payrollPreview = ref([]);

 const settings = reactive({
  hourly_rate: 100,
  overtime_multiplier: 1.5,
  cutoff: '15',
  currency: 'PHP',
});

 const structures = ref([
  { id: 1, title: 'Full-time (Monthly)', base: 25000, type: 'Monthly' },
  { id: 2, title: 'Part-time (Hourly)', base: 75, type: 'Hourly' },
]);
const showStructureModal = ref(false);
const structureEdit = reactive({ id: null, title: '', base: 0, type: 'Monthly' });

 const allowances = ref([
  { id: 1, title: 'Transport Allowance', amount: 2000, type: 'Fixed' },
  { id: 2, title: 'Meal Allowance', amount: 5, type: 'Percentage' },
]);
const showAllowance = ref(false);
const allowanceEdit = reactive({ id: null, title: '', amount: 0, type: 'Fixed' });

 const deductions = reactive({
  sss: { label: 'SSS', description: 'Social Security System', rate: 'Standard table' },
  pagibig: { label: 'Pag-IBIG', description: 'HDMF Contribution', rate: 'Fixed 100' },
  philhealth: { label: 'PhilHealth', description: 'Health Insurance', rate: 'Standard table' },
  tax: { label: 'Withholding Tax', description: 'Government Tax', rate: 'Progressive' },
});

 const logFile = ref(null);
const logMapping = reactive({ user_col: '', date_col: '', time_col: '' });
const logPreview = ref([]);

 const report = reactive({ filter: '' });
const reports = ref([
  { id: 1, cutoff: '2025-11-01 to 2025-11-15', employees: 35, total_gross: 875000, total_net: 700000 },
  { id: 2, cutoff: '2025-11-16 to 2025-11-30', employees: 35, total_gross: 880000, total_net: 704000 },
]);

 const payslipTemplates = ref([
  { id: 1, name: 'Standard', html: '<h3>Payslip</h3><p>{{employee}}</p>' },
  { id: 2, name: 'Detailed', html: '<h3>Detailed Payslip</h3><p>{{employee}}</p>' },
]);
const selectedTemplate = ref(1);
const templateEditor = ref(payslipTemplates.value.find(t=>t.id===1).html);

 function openGenerate(){ activeTab.value = 'generate'; scrollToTop(); }
function downloadSample(){ alert('Download sample biometric CSV (UI-only).'); }
function previewPayroll(){
   payrollPreview.value = [
    { id: 1, user: 'Juan Dela Cruz', gross: 30000, deductions: 4500, net: 25500 },
    { id: 2, user: 'Maria Santos', gross: 25000, deductions: 3750, net: 21250 },
  ];
  activeTab.value = 'generate';
}
function runPayroll(){ alert('Payroll run (UI-only).'); }

 function saveSettings(){ alert('Settings saved (UI-only).'); }
function resetSettings(){ settings.hourly_rate = 100; settings.overtime_multiplier = 1.5; settings.cutoff='15'; settings.currency='PHP'; }

 function openAddStructure(){ structureEdit.id = null; structureEdit.title=''; structureEdit.base=0; structureEdit.type='Monthly'; showStructureModal.value=true; }
function editStructure(s){ structureEdit.id = s.id; structureEdit.title=s.title; structureEdit.base=s.base; structureEdit.type=s.type; showStructureModal.value=true; }
function saveStructure(){
  if(structureEdit.id){
    const i = structures.value.findIndex(x=>x.id===structureEdit.id);
    structures.value[i] = {...structureEdit};
  } else {
    structures.value.push({ id: Date.now(), title: structureEdit.title, base: structureEdit.base, type: structureEdit.type });
  }
  showStructureModal.value=false;
}
function deleteStructure(id){ structures.value = structures.value.filter(s=>s.id!==id); }

 
function openAllowanceModal(){ allowanceEdit.id=null; allowanceEdit.title=''; allowanceEdit.amount=0; allowanceEdit.type='Fixed'; showAllowance.value=true; }
function editAllowance(a){ allowanceEdit.id=a.id; allowanceEdit.title=a.title; allowanceEdit.amount=a.amount; allowanceEdit.type=a.type; showAllowance.value=true; }
function saveAllowance(){
  if(allowanceEdit.id){
    const i = allowances.value.findIndex(x=>x.id===allowanceEdit.id);
    allowances.value[i] = {...allowanceEdit};
  } else {
    allowances.value.push({ id: Date.now(), title: allowanceEdit.title, amount: allowanceEdit.amount, type: allowanceEdit.type });
  }
  showAllowance.value=false;
}
function deleteAllowance(id){ allowances.value = allowances.value.filter(a=>a.id!==id); }

 function saveDeductions(){ alert('Deductions saved (UI-only).'); }
function resetDeductions(){ window.location.reload(); }

 function onFileChange(e){ logFile.value = e.target.files[0]; }
function previewLogs(){
  if(!logFile.value){ alert('Choose a file first (UI-only).'); return; }
   logPreview.value = [
    { user: 'Juan Dela Cruz', date: '2025-11-27', time: '08:05' },
    { user: 'Maria Santos', date: '2025-11-27', time: '08:10' },
  ];
}
function importLogs(){ alert('Import started (UI-only).'); }

 function exportReport(){ alert('Export CSV (UI-only).'); }

 function resetTemplate(){ const t = payslipTemplates.value.find(x => x.id === selectedTemplate.value); templateEditor.value = t?.html ?? ''; }
function saveTemplate(){ const i = payslipTemplates.value.findIndex(t=>t.id===selectedTemplate.value); if(i>-1) payslipTemplates.value[i].html = templateEditor.value; alert('Template saved (UI-only).'); }

function editStructureEmit(){   }

function scrollToTop(){ window.scrollTo({top:0, behavior:'smooth'}); }

 function formatCurrency(v){ return (Number(v) || 0).toLocaleString() + ' ' + settings.currency; }

const templateEditorPreview = computed(()=> {
   return templateEditor.value.replace(/\{\{employee\}\}/g, '<strong>Juan Dela Cruz</strong>');
});

 
const currency = (val) => formatCurrency(val);

 </script>

<script>
 export default {
  methods: {
   },
  computed: {
  }
}
</script>

<style scoped>
 </style>
 